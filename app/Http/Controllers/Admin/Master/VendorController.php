<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Vendor;
use App\Models\Master\Barang;
use Illuminate\Http\Request;

class VendorController extends Controller
{
    public function index()
    {
        $items = Vendor::withCount('barangs')->latest('id_vendor')->paginate(15);
        return view('admin.master.vendor.index', ['items' => $items]);
    }

    public function create()
    {
        $barangList = Barang::where('status', 1)->orderBy('nama_barang')->get();
        return view('admin.master.vendor.create', compact('barangList'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'no_telp' => 'string|max:20|nullable',
            'alamat' => 'string|nullable',
            'keterangan' => 'string|nullable',
            'status' => 'required|integer',
            'barang_skus' => 'nullable|array',
            'barang_skus.*' => 'string|exists:m_barang,sku',
        ]);

        $vendor = Vendor::create($validated);

        if ($request->has('barang_skus')) {
            $vendor->barangs()->sync($request->input('barang_skus', []));
        }

        return redirect()->route('admin.master.vendor.index')->with('success', 'Data vendor berhasil ditambahkan.');
    }

    public function show($id)
    {
        $item = Vendor::with('barangs')->findOrFail($id);
        return view('admin.master.vendor.show', ['item' => $item]);
    }

    public function edit($id)
    {
        $item = Vendor::with('barangs')->findOrFail($id);
        $barangList = Barang::where('status', 1)->orderBy('nama_barang')->get();
        $selectedSkus = $item->barangs->pluck('sku')->toArray();
        return view('admin.master.vendor.edit', compact('item', 'barangList', 'selectedSkus'));
    }

    public function update(Request $request, $id)
    {
        $item = Vendor::findOrFail($id);

        $validated = $request->validate([
            'nama_vendor' => 'required|string|max:255',
            'no_telp' => 'string|max:20|nullable',
            'alamat' => 'string|nullable',
            'keterangan' => 'string|nullable',
            'status' => 'required|integer',
            'barang_skus' => 'nullable|array',
            'barang_skus.*' => 'string|exists:m_barang,sku',
        ]);

        $item->update($validated);
        $item->barangs()->sync($request->input('barang_skus', []));

        return redirect()->route('admin.master.vendor.index')->with('success', 'Data vendor berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Vendor::findOrFail($id)->delete();

        return redirect()->route('admin.master.vendor.index')->with('success', 'Data berhasil dihapus.');
    }

    /**
     * AJAX endpoint to get products sold by a specific vendor.
     */
    public function getBarangJson($id)
    {
        $vendor = Vendor::with(['barangs' => function ($q) {
            $q->where('status', 1)->orderBy('nama_barang');
        }])->findOrFail($id);

        $items = $vendor->barangs->map(function ($b) {
            return [
                'sku' => $b->sku,
                'nama_barang' => $b->nama_barang,
                'harga_beli' => $b->pivot->harga_beli ?? null,
            ];
        });

        return response()->json($items);
    }
}
