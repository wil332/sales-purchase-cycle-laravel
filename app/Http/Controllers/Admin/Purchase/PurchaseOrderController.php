<?php

namespace App\Http\Controllers\Admin\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Purchase\PurchaseOrder;
use App\Models\Purchase\PurchaseRequest;
use App\Models\Master\Barang;
use App\Models\Master\Pengguna;
use App\Models\Master\Vendor;
use Illuminate\Http\Request;

class PurchaseOrderController extends Controller
{
    public function index()
    {
        $items = PurchaseOrder::with(['vendor', 'pengguna'])->latest('no_order_pembelian')->paginate(15);
        return view('admin.purchase.orders.index', ['items' => $items]);
    }

    public function create()
    {
        $nextNo = \App\Services\CodeGenerator::generatePurchaseOrderNo();
        $barangList = Barang::where('status', 1)->orderBy('nama_barang')->get();
        $vendorList = Vendor::where('status', 1)->orderBy('nama_vendor')->get();
        $penggunaList = Pengguna::jabatan('purchasing')->orderBy('nama_lengkap')->get();

        return view('admin.purchase.orders.create', [
            'nextNo' => $nextNo,
            'barangList' => $barangList,
            'vendorList' => $vendorList,
            'penggunaList' => $penggunaList,
            'prefillHeader' => [
                'no_order_pembelian' => $nextNo,
            ],
            'prefillDetails' => [],
        ]);
    }


    public function createFromRequest($no_faktur)
    {
        $nextNo = \App\Services\CodeGenerator::generatePurchaseOrderNo();
        $request = PurchaseRequest::with('details')->findOrFail($no_faktur);

        $barangList = Barang::where('status', 1)->orderBy('nama_barang')->get();
        $vendorList = Vendor::where('status', 1)->orderBy('nama_vendor')->get();
        $penggunaList = Pengguna::jabatan('purchasing')->orderBy('nama_lengkap')->get();


        $prefillHeader = [
            'no_order_pembelian' => $nextNo,
            'tanggal_dibutuhkan' => optional($request->tanggal_diperlukan)->format('Y-m-d'),
            'keterangan' => 'Dibuat dari Permintaan Pembelian No. ' . $request->no_faktur,
        ];

        // Prefill detail: sku, kuantitas, keterangan diwariskan dari detail PR
        // (harga_unit & diskon dikosongkan karena harga baru ditentukan saat negosiasi ke vendor)
        $prefillDetails = $request->details->map(function ($detail) {
            return [
                'sku' => $detail->sku,
                'kuantitas' => $detail->kuantitas,
                'harga_unit' => '',
                'diskon' => 0,
                'keterangan' => $detail->keterangan,
            ];
        })->toArray();

        return view('admin.purchase.orders.create', compact(
            'nextNo', 'barangList', 'vendorList', 'prefillHeader', 'prefillDetails', 'penggunaList'
        ));
    }

    public function store(Request $request)
    {
        if (empty($request->no_order_pembelian)) {
            $request->merge(['no_order_pembelian' => \App\Services\CodeGenerator::generatePurchaseOrderNo()]);
        }

        $validated = $request->validate([
            'no_order_pembelian'          => 'required|string|max:50|unique:nota_order_pembelian,no_order_pembelian',
            'tanggal'                     => 'required|date',
            'id_vendor'                   => 'nullable|numeric',
            'id_pengguna' => 'nullable|integer|exists:m_pengguna,id_pengguna',
            'tanggal_dibutuhkan'          => 'nullable|date',
            'keterangan'                  => 'nullable|string',
            'status'                      => 'required|integer',
            'details'                     => 'required|array|min:1',
            'details.*.sku'               => 'required|string|exists:m_barang,sku',
            'details.*.kuantitas'         => 'required|integer|min:1',
            'details.*.harga_unit'        => 'required|numeric|min:0',
            'details.*.diskon'            => 'nullable|numeric|min:0',
            'details.*.keterangan'        => 'nullable|string',
        ], [
            'no_order_pembelian.unique' => 'Nomor PO ":input" sudah digunakan. Silakan gunakan nomor lain atau generate otomatis.',
            'no_order_pembelian.required' => 'Nomor Order Pembelian wajib diisi.',
            'details.required' => 'Minimal harus ada 1 item barang yang dipesan.',
            'details.min' => 'Minimal harus ada 1 item barang yang dipesan.',
        ]);

        $headerData = collect($validated)->except('details')->all();
        $detailRows = $validated['details'];

        PurchaseOrder::createWithDetails($headerData, $detailRows);

        return redirect()->route('admin.purchase.orders.index')->with('success', 'Purchase Order berhasil dibuat.');
    }

