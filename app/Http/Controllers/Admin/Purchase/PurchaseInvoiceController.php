<?php

namespace App\Http\Controllers\Admin\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Purchase\PurchaseInvoice;
use App\Models\Purchase\GoodsReceipt;
use App\Models\Master\Barang;
use App\Models\Master\Vendor;
use App\Models\Master\Pengguna;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseInvoiceController extends Controller
{
    protected function detailRules(): array
    {
        return [
            'details'                 => 'required|array|min:1',
            'details.*.sku'           => 'required|string|exists:m_barang,sku',
            'details.*.kuantitas'     => 'required|integer|min:1',
            'details.*.harga_unit'    => 'required|numeric|min:0',
            'details.*.diskon'        => 'nullable|numeric|min:0',
            'details.*.keterangan'    => 'nullable|string',
        ];
    }

    public function index()
    {
        $items = PurchaseInvoice::withCount('details')
            ->with(['vendor', 'goodsReceipt'])
            ->latest('no_invoice_beli')
            ->paginate(15);

        return view('admin.purchase.invoices.index', ['items' => $items]);
    }

    public function create()
    {
        $nextNo = \App\Services\CodeGenerator::generatePurchaseInvoiceNo();
        return view('admin.purchase.invoices.create', [
            'nextNo'      => $nextNo,
            'barangList'  => Barang::where('status', 1)->orderBy('nama_barang')->get(),
            'vendorList'  => Vendor::where('status', 1)->orderBy('nama_vendor')->get(),
            'penggunaList' => Pengguna::jabatan(['purchasing', 'admin'])->orderBy('nama_lengkap')->get(),
            'receiptList' => GoodsReceipt::with('purchaseOrder.vendor')->orderBy('no_penerimaan')->get(),
        ]);
    }

    public function store(Request $request)
    {
        if (empty($request->no_invoice_beli)) {
            $request->merge(['no_invoice_beli' => \App\Services\CodeGenerator::generatePurchaseInvoiceNo()]);
        }

        $validated = $request->validate(array_merge([
            'no_invoice_beli' => 'required|string|max:50|unique:nota_tagihan_pembelian,no_invoice_beli',
            'tanggal'         => 'required|date',
            'no_penerimaan'   => 'nullable|string|exists:nota_penerimaan_barang,no_penerimaan',
            'id_vendor'       => 'nullable|integer|exists:m_vendor,id_vendor',
            'id_pengguna'     => 'nullable|integer|exists:m_pengguna,id_pengguna',
            'jatuh_tempo'     => 'required|date',
            'keterangan'      => 'nullable|string',
            'status'          => 'required|integer',
        ], $this->detailRules()), [
            'no_invoice_beli.unique' => 'Nomor Faktur Beli ":input" sudah digunakan. Silakan gunakan nomor lain atau generate otomatis.',
            'no_invoice_beli.required' => 'Nomor Faktur Pembelian wajib diisi.',
            'details.required' => 'Minimal harus ada 1 item barang pada tagihan.',
            'details.min' => 'Minimal harus ada 1 item barang pada tagihan.',
        ]);

        $details = $validated['details'];
        unset($validated['details']);

        PurchaseInvoice::createWithDetails($validated, $details);

        return redirect()->route('admin.purchase.invoices.index')->with('success', 'Tagihan pembelian berhasil dibuat.');
    }

    public function show($id)
    {
        $item = PurchaseInvoice::with(['details.barang', 'vendor', 'pengguna', 'goodsReceipt'])->findOrFail($id);

        return view('admin.purchase.invoices.show', ['item' => $item]);
    }

    public function edit($id)
    {
        $item = PurchaseInvoice::with('details')->findOrFail($id);

        return view('admin.purchase.invoices.edit', [
            'item'         => $item,
            'barangList'   => Barang::where('status', 1)->orderBy('nama_barang')->get(),
            'vendorList'   => Vendor::where('status', 1)->orderBy('nama_vendor')->get(),
            'penggunaList' => Pengguna::jabatan(['purchasing', 'admin'])->orderBy('nama_lengkap')->get(),
            'receiptList'  => GoodsReceipt::with('purchaseOrder.vendor')->orderBy('no_penerimaan')->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $item = PurchaseInvoice::findOrFail($id);

        $validated = $request->validate(array_merge([
            'tanggal'       => 'required|date',
            'no_penerimaan' => 'nullable|string|exists:nota_penerimaan_barang,no_penerimaan',
            'id_vendor'     => 'nullable|integer|exists:m_vendor,id_vendor',
            'id_pengguna'   => 'nullable|integer|exists:m_pengguna,id_pengguna',
            'jatuh_tempo'   => 'required|date',
            'keterangan'    => 'nullable|string',
            'status'        => 'required|integer',
        ], $this->detailRules()));

        $details = $validated['details'];
        unset($validated['details']);

        $item->update($validated);
        $item->syncDetails($details);

        return redirect()->route('admin.purchase.invoices.index')->with('success', 'Tagihan pembelian berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = PurchaseInvoice::findOrFail($id);

        DB::transaction(function () use ($item) {
            $item->details()->delete();
            $item->delete();
        });

        return redirect()->route('admin.purchase.invoices.index')->with('success', 'Data berhasil dihapus.');
    }

    public function getInvoiceAmountJson($id)
    {
        $invoice = PurchaseInvoice::with('details')->findOrFail($id);

        $totalTagihan = $invoice->details->sum(function ($d) {
            return ($d->kuantitas * $d->harga_unit) - ($d->diskon ?? 0);
        });

        $sudahDibayar = \App\Models\Purchase\PurchasePaymentDetail::where('no_invoice_beli', $invoice->no_invoice_beli)
            ->sum('jumlah_dialokasikan');

        $sisaTagihan = max(0, $totalTagihan - $sudahDibayar);

        return response()->json([
            'no_invoice_beli' => $invoice->no_invoice_beli,
            'total_tagihan'   => $totalTagihan,
            'sudah_dibayar'   => $sudahDibayar,
            'sisa_tagihan'    => $sisaTagihan > 0 ? $sisaTagihan : $totalTagihan,
        ]);
    }
}
