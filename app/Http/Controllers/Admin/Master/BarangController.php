<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Barang;
use App\Services\CodeGenerator;
use Illuminate\Http\Request;

class BarangController extends Controller
{
    public function index()
    {
        $items = Barang::with('category')->latest('created_at')->paginate(15);
        return view('admin.master.barang.index', ['items' => $items]);
    }

    public function checkSku(Request $request)
    {
        $sku = trim($request->query('sku', ''));
        $exists = Barang::where('sku', $sku)->exists();
        return response()->json([
            'exists' => $exists,
            'message' => $exists 
                ? "SKU \"{$sku}\" sudah terdaftar di sistem. Gunakan SKU lain atau klik Acak." 
                : "SKU \"{$sku}\" tersedia (belum pernah digunakan)."
        ]);
    }

    public function create()
    {
        $nextSku = CodeGenerator::generateSku();
        $categories = \App\Models\Category::orderBy('name')->get();
        return view('admin.master.barang.create', compact('nextSku', 'categories'));
    }

    public function store(Request $request)
    {
        if (empty($request->sku)) {
            $request->merge(['sku' => CodeGenerator::generateSku()]);
        }

        $validated = $request->validate([
            'sku' => 'required|string|max:50|unique:m_barang,sku',
            'category_id' => 'nullable|integer|exists:categories,id',
            'nama_barang' => 'required|string|max:255',
            'keterangan' => 'string|nullable',
            'status' => 'required|integer',
        ], [
            'sku.unique' => 'Kode SKU ":input" sudah terdaftar di sistem. Silakan gunakan SKU lain atau generate otomatis.',
            'sku.required' => 'Kode SKU wajib diisi.',
            'nama_barang.required' => 'Nama barang wajib diisi.',
        ]);

        Barang::create($validated);

        return redirect()->route('admin.master.barang.index')->with('success', 'Data barang berhasil ditambahkan.');
    }

    public function show($id)
    {
        $item = Barang::with('category')->findOrFail($id);
        return view('admin.master.barang.show', ['item' => $item]);
    }

    public function edit($id)
    {
        $item = Barang::findOrFail($id);
        $categories = \App\Models\Category::orderBy('name')->get();
        return view('admin.master.barang.edit', compact('item', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $item = Barang::findOrFail($id);

        $validated = $request->validate([
            'category_id' => 'nullable|integer|exists:categories,id',
            'nama_barang' => 'required|string|max:255',
            'keterangan' => 'string|nullable',
            'status' => 'required|integer',
        ], [
            'nama_barang.required' => 'Nama barang wajib diisi.',
        ]);

        $item->update($validated);

        return redirect()->route('admin.master.barang.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Barang::findOrFail($id)->delete();

        return redirect()->route('admin.master.barang.index')->with('success', 'Data berhasil dihapus.');
    }
}