    public function show($id)
    {
        $item = PurchaseOrder::with(['details.barang', 'vendor'])->findOrFail($id);
        return view('admin.purchase.orders.show', compact('item'));
    }

    public function edit($id)
    {
        $item = PurchaseOrder::with('details')->findOrFail($id);
        $barangList = Barang::where('status', 1)->orderBy('nama_barang')->get();
        $vendorList = Vendor::where('status', 1)->orderBy('nama_vendor')->get();
        $penggunaList = Pengguna::jabatan('purchasing')->orderBy('nama_lengkap')->get();

        return view('admin.purchase.orders.edit', compact('item', 'barangList', 'vendorList', 'penggunaList'));
    }

    public function update(Request $request, $id)
    {
        $item = PurchaseOrder::findOrFail($id);

        $validated = $request->validate([
            'tanggal'                     => 'required|date',
            'id_vendor'                   => 'nullable|numeric',
            'id_pengguna' => 'nullable|integer|exists:m_pengguna,id_pengguna',
            'tanggal_dibutuhkan'          => 'nullable|date',
            'keterangan'                  => 'nullable|string',
            'status'                      => 'required|integer',
            'details'                     => 'required|array|min:1',
            'details.*.sku'               => 'required|string|exists:m_barang,sku',
            'details.*.kuantitas'         => 'required|integer|min:1',
            'details.*.harga_unit'        => 'required|numeric|min:0',
            'details.*.diskon'            => 'nullable|numeric|min:0',
            'details.*.keterangan'        => 'nullable|string',
        ]);

        $headerData = collect($validated)->except('details')->all();
        $detailRows = $validated['details'];

        $item->update($headerData);
        $item->syncDetails($detailRows);

        return redirect()->route('admin.purchase.orders.index')->with('success', 'Purchase Order berhasil diperbarui.');
    }

    public function destroy($id)
    {
        PurchaseOrder::findOrFail($id)->delete();
        return redirect()->route('admin.purchase.orders.index')->with('success', 'Purchase Order berhasil dihapus.');
    }

    public function getOrderItemsJson($id)
    {
        $order = PurchaseOrder::with(['details.barang'])->findOrFail($id);

        $items = $order->details->map(function ($detail) use ($order) {
            $totalDiterima = \App\Models\Purchase\GoodsReceiptDetail::whereHas('goodsReceipt', function ($q) use ($order) {
                $q->where('no_order_pembelian', $order->no_order_pembelian)->where('status', 1);
            })->where('sku', $detail->sku)->sum('kuantitas');

            $sisaQty = max(0, $detail->kuantitas - $totalDiterima);

            return [
                'sku'            => $detail->sku,
                'nama_barang'    => optional($detail->barang)->nama_barang ?? $detail->sku,
                'kuantitas'      => $detail->kuantitas,
                'kuantitas_sisa' => $sisaQty > 0 ? $sisaQty : $detail->kuantitas,
                'harga_unit'     => $detail->harga_unit,
                'diskon'         => $detail->diskon ?? 0,
                'keterangan'     => $detail->keterangan ?? '',
            ];
        });

        return response()->json([
            'id_vendor' => $order->id_vendor,
            'items'     => $items,
        ]);
    }
}