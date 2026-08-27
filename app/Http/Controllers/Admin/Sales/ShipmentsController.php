<?php

namespace App\Http\Controllers\Admin\Sales;

use App\Http\Controllers\Controller;
use App\Models\Master\Barang;
use App\Models\Master\Pengguna;
use App\Models\Sales\Shipment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ShipmentsController extends Controller
{
    protected function detailRules(): array
    {
        return [
            'items' => 'required|array|min:1',
            'items.*.sku' => 'required|string|exists:m_barang,sku',
            'items.*.kuantitas_dikirim' => 'required|integer|min:1',
            'items.*.keterangan' => 'nullable|string',
        ];
    }

    public function index()
    {
        $items = Shipment::withCount('details')
            ->with(['salesOrder', 'pengguna'])
            ->latest('no_pengiriman')
            ->paginate(15);

        return view('admin.sales.shipments.index', ['items' => $items]);
    }

    public function create()
    {
        $nextNo = \App\Services\CodeGenerator::generateShipmentNo();
        $barangList = Barang::orderBy('nama_barang')->get(['sku', 'nama_barang']);
        $penggunaList = Pengguna::jabatan('gudang')->orderBy('nama_lengkap')->get();
        $salesOrderList = \App\Models\Sales\SalesOrder::whereIn('status', [1, 2])->orderBy('no_order_penjualan')->get();
        return view('admin.sales.shipments.create', compact('nextNo', 'barangList', 'penggunaList', 'salesOrderList'));
    }

    public function store(Request $request)
    {
        if (empty($request->no_pengiriman)) {
            $request->merge(['no_pengiriman' => \App\Services\CodeGenerator::generateShipmentNo()]);
        }

        $validated = $request->validate(array_merge([
            'no_pengiriman' => 'required|string|max:50|unique:nota_pengiriman_barang,no_pengiriman',
            'tanggal_kirim' => 'date|required',
            'no_order_penjualan' => 'required|string|exists:nota_order_penjualan,no_order_penjualan',
            'id_pengguna' => 'nullable|integer|exists:m_pengguna,id_pengguna',
            'keterangan' => 'nullable|string',
            'status' => 'required|integer',
        ], $this->detailRules()), [
            'no_pengiriman.unique' => 'Nomor Pengiriman ":input" sudah digunakan. Silakan gunakan nomor lain atau generate otomatis.',
            'no_pengiriman.required' => 'Nomor Pengiriman wajib diisi.',
            'no_order_penjualan.required' => 'Silakan pilih Sales Order yang akan dikirim.',
            'items.required' => 'Minimal harus ada 1 item barang yang dikirim.',
            'items.min' => 'Minimal harus ada 1 item barang yang dikirim.',
        ]);

        $items = $validated['items'];
        unset($validated['items']);

        $shipment = Shipment::createWithDetails($validated, $items);

        if (!empty($shipment->no_order_penjualan)) {
            $order = \App\Models\Sales\SalesOrder::find($shipment->no_order_penjualan);
            if ($order) $order->updateOrderStatus();
        }

        return redirect()->route('admin.sales.shipments.index')->with('success', 'Surat Jalan / Pengiriman Barang berhasil dibuat.');
    }

    public function show($id)
    {
        $item = Shipment::with(['details.barang', 'salesOrder', 'pengguna'])->findOrFail($id);

        return view('admin.sales.shipments.show', ['item' => $item]);
    }

    public function edit($id)
    {
        $item = Shipment::with('details')->findOrFail($id);
        $currentSoNo = $item->no_order_penjualan;

        $barangList = Barang::orderBy('nama_barang')->get(['sku', 'nama_barang']);
        $penggunaList = Pengguna::jabatan('gudang')->orderBy('nama_lengkap')->get();

        $salesOrderList = \App\Models\Sales\SalesOrder::where(function ($q) use ($currentSoNo) {
            $q->whereIn('status', [1, 2]);
            if ($currentSoNo) {
                $q->orWhere('no_order_penjualan', $currentSoNo);
            }
        })->orderBy('no_order_penjualan')->get();

        return view('admin.sales.shipments.edit', compact('item', 'barangList', 'penggunaList', 'salesOrderList'));
    }

    public function update(Request $request, $id)
    {
        $item = Shipment::findOrFail($id);
        $oldSoNo = $item->no_order_penjualan;

        $validated = $request->validate(array_merge([
            'tanggal_kirim' => 'date|required',
            'no_order_penjualan' => 'required|string|exists:nota_order_penjualan,no_order_penjualan',
            'id_pengguna' => 'nullable|integer|exists:m_pengguna,id_pengguna',
            'keterangan' => 'nullable|string',
            'status' => 'required|integer',
        ], $this->detailRules()));

        $items = $validated['items'];
        unset($validated['items']);

        $item->update($validated);
        $item->syncDetails($items);

        if ($oldSoNo) {
            $oldOrder = \App\Models\Sales\SalesOrder::find($oldSoNo);
            if ($oldOrder) $oldOrder->updateOrderStatus();
        }
        if (!empty($item->no_order_penjualan)) {
            $newOrder = \App\Models\Sales\SalesOrder::find($item->no_order_penjualan);
            if ($newOrder) $newOrder->updateOrderStatus();
        }

        return redirect()->route('admin.sales.shipments.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = Shipment::findOrFail($id);
        $soNo = $item->no_order_penjualan;

        DB::transaction(function () use ($item) {
            $item->details()->delete();
            $item->delete();
        });

        if ($soNo) {
            $order = \App\Models\Sales\SalesOrder::find($soNo);
            if ($order) $order->updateOrderStatus();
        }

        return redirect()->route('admin.sales.shipments.index')->with('success', 'Data berhasil dihapus.');
    }

    public function getShipmentItemsJson($id)
    {
        $shipment = Shipment::with(['details.barang', 'salesOrder.details'])->findOrFail($id);

        $soDetails = $shipment->salesOrder ? $shipment->salesOrder->details->keyBy('sku') : collect();
        $idPelanggan = $shipment->salesOrder ? $shipment->salesOrder->id_pelanggan : null;

        $items = $shipment->details->map(function ($detail) use ($soDetails) {
            $soDetail = $soDetails->get($detail->sku);
            return [
                'sku'         => $detail->sku,
                'nama_barang' => optional($detail->barang)->nama_barang ?? $detail->sku,
                'kuantitas'   => $detail->kuantitas_dikirim,
                'harga_jual'  => $soDetail ? $soDetail->harga_jual : 0,
                'diskon'      => $soDetail ? ($soDetail->diskon ?? 0) : 0,
                'keterangan'  => $detail->keterangan ?? '',
            ];
        });

        return response()->json([
            'no_order_penjualan' => $shipment->no_order_penjualan,
            'id_pelanggan'       => $idPelanggan,
            'items'              => $items,
        ]);
    }
}
