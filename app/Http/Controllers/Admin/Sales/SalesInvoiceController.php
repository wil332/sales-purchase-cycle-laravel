<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Controller;
use App\Models\Master\Barang;
use App\Models\Master\Pelanggan;
use App\Models\Sales\SalesInvoice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesInvoiceController extends Controller
{
    protected function detailRules(): array
    {
        return [
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|string|exists:m_barang,sku',
            'items.*.kuantitas' => 'required|integer|min:1',
            'items.*.harga_jual' => 'required|numeric|min:0',
            'items.*.diskon' => 'nullable|numeric|min:0',
            'items.*.keterangan' => 'nullable|string',
        ];
    }

    public function index()
    {
        $items = SalesInvoice::withCount('details')
            ->with(['shipment', 'pelanggan'])
            ->latest('no_invoice_jual')
            ->paginate(15);

        return view('admin.sales.invoices.index', ['items' => $items]);
    }

    public function create()
    {
        $nextNo = \App\Services\CodeGenerator::generateSalesInvoiceNo();
        $barangList = Barang::orderBy('nama_barang')->get(['sku', 'nama_barang']);
        $pelangganList = Pelanggan::where('status', 1)->orderBy('nama_pelanggan')->get();
        $shipmentList = \App\Models\Sales\Shipment::orderBy('no_pengiriman')->get();
        return view('admin.sales.invoices.create', compact('nextNo', 'barangList', 'pelangganList', 'shipmentList'));
    }

    public function store(Request $request)
    {
        if (empty($request->no_invoice_jual)) {
            $request->merge(['no_invoice_jual' => \App\Services\CodeGenerator::generateSalesInvoiceNo()]);
        }

        $validated = $request->validate(array_merge([
            'no_invoice_jual' => 'required|string|max:50|unique:nota_tagihan_penjualan,no_invoice_jual',
            'tanggal' => 'date|required',
            'no_pengiriman' => 'required|string|exists:nota_pengiriman_barang,no_pengiriman',
            'id_pelanggan' => 'required|integer|exists:m_pelanggan,id_pelanggan',
            'jatuh_tempo' => 'date|required',
            'keterangan' => 'nullable|string',
            'status' => 'required|integer',
        ], $this->detailRules()), [
            'no_invoice_jual.unique' => 'Nomor Faktur Penjualan ":input" sudah digunakan. Silakan gunakan nomor lain atau generate otomatis.',
            'no_invoice_jual.required' => 'Nomor Faktur Penjualan wajib diisi.',
            'no_pengiriman.required' => 'Silakan pilih Surat Jalan / Pengiriman Barang terkait.',
            'items.required' => 'Minimal harus ada 1 item barang pada faktur.',
            'items.min' => 'Minimal harus ada 1 item barang pada faktur.',
        ]);

        $items = $validated['items'];
        unset($validated['items']);

        SalesInvoice::createWithDetails($validated, $items);

        return redirect()->route('admin.sales.invoices.index')->with('success', 'Tagihan Penjualan (Invoice) berhasil dibuat.');
    }

    public function show($id)
    {
        $item = SalesInvoice::with(['details.barang', 'shipment', 'pelanggan'])->findOrFail($id);

        return view('admin.sales.invoices.show', ['item' => $item]);
    }

    public function edit($id)
{
    $item = SalesInvoice::with('details')->findOrFail($id);

    $barangList = Barang::orderBy('nama_barang')
        ->get(['sku', 'nama_barang']);

    $pelangganList = Pelanggan::where('status', 1)
        ->orderBy('nama_pelanggan')
        ->get();

    return view('admin.sales.invoices.edit', [
        'item' => $item,
        'barangList' => $barangList,
        'pelangganList' => $pelangganList,
    ]);
}

    public function update(Request $request, $id)
    {
        $item = SalesInvoice::findOrFail($id);

        $validated = $request->validate(array_merge([
            'tanggal' => 'date|required',
            'no_pengiriman' => 'required|string|exists:nota_pengiriman_barang,no_pengiriman',
            'id_pelanggan' => 'required|integer|exists:m_pelanggan,id_pelanggan',
            'jatuh_tempo' => 'date|required',
            'keterangan' => 'nullable|string',
            'status' => 'required|integer',
        ], $this->detailRules()));

        $items = $validated['items'];
        unset($validated['items']);

        $item->update($validated);
        $item->syncDetails($items);

        return redirect()->route('admin.sales.invoices.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = SalesInvoice::findOrFail($id);

        DB::transaction(function () use ($item) {
            $item->details()->delete();
            $item->delete();
        });

        return redirect()->route('admin.sales.invoices.index')->with('success', 'Data berhasil dihapus.');
    }

    public function getInvoiceAmountJson($id)
    {
        $invoice = SalesInvoice::with('details')->findOrFail($id);

        $totalTagihan = $invoice->details->sum(function ($d) {
            return ($d->kuantitas * $d->harga_jual) - ($d->diskon ?? 0);
        });

        $sudahDibayar = \App\Models\Sales\SalesPayment::where('no_invoice_jual', $invoice->no_invoice_jual)
            ->sum('jumlah_bayar');

        $sisaTagihan = max(0, $totalTagihan - $sudahDibayar);

        return response()->json([
            'no_invoice_jual' => $invoice->no_invoice_jual,
            'total_tagihan'   => $totalTagihan,
            'sudah_dibayar'   => $sudahDibayar,
            'sisa_tagihan'    => $sisaTagihan > 0 ? $sisaTagihan : $totalTagihan,
        ]);
    }
}
