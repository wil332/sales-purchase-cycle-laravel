<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Controller;
use App\Models\Master\Barang;
use App\Models\Master\Pelanggan;
use App\Models\Sales\SalesReturn;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SalesReturnController extends Controller
{
    protected function detailRules(): array
    {
        return [
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|string|exists:m_barang,sku',
            'items.*.jumlah_diretur' => 'required|integer|min:1',
            'items.*.alasan_retur' => 'nullable|string',
        ];
    }

    public function index()
    {
        $items = SalesReturn::withCount('details')
            ->with(['salesInvoice', 'pelanggan'])
            ->latest('no_retur_jual')
            ->paginate(15);

        return view('admin.sales.returns.index', ['items' => $items]);
    }

    public function create()
    {
        $nextNo = \App\Services\CodeGenerator::generateSalesReturnNo();
        $barangList = Barang::orderBy('nama_barang')->get(['sku', 'nama_barang']);
        $pelangganList = Pelanggan::where('status', 1)->orderBy('nama_pelanggan')->get();
        $invoiceList = \App\Models\Sales\SalesInvoice::orderBy('no_invoice_jual')->get();
        return view('admin.sales.returns.create', compact('nextNo', 'barangList', 'pelangganList', 'invoiceList'));
    }

    public function store(Request $request)
    {
        if (empty($request->no_retur_jual)) {
            $request->merge(['no_retur_jual' => \App\Services\CodeGenerator::generateSalesReturnNo()]);
        }

        $validated = $request->validate(array_merge([
            'no_retur_jual' => 'required|string|max:50|unique:nota_retur_penjualan,no_retur_jual',
            'tanggal' => 'date|required',
            'no_invoice_jual' => 'required|string|exists:nota_tagihan_penjualan,no_invoice_jual',
            'id_pelanggan' => 'required|integer|exists:m_pelanggan,id_pelanggan',
            'keterangan' => 'nullable|string',
            'status' => 'required|integer',
        ], $this->detailRules()), [
            'no_retur_jual.unique' => 'Nomor Retur Penjualan ":input" sudah digunakan. Silakan gunakan nomor lain atau generate otomatis.',
            'no_retur_jual.required' => 'Nomor Retur Penjualan wajib diisi.',
            'items.required' => 'Minimal harus ada 1 item barang yang diretur.',
            'items.min' => 'Minimal harus ada 1 item barang yang diretur.',
        ]);

        $items = $validated['items'];
        unset($validated['items']);

        SalesReturn::createWithDetails($validated, $items);

        return redirect()->route('admin.sales.returns.index')->with('success', 'Nota Retur Penjualan berhasil dibuat.');
    }

    public function show($id)
    {
        $item = SalesReturn::with(['details.barang', 'salesInvoice', 'pelanggan'])->findOrFail($id);

        return view('admin.sales.returns.show', ['item' => $item]);
    }

    public function edit($id)
    {
        $item = SalesReturn::with('details')->findOrFail($id);
        $barangList = Barang::orderBy('nama_barang')->get(['sku', 'nama_barang']);

        return view('admin.sales.returns.edit', ['item' => $item, 'barangList' => $barangList]);
    }

    public function update(Request $request, $id)
    {
        $item = SalesReturn::findOrFail($id);

        $validated = $request->validate(array_merge([
            'tanggal' => 'date|required',
            'no_invoice_jual' => 'required|string|exists:nota_tagihan_penjualan,no_invoice_jual',
            'id_pelanggan' => 'required|integer|exists:m_pelanggan,id_pelanggan',
            'keterangan' => 'nullable|string',
            'status' => 'required|integer',
        ], $this->detailRules()));

        $items = $validated['items'];
        unset($validated['items']);

        $item->update($validated);
        $item->syncDetails($items);

        return redirect()->route('admin.sales.returns.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = SalesReturn::findOrFail($id);

        DB::transaction(function () use ($item) {
            $item->details()->delete();
            $item->delete();
        });

        return redirect()->route('admin.sales.returns.index')->with('success', 'Data berhasil dihapus.');
    }
}
