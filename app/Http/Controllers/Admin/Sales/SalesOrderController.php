<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Controller;
use App\Models\Master\Barang;
use App\Models\Sales\SalesOrder;
use Illuminate\Http\Request;
use App\Models\Master\Pelanggan;
use App\Models\Master\Pengguna;
use Illuminate\Support\Facades\DB;

class SalesOrderController extends Controller
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
        $items = SalesOrder::withCount('details')
            ->with(['pelanggan', 'pengguna'])
            ->latest('no_order_penjualan')
            ->paginate(15);

        return view('admin.sales.orders.index', ['items' => $items]);
    }

    public function create()
    {
        $nextNo = \App\Services\CodeGenerator::generateSalesOrderNo();
        $barangList = Barang::orderBy('nama_barang')->get(['sku', 'nama_barang']);
        $pelangganList = Pelanggan::where('status', 1)->orderBy('nama_pelanggan')->get();
        $penggunaList = Pengguna::jabatan('sales')->orderBy('nama_lengkap')->get();

        return view('admin.sales.orders.create', compact('nextNo', 'barangList', 'pelangganList', 'penggunaList'));
    }

    public function store(Request $request)
    {
        if (empty($request->no_order_penjualan)) {
            $request->merge(['no_order_penjualan' => \App\Services\CodeGenerator::generateSalesOrderNo()]);
        }

        $validated = $request->validate(array_merge([
            'no_order_penjualan' => 'required|string|max:50|unique:nota_order_penjualan,no_order_penjualan',
            'tanggal' => 'date|required',
            'id_pelanggan' => 'required|integer|exists:m_pelanggan,id_pelanggan',
            'id_pengguna' => 'nullable|integer|exists:m_pengguna,id_pengguna',
            'keterangan' => 'nullable|string',
            'status' => 'required|integer',
        ], $this->detailRules()), [
            'no_order_penjualan.unique' => 'Nomor Order Penjualan ":input" sudah digunakan. Silakan gunakan nomor lain atau generate otomatis.',
            'no_order_penjualan.required' => 'Nomor Order Penjualan wajib diisi.',
            'id_pelanggan.required' => 'Silakan pilih pelanggan terlebih dahulu.',
            'items.required' => 'Minimal harus ada 1 item barang yang dipesan.',
            'items.min' => 'Minimal harus ada 1 item barang yang dipesan.',
        ]);

        $items = $validated['items'];
        unset($validated['items']);

        SalesOrder::createWithDetails($validated, $items);

        return redirect()->route('admin.sales.orders.index')->with('success', 'Sales Order berhasil dibuat.');
    }

    public function show($id)
    {
        $item = SalesOrder::with(['details.barang', 'pelanggan', 'pengguna'])->findOrFail($id);

        return view('admin.sales.orders.show', ['item' => $item]);
    }

    public function edit($id)
    {
        $item = SalesOrder::with('details')->findOrFail($id);
        $barangList = Barang::orderBy('nama_barang')->get(['sku', 'nama_barang']);
        $pelangganList = Pelanggan::where('status', 1)->orderBy('nama_pelanggan')->get();
        $penggunaList = Pengguna::jabatan('sales')->orderBy('nama_lengkap')->get();

        return view('admin.sales.orders.edit', compact('item', 'barangList', 'pelangganList', 'penggunaList'));
    }

    public function update(Request $request, $id)
    {
        $item = SalesOrder::findOrFail($id);

        $validated = $request->validate(array_merge([
            'tanggal' => 'date|required',
            'id_pelanggan' => 'required|integer|exists:m_pelanggan,id_pelanggan',
            'id_pengguna' => 'nullable|integer|exists:m_pengguna,id_pengguna',
            'keterangan' => 'nullable|string',
            'status' => 'required|integer',
        ], $this->detailRules()));

        $items = $validated['items'];
        unset($validated['items']);

        $item->update($validated);
        $item->syncDetails($items);

        return redirect()->route('admin.sales.orders.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = SalesOrder::findOrFail($id);

        DB::transaction(function () use ($item) {
            $item->details()->delete();
            $item->delete();
        });

        return redirect()->route('admin.sales.orders.index')->with('success', 'Data berhasil dihapus.');
    }

    public function getOrderItemsJson($id)
    {
        $order = SalesOrder::with(['details.barang'])->findOrFail($id);

        $items = $order->details->map(function ($detail) use ($order) {
            $totalDikirim = \App\Models\Sales\ShipmentDetail::whereHas('shipment', function ($q) use ($order) {
                $q->where('no_order_penjualan', $order->no_order_penjualan);
            })->where('sku', $detail->sku)->sum('kuantitas_dikirim');

            $sisaQty = max(0, $detail->kuantitas - $totalDikirim);

            return [
                'sku'            => $detail->sku,
                'nama_barang'    => optional($detail->barang)->nama_barang ?? $detail->sku,
                'kuantitas'      => $detail->kuantitas,
                'kuantitas_sisa' => $sisaQty > 0 ? $sisaQty : $detail->kuantitas,
                'harga_jual'     => $detail->harga_jual,
                'diskon'         => $detail->diskon ?? 0,
                'keterangan'     => $detail->keterangan ?? '',
            ];
        });

        return response()->json([
            'id_pelanggan' => $order->id_pelanggan,
            'items'        => $items,
        ]);
    }
}
