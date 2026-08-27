<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Controller;
use App\Models\Sales\SalesPayment;
use Illuminate\Http\Request;

class SalesPaymentController extends Controller
{
    public function index()
    {
        $items = SalesPayment::with('salesInvoice')->latest('no_pembayaran')->paginate(15);

        return view('admin.sales.payments.index', ['items' => $items]);
    }

    public function create()
    {
        $nextNo = \App\Services\CodeGenerator::generateSalesPaymentNo();
        $invoiceList = \App\Models\Sales\SalesInvoice::whereIn('status', [1, 2])->orderBy('no_invoice_jual')->get();
        return view('admin.sales.payments.create', compact('nextNo', 'invoiceList'));
    }

    public function store(Request $request)
    {
        if (empty($request->no_pembayaran)) {
            $request->merge(['no_pembayaran' => \App\Services\CodeGenerator::generateSalesPaymentNo()]);
        }

        $validated = $request->validate([
            'no_pembayaran' => 'required|string|max:50|unique:nota_pelunasan_penjualan,no_pembayaran',
            'tanggal_bayar' => 'date|required',
            'no_invoice_jual' => 'required|string|exists:nota_tagihan_penjualan,no_invoice_jual',
            'jumlah_bayar' => 'required|numeric|min:0',
            'metode_bayar' => 'required|string|in:tunai,transfer,cek',
            'keterangan' => 'nullable|string',
            'status' => 'required|integer',
        ], [
            'no_pembayaran.unique' => 'Nomor Bukti Bayar ":input" sudah digunakan. Silakan gunakan nomor lain atau generate otomatis.',
            'no_pembayaran.required' => 'Nomor Bukti Bayar wajib diisi.',
            'no_invoice_jual.required' => 'Silakan pilih Faktur Penjualan yang akan dibayar.',
        ]);

        $payment = SalesPayment::create($validated);

        if (!empty($validated['no_invoice_jual'])) {
            $inv = \App\Models\Sales\SalesInvoice::find($validated['no_invoice_jual']);
            if ($inv) $inv->updatePaymentStatus();
        }

        return redirect()->route('admin.sales.payments.index')->with('success', 'Bukti Pelunasan Penjualan berhasil dicatat.');
    }

    public function show($id)
    {
        $item = SalesPayment::with('salesInvoice')->findOrFail($id);

        return view('admin.sales.payments.show', ['item' => $item]);
    }

    public function edit($id)
    {
        $item = SalesPayment::findOrFail($id);
        $currentInvoiceNo = $item->no_invoice_jual;

        $invoiceList = \App\Models\Sales\SalesInvoice::where(function ($q) use ($currentInvoiceNo) {
            $q->whereIn('status', [1, 2]);
            if ($currentInvoiceNo) {
                $q->orWhere('no_invoice_jual', $currentInvoiceNo);
            }
        })->orderBy('no_invoice_jual')->get();

        return view('admin.sales.payments.edit', ['item' => $item, 'invoiceList' => $invoiceList]);
    }

    public function update(Request $request, $id)
    {
        $item = SalesPayment::findOrFail($id);
        $oldInvoiceNo = $item->no_invoice_jual;

        $validated = $request->validate([
            'tanggal_bayar' => 'date|required',
            'no_invoice_jual' => 'required|string|exists:nota_tagihan_penjualan,no_invoice_jual',
            'jumlah_bayar' => 'required|numeric|min:0',
            'metode_bayar' => 'required|string|in:tunai,transfer,cek',
            'keterangan' => 'nullable|string',
            'status' => 'required|integer',
        ]);

        $item->update($validated);

        if ($oldInvoiceNo) {
            $invOld = \App\Models\Sales\SalesInvoice::find($oldInvoiceNo);
            if ($invOld) $invOld->updatePaymentStatus();
        }
        if (!empty($validated['no_invoice_jual'])) {
            $invNew = \App\Models\Sales\SalesInvoice::find($validated['no_invoice_jual']);
            if ($invNew) $invNew->updatePaymentStatus();
        }

        return redirect()->route('admin.sales.payments.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = SalesPayment::findOrFail($id);
        $invoiceNo = $item->no_invoice_jual;

        $item->delete();

        if ($invoiceNo) {
            $inv = \App\Models\Sales\SalesInvoice::find($invoiceNo);
            if ($inv) $inv->updatePaymentStatus();
        }

        return redirect()->route('admin.sales.payments.index')->with('success', 'Data berhasil dihapus.');
    }
}
