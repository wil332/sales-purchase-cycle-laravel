<?php

namespace App\Http\Controllers\Admin\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Purchase\PurchasePayment;
use App\Models\Purchase\PurchaseInvoice;
use App\Models\Master\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchasePaymentController extends Controller
{
    protected function detailRules(): array
    {
        return [
            'details'                        => 'nullable|array',
            'details.*.no_invoice_beli'      => 'required_with:details|string|exists:nota_tagihan_pembelian,no_invoice_beli',
            'details.*.jumlah_dialokasikan'  => 'required_with:details|numeric|min:0',
            'details.*.keterangan'           => 'nullable|string',
        ];
    }

    public function index()
    {
        $items = PurchasePayment::withCount('details')
            ->with(['purchaseInvoice', 'pengguna'])
            ->latest('no_pembayaran_beli')
            ->paginate(15);

        return view('admin.purchase.payments.index', ['items' => $items]);
    }

    public function create()
    {
        $nextNo = \App\Services\CodeGenerator::generatePurchasePaymentNo();
        return view('admin.purchase.payments.create', [
            'nextNo'       => $nextNo,
            'invoiceList'  => PurchaseInvoice::whereIn('status', [1, 2])->orderBy('no_invoice_beli')->get(),
            'penggunaList' => Pengguna::jabatan([ 'admin'])->orderBy('nama_lengkap')->get(),
        ]);
    }

    public function store(Request $request)
    {
        if (empty($request->no_pembayaran_beli)) {
            $request->merge(['no_pembayaran_beli' => \App\Services\CodeGenerator::generatePurchasePaymentNo()]);
        }

        $validated = $request->validate(array_merge([
            'no_pembayaran_beli' => 'required|string|max:50|unique:nota_pelunasan_pembelian,no_pembayaran_beli',
            'tanggal_bayar'      => 'required|date',
            'no_invoice_beli'    => 'required|string|exists:nota_tagihan_pembelian,no_invoice_beli',
            'id_pengguna'        => 'nullable|integer|exists:m_pengguna,id_pengguna',
            'jumlah_bayar'       => 'required|numeric|min:0',
            'metode_bayar'       => 'required|string|in:tunai,transfer,cek',
            'keterangan'         => 'nullable|string',
            'status'             => 'required|integer',
        ], $this->detailRules()), [
            'no_pembayaran_beli.unique' => 'Nomor Bukti Bayar ":input" sudah digunakan. Silakan gunakan nomor lain atau generate otomatis.',
            'no_pembayaran_beli.required' => 'Nomor Bukti Bayar wajib diisi.',
            'no_invoice_beli.required' => 'Silakan pilih Invoice Pembelian yang akan dibayar.',
        ]);

        $details = $validated['details'] ?? [];
        unset($validated['details']);

        if (empty($details)) {
            $details = [[
                'no_invoice_beli'     => $validated['no_invoice_beli'],
                'jumlah_dialokasikan' => $validated['jumlah_bayar'],
                'keterangan'          => null,
            ]];
        }

        $payment = PurchasePayment::createWithDetails($validated, $details);

        // Update status for all affected invoices
        foreach ($details as $d) {
            if (!empty($d['no_invoice_beli'])) {
                $inv = PurchaseInvoice::find($d['no_invoice_beli']);
                if ($inv) $inv->updatePaymentStatus();
            }
        }

        return redirect()->route('admin.purchase.payments.index')->with('success', 'Pembayaran berhasil dicatat.');
    }

    public function show($id)
    {
        $item = PurchasePayment::with(['details.purchaseInvoice', 'purchaseInvoice', 'pengguna'])->findOrFail($id);

        return view('admin.purchase.payments.show', ['item' => $item]);
    }

    public function edit($id)
    {
        $item = PurchasePayment::with('details')->findOrFail($id);
        $currentInvoiceNo = $item->no_invoice_beli;

        $invoiceList = PurchaseInvoice::where(function ($q) use ($currentInvoiceNo) {
            $q->whereIn('status', [1, 2]);
            if ($currentInvoiceNo) {
                $q->orWhere('no_invoice_beli', $currentInvoiceNo);
            }
        })->orderBy('no_invoice_beli')->get();

        return view('admin.purchase.payments.edit', [
            'item'         => $item,
            'invoiceList'  => $invoiceList,
            'penggunaList' => Pengguna::jabatan(['admin'])->orderBy('nama_lengkap')->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $item = PurchasePayment::findOrFail($id);
        $oldInvoiceNo = $item->no_invoice_beli;

        $validated = $request->validate(array_merge([
            'tanggal_bayar'   => 'required|date',
            'no_invoice_beli' => 'required|string|exists:nota_tagihan_pembelian,no_invoice_beli',
            'id_pengguna'     => 'nullable|integer|exists:m_pengguna,id_pengguna',
            'jumlah_bayar'    => 'required|numeric|min:0',
            'metode_bayar'    => 'required|string|in:tunai,transfer,cek',
            'keterangan'      => 'nullable|string',
            'status'          => 'required|integer',
        ], $this->detailRules()));

        $details = $validated['details'] ?? [];
        unset($validated['details']);

        if (empty($details)) {
            $details = [[
                'no_invoice_beli'     => $validated['no_invoice_beli'],
                'jumlah_dialokasikan' => $validated['jumlah_bayar'],
                'keterangan'          => null,
            ]];
        }

        $item->update($validated);
        $item->syncDetails($details);

        // Update status for old & new affected invoices
        if ($oldInvoiceNo) {
            $invOld = PurchaseInvoice::find($oldInvoiceNo);
            if ($invOld) $invOld->updatePaymentStatus();
        }
        foreach ($details as $d) {
            if (!empty($d['no_invoice_beli'])) {
                $inv = PurchaseInvoice::find($d['no_invoice_beli']);
                if ($inv) $inv->updatePaymentStatus();
            }
        }

        return redirect()->route('admin.purchase.payments.index')->with('success', 'Pembayaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = PurchasePayment::with('details')->findOrFail($id);
        $affectedInvoices = $item->details->pluck('no_invoice_beli')->push($item->no_invoice_beli)->filter()->unique();

        DB::transaction(function () use ($item) {
            $item->details()->delete();
            $item->delete();
        });

        foreach ($affectedInvoices as $invNo) {
            $inv = PurchaseInvoice::find($invNo);
            if ($inv) $inv->updatePaymentStatus();
        }

        return redirect()->route('admin.purchase.payments.index')->with('success', 'Data berhasil dihapus.');
    }
}
