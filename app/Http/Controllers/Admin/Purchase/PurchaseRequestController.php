<?php

namespace App\Http\Controllers\Admin\Purchase;

use App\Http\Controllers\Controller;
use App\Models\Purchase\PurchaseRequest;
use App\Models\Master\Barang;
use App\Models\Master\Pengguna;
use Illuminate\Http\Request;

class PurchaseRequestController extends Controller
{
    public function index()
    {
        $items = PurchaseRequest::latest('no_faktur')->paginate(15);
        return view('admin.purchase.requests.index', ['items' => $items]);
    }

    public function create()
    {
        $nextNo = \App\Services\CodeGenerator::generatePurchaseRequestNo();
        $barangList = Barang::where('status', 1)->orderBy('nama_barang')->get();
        $penggunaList = Pengguna::jabatan(['gudang', 'purchasing'])->orderBy('nama_lengkap')->get();
        return view('admin.purchase.requests.create', compact('nextNo', 'barangList', 'penggunaList'));
    }

    public function store(Request $request)
    {
        if (empty($request->no_faktur)) {
            $request->merge(['no_faktur' => \App\Services\CodeGenerator::generatePurchaseRequestNo()]);
        }

        $validated = $request->validate([
            'no_faktur'                  => 'required|string|max:50|unique:nota_permintaan_pembelian,no_faktur',
            'tanggal'                    => 'required|date',
            'id_pengguna' => 'nullable|integer|exists:m_pengguna,id_pengguna',
            'tanggal_diperlukan'         => 'nullable|date',
            'keterangan'                 => 'nullable|string',
            'status'                     => 'required|integer',
            'details'                    => 'required|array|min:1',
            'details.*.sku'              => 'required|string|exists:m_barang,sku',
            'details.*.kuantitas'        => 'required|integer|min:1',
            'details.*.keterangan'       => 'nullable|string',
        ], [
            'no_faktur.unique' => 'Nomor Permintaan ":input" sudah digunakan. Silakan gunakan nomor lain atau generate otomatis.',
            'no_faktur.required' => 'Nomor Permintaan Pembelian wajib diisi.',
            'details.required' => 'Minimal harus ada 1 item barang yang diminta.',
            'details.min' => 'Minimal harus ada 1 item barang yang diminta.',
        ]);

        $headerData = collect($validated)->except('details')->all();
        $detailRows = $validated['details'];

        PurchaseRequest::createWithDetails($headerData, $detailRows);

        return redirect()->route('admin.purchase.requests.index')->with('success', 'Permintaan Pembelian berhasil dibuat.');
    }

    public function show($id)
    {
        $item = PurchaseRequest::with('details.barang')->findOrFail($id);
        return view('admin.purchase.requests.show', ['item' => $item]);
    }

    public function edit($id)
    {
        $item = PurchaseRequest::with('details')->findOrFail($id);
        $barangList = Barang::where('status', 1)->orderBy('nama_barang')->get();
        $penggunaList = Pengguna::jabatan(['gudang', 'purchasing'])->orderBy('nama_lengkap')->get();
        return view('admin.purchase.requests.edit', ['item' => $item, 'barangList' => $barangList, 'penggunaList' => $penggunaList]);
    }

    public function update(Request $request, $id)
    {
        $item = PurchaseRequest::findOrFail($id);

        $validated = $request->validate([
            'tanggal'                    => 'required|date',
            'id_pengguna' => 'nullable|integer|exists:m_pengguna,id_pengguna',
            'tanggal_diperlukan'         => 'nullable|date',
            'keterangan'                 => 'nullable|string',
            'status'                     => 'required|integer',
            'details'                    => 'required|array|min:1',
            'details.*.sku'              => 'required|string|exists:m_barang,sku',
            'details.*.kuantitas'        => 'required|integer|min:1',
            'details.*.keterangan'       => 'nullable|string',
        ]);

        $headerData = collect($validated)->except('details')->all();
        $detailRows = $validated['details'];

        $item->update($headerData);
        $item->syncDetails($detailRows);

        return redirect()->route('admin.purchase.requests.index')->with('success', 'Data berhasil diperbarui.');
    }

    public function destroy($id)
    {

        PurchaseRequest::findOrFail($id)->delete();

        return redirect()->route('admin.purchase.requests.index')->with('success', 'Data berhasil dihapus.');
    }
}