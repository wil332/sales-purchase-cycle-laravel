<?php

namespace App\Http\Controllers\Admin\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Purchase\GoodsReceipt;
use App\Models\Purchase\PurchaseOrder;
use App\Models\Master\Barang;
use App\Models\Master\Pengguna;
use Illuminate\Http\Request;

class GoodsReceiptController extends Controller
{
    public function index()
    {
        $items = GoodsReceipt::latest('no_penerimaan')->paginate(15);
        return view('admin.purchase.receipts.index', ['items' => $items]);
    }

    public function create()
    {
        $nextNo = \App\Services\CodeGenerator::generateGoodsReceiptNo();
        $barangList = Barang::where('status', 1)->orderBy('nama_barang')->get();
        $penggunaList = Pengguna::jabatan('gudang')->orderBy('nama_lengkap')->get();
        $orderList = PurchaseOrder::with('vendor')->whereIn('status', [1, 2])->orderBy('no_order_pembelian')->get();
        return view('admin.purchase.receipts.create', [
            'nextNo' => $nextNo,
            'barangList' => $barangList,
            'penggunaList' => $penggunaList,
            'orderList' => $orderList,
            'prefillHeader' => [
                'no_penerimaan' => $nextNo,
            ],
            'prefillItems' => [],
        ]);
    }

    // Fitur lanjutan: buat Penerimaan Barang dari Purchase Order
    public function createFromOrder($no_order_pembelian)
    {
        $nextNo = \App\Services\CodeGenerator::generateGoodsReceiptNo();
        $order = PurchaseOrder::with('details')->findOrFail($no_order_pembelian);

        if ((int) $order->status === 3 || (int) $order->status === 0) {
            return redirect()->route('admin.purchase.receipts.index')
                ->with('error', 'Purchase Order ' . $order->no_order_pembelian . ' sudah selesai/batal dan tidak dapat dibuatkan penerimaan barang lagi.');
        }

        $barangList = Barang::where('status', 1)->orderBy('nama_barang')->get();
        $penggunaList = Pengguna::jabatan('gudang')->orderBy('nama_lengkap')->get();
        $orderList = PurchaseOrder::with('vendor')->whereIn('status', [1, 2])->orderBy('no_order_pembelian')->get();

        $prefillHeader = [
            'no_penerimaan' => $nextNo,
            'no_order_pembelian' => $order->no_order_pembelian,
            'keterangan' => 'Dari Purchase Order No. ' . $order->no_order_pembelian,
        ];

        $prefillItems = $order->details->map(function ($d) {
            return [
                'sku' => $d->sku,
                'kuantitas' => $d->kuantitas,
                'kondisi_barang' => 'baik',
                'keterangan' => $d->keterangan,
            ];
        })->toArray();

        return view('admin.purchase.receipts.create', compact('nextNo', 'barangList', 'prefillHeader', 'prefillItems','penggunaList','orderList'));
    }

    public function store(Request $request)
    {
        if (empty($request->no_penerimaan)) {
            $request->merge(['no_penerimaan' => \App\Services\CodeGenerator::generateGoodsReceiptNo()]);
        }

        $validated = $request->validate([
            'no_penerimaan'                 => 'required|string|max:50|unique:nota_penerimaan_barang,no_penerimaan',
            'tanggal_penerimaan'             => 'required|date',
            'no_order_pembelian'             => 'nullable|string|exists:nota_order_pembelian,no_order_pembelian',
            'id_pengguna'     => 'nullable|integer|exists:m_pengguna,id_pengguna',
            'keterangan'                     => 'nullable|string',
            'status'                         => 'required|integer',
            'items'                          => 'required|array|min:1',
            'items.*.sku'                    => 'required|string|exists:m_barang,sku',
            'items.*.kuantitas'              => 'required|integer|min:1',
            'items.*.kondisi_barang'         => 'required|in:baik,rusak',
            'items.*.keterangan'             => 'nullable|string',
        ]);

        if (!empty($validated['no_order_pembelian'])) {
            $validSkus = \App\Models\Purchase\PurchaseOrderDetail::where('no_order_pembelian', $validated['no_order_pembelian'])
                ->pluck('sku')->toArray();

            foreach ($validated['items'] as $detailRow) {
                if (!in_array($detailRow['sku'], $validSkus)) {
                    return back()->withErrors(['items' => 'Barang ' . $detailRow['sku'] . ' tidak terdaftar di PO yang dipilih.'])->withInput();
                }
            }
        }

        $headerData = collect($validated)->except('items')->all();
        $receipt = GoodsReceipt::createWithDetails($headerData, $validated['items']);

        if (!empty($receipt->no_order_pembelian)) {
            $order = PurchaseOrder::find($receipt->no_order_pembelian);
            if ($order) $order->updateOrderStatus();
        }

        return redirect()->route('admin.purchase.receipts.index')->with('success', 'Penerimaan barang berhasil dicatat.');
    }

