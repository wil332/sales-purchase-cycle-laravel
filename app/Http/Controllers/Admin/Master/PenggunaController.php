<?php

namespace App\Http\Controllers\Admin\Master;

use App\Http\Controllers\Controller;
use App\Models\Master\Pengguna;
use Illuminate\Http\Request;

class PenggunaController extends Controller
{
    public function index()
    {
        $items = Pengguna::latest('id_pengguna')->paginate(15);
        return view('admin.master.pengguna.index', ['items' => $items]);
    }

    public function create()
    {
        return view('admin.master.pengguna.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:m_pengguna,username',
            'password' => 'required|string|min:6',
            'jabatan' => 'required|in:gudang,purchasing,sales,manajer,admin',
            'keterangan' => 'string|nullable',
            'status' => 'required|integer',
        ]);

        // Password is hashed automatically by the Pengguna model's mutator.
        Pengguna::create($validated);

        return redirect()->route('admin.master.pengguna.index')->with('success', 'Data berhasil ditambahkan.');
    }

    public function show($id)
    {
        $item = Pengguna::findOrFail($id);
        return view('admin.master.pengguna.show', ['item' => $item]);
    }

    public function edit($id)
    {
        $item = Pengguna::findOrFail($id);
        return view('admin.master.pengguna.edit', ['item' => $item]);
    }

    public function update(Request $request, $id)
    {
        $item = Pengguna::findOrFail($id);

        $validated = $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'username' => 'required|string|max:50|unique:m_pengguna,username,' . $item->id_pengguna . ',id_pengguna',
            'password' => 'nullable|string|min:6',
            'jabatan' => 'required|in:gudang,purchasing,sales,manajer,admin',
            'keterangan' => 'string|nullable',
            'status' => 'required|integer',
        ]);

        // Leave the existing password untouched if the field was left blank.
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $item->update($validated);

        return redirect()->route('admin.master.pengguna.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {
        Pengguna::findOrFail($id)->delete();

        return redirect()->route('admin.master.pengguna.index')->with('success', 'Data berhasil dihapus.');
    }
}
