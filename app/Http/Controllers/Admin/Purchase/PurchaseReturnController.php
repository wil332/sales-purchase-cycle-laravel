<?php

namespace App\Http\Controllers\Admin\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Purchase\PurchaseReturn;
use App\Models\Purchase\GoodsReceipt;
use App\Models\Master\Barang;
use App\Models\Master\Vendor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PurchaseReturnController extends Controller
{
    protected function detailRules(): array
    {
        return [
            'details'               => 'required|array|min:1',
            'details.*.sku'         => 'required|string|exists:m_barang,sku',
            'details.*.jumlah'      => 'required|integer|min:1',
            'details.*.harga_unit'  => 'required|numeric|min:0',
            'details.*.diskon'      => 'nullable|numeric|min:0',
            'details.*.keterangan'  => 'nullable|string',
        ];
    }

    public function index()
    {
        $items = PurchaseReturn::withCount('details')
            ->with(['vendor', 'goodsReceipt'])
            ->latest('nota_retur')
            ->paginate(15);

        return view('admin.purchase.returns.index', ['items' => $items]);
    }

    public function create()
    {
        $nextNo = \App\Services\CodeGenerator::generatePurchaseReturnNo();
        return view('admin.purchase.returns.create', [
            'nextNo'      => $nextNo,
            'barangList'  => Barang::where('status', 1)->orderBy('nama_barang')->get(),
            'vendorList'  => Vendor::where('status', 1)->orderBy('nama_vendor')->get(),
            'receiptList' => GoodsReceipt::orderBy('no_penerimaan')->get(),
        ]);
    }

    public function store(Request $request)
    {
        if (empty($request->nota_retur)) {
            $request->merge(['nota_retur' => \App\Services\CodeGenerator::generatePurchaseReturnNo()]);
        }
        if (empty($request->nomor_urut)) {
            $request->merge(['nomor_urut' => $request->nota_retur]);
        }

        $validated = $request->validate(array_merge([
            'nota_retur'            => 'required|string|max:50|unique:nota_retur_barang,nota_retur',
            'nomor_urut'            => 'required|string|max:50',
            'atas_penerimaan_nomor' => 'nullable|string|exists:nota_penerimaan_barang,no_penerimaan',
            'tanggal'               => 'required|date',
            'id_penjual'            => 'nullable|integer|exists:m_vendor,id_vendor',
            'keterangan'            => 'nullable|string',
            'status'                => 'required|integer',
        ], $this->detailRules()), [
            'nota_retur.unique' => 'Nomor Nota Retur ":input" sudah digunakan. Silakan gunakan nomor lain atau generate otomatis.',
            'nota_retur.required' => 'Nomor Nota Retur wajib diisi.',
            'details.required' => 'Minimal harus ada 1 item barang yang diretur.',
            'details.min' => 'Minimal harus ada 1 item barang yang diretur.',
        ]);

        $details = $validated['details'];
        unset($validated['details']);

        PurchaseReturn::createWithDetails($validated, $details);

        return redirect()->route('admin.purchase.returns.index')->with('success', 'Retur barang berhasil dicatat.');
    }

    public function show($id)
    {
        $item = PurchaseReturn::with(['details.barang', 'vendor', 'goodsReceipt'])->findOrFail($id);

        return view('admin.purchase.returns.show', ['item' => $item]);
    }

    public function edit($id)
    {
        $item = PurchaseReturn::with('details')->findOrFail($id);

        return view('admin.purchase.returns.edit', [
            'item'        => $item,
            'barangList'  => Barang::where('status', 1)->orderBy('nama_barang')->get(),
            'vendorList'  => Vendor::where('status', 1)->orderBy('nama_vendor')->get(),
            'receiptList' => GoodsReceipt::orderBy('no_penerimaan')->get(),
        ]);
    }

    public function update(Request $request, $id)
    {
        $item = PurchaseReturn::findOrFail($id);

        $validated = $request->validate(array_merge([
            'nomor_urut'            => 'required|string|max:50',
            'atas_penerimaan_nomor' => 'nullable|string|exists:nota_penerimaan_barang,no_penerimaan',
            'tanggal'               => 'required|date',
            'id_penjual'            => 'nullable|integer|exists:m_vendor,id_vendor',
            'keterangan'            => 'nullable|string',
            'status'                => 'required|integer',
        ], $this->detailRules()));

        $details = $validated['details'];
        unset($validated['details']);

        $item->update($validated);
        $item->syncDetails($details);

        return redirect()->route('admin.purchase.returns.index')->with('success', 'Retur barang berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $item = PurchaseReturn::findOrFail($id);

        DB::transaction(function () use ($item) {
            $item->details()->delete();
            $item->delete();
        });

        return redirect()->route('admin.purchase.returns.index')->with('success', 'Data berhasil dihapus.');
    }
}