    public function show($id)
    {
        $item = GoodsReceipt::with('details.barang')->findOrFail($id);
        return view('admin.purchase.receipts.show', compact('item'));
    }

    public function edit($id)
    {
        $item = GoodsReceipt::with('details')->findOrFail($id);
        $currentPoNo = $item->no_order_pembelian;

        $barangList = Barang::where('status', 1)->orderBy('nama_barang')->get();
        $penggunaList = Pengguna::jabatan('gudang')->orderBy('nama_lengkap')->get();

        $orderList = PurchaseOrder::with('vendor')->where(function ($q) use ($currentPoNo) {
            $q->whereIn('status', [1, 2]);
            if ($currentPoNo) {
                $q->orWhere('no_order_pembelian', $currentPoNo);
            }
        })->orderBy('no_order_pembelian')->get();

        return view('admin.purchase.receipts.edit', compact('item', 'barangList', 'penggunaList','orderList'));
    }

    public function update(Request $request, $id)
    {
        $item = GoodsReceipt::findOrFail($id);
        $oldPoNo = $item->no_order_pembelian;

        $validated = $request->validate([
            'tanggal_penerimaan'             => 'required|date',
            'no_order_pembelian'             => 'nullable|string|exists:nota_order_pembelian,no_order_pembelian',
            'id_pengguna' => 'nullable|integer|exists:m_pengguna,id_pengguna',
            'keterangan'                     => 'nullable|string',
            'status'                         => 'required|integer',
            'items'                          => 'required|array|min:1',
            'items.*.sku'                    => 'required|string|exists:m_barang,sku',
            'items.*.kuantitas'              => 'required|integer|min:1',
            'items.*.kondisi_barang'         => 'required|in:baik,rusak',
            'items.*.keterangan'             => 'nullable|string',
        ]);

        if (!empty($validated['no_order_pembelian'])) {
            $validSkus = \App\Models\Purchase\PurchaseOrderDetail::where('no_order_pembelian', $validated['no_order_pembelian'])
                ->pluck('sku')->toArray();

            foreach ($validated['items'] as $detailRow) {
                if (!in_array($detailRow['sku'], $validSkus)) {
                    return back()->withErrors(['items' => 'Barang ' . $detailRow['sku'] . ' tidak terdaftar di PO yang dipilih.'])->withInput();
                }
            }
        }

        $headerData = collect($validated)->except('items')->all();
        $item->update($headerData);
        $item->syncDetails($validated['items']);

        if ($oldPoNo) {
            $oldOrder = PurchaseOrder::find($oldPoNo);
            if ($oldOrder) $oldOrder->updateOrderStatus();
        }
        if (!empty($item->no_order_pembelian)) {
            $newOrder = PurchaseOrder::find($item->no_order_pembelian);
            if ($newOrder) $newOrder->updateOrderStatus();
        }

        return redirect()->route('admin.purchase.receipts.index')->with('success', 'Penerimaan barang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = GoodsReceipt::findOrFail($id);
        $poNo = $item->no_order_pembelian;

        $item->delete();

        if ($poNo) {
            $order = PurchaseOrder::find($poNo);
            if ($order) $order->updateOrderStatus();
        }

        return redirect()->route('admin.purchase.receipts.index')->with('success', 'Data berhasil dihapus.');
    }

    public function getReceiptItemsJson($id)
    {
        $receipt = GoodsReceipt::with(['details.barang', 'purchaseOrder.details'])->findOrFail($id);

        $poDetails = $receipt->purchaseOrder ? $receipt->purchaseOrder->details->keyBy('sku') : collect();
        $idVendor = $receipt->purchaseOrder ? $receipt->purchaseOrder->id_vendor : null;

        $items = $receipt->details->map(function ($detail) use ($poDetails) {
            $poDetail = $poDetails->get($detail->sku);
            return [
                'sku'         => $detail->sku,
                'nama_barang' => optional($detail->barang)->nama_barang ?? $detail->sku,
                'kuantitas'   => $detail->kuantitas,
                'harga_unit'  => $poDetail ? $poDetail->harga_unit : 0,
                'diskon'      => $poDetail ? ($poDetail->diskon ?? 0) : 0,
                'keterangan'  => $detail->keterangan ?? '',
            ];
        });

        return response()->json([
            'id_vendor'          => $idVendor,
            'no_order_pembelian' => $receipt->no_order_pembelian,
            'items'              => $items,
        ]);
    }
}