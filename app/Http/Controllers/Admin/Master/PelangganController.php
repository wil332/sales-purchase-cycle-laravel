<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Pelanggan;
use Illuminate\Http\Request;

class PelangganController extends Controller
{
    public function index()
    {
        $items = Pelanggan::latest('id_pelanggan')->paginate(15);
        return view('admin.master.pelanggan.index', ['items' => $items]);
    }

    public function create()
    {
        return view('admin.master.pelanggan.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'no_telp' => 'string|max:20|nullable',
            'alamat' => 'string|nullable',
            'keterangan' => 'string|nullable',
            'status' => 'required|integer',
        ]);

        Pelanggan::create($validated);

        return redirect()->route('admin.master.pelanggan.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function show($id)
    {
        $item = Pelanggan::findOrFail($id);
        return view('admin.master.pelanggan.show', ['item' => $item]);
    }

    public function edit($id)
    {
        $item = Pelanggan::findOrFail($id);
        return view('admin.master.pelanggan.edit', ['item' => $item]);
    }

    public function update(Request $request, $id)
    {
        $item = Pelanggan::findOrFail($id);

        $validated = $request->validate([
            'nama_pelanggan' => 'required|string|max:255',
            'no_telp' => 'string|max:20|nullable',
            'alamat' => 'string|nullable',
            'keterangan' => 'string|nullable',
            'status' => 'required|integer',
        ]);

        $item->update($validated);

        return redirect()->route('admin.master.pelanggan.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Pelanggan::findOrFail($id)->delete();

        return redirect()->route('admin.master.pelanggan.index')->with('success', 'Data berhasil dihapus.');
    }
}
