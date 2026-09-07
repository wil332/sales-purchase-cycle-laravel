<?php

namespace App\Http\Controllers\Admin\product;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function getProductList()
    {
        // Query products with JOIN to categories table to select category name
        $products = Product::leftJoin('categories', 'm_barang.category_id', '=', 'categories.id')
            ->select('m_barang.*', 'categories.name as category_name')
            ->orderBy('m_barang.created_at', 'desc')
            ->get();

        return view('admin.products.index', compact('products'));
    }

    public function checkSku(Request $request)
    {
        $sku = trim($request->query('sku', ''));
        $exists = Product::where('sku', $sku)->exists();
        return response()->json([
            'exists' => $exists,
            'message' => $exists 
                ? "SKU \"{$sku}\" sudah terdaftar di sistem. Gunakan SKU lain atau klik Acak." 
                : "SKU \"{$sku}\" tersedia (belum pernah digunakan)."
        ]);
    }

    /**
     * Server-Side DataTables AJAX endpoint for Products
     */
    public function getProductsData(Request $request)
    {
        $draw = intval($request->get('draw', 1));
        $start = intval($request->get('start', 0));
        $length = intval($request->get('length', 10));
        $searchValue = $request->input('search.value', '');

        $query = Product::leftJoin('categories', 'm_barang.category_id', '=', 'categories.id')
            ->select('m_barang.*', 'categories.name as category_name');

        $totalRecords = Product::count();

        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('m_barang.sku', 'LIKE', "%{$searchValue}%")
                  ->orWhere('m_barang.nama_barang', 'LIKE', "%{$searchValue}%")
                  ->orWhere('m_barang.keterangan', 'LIKE', "%{$searchValue}%")
                  ->orWhere('categories.name', 'LIKE', "%{$searchValue}%");
            });
        }

        $filteredRecords = $query->count();

        $columns = ['sku', 'nama_barang', 'category_name', 'keterangan', 'status', 'created_at'];
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'desc');
        $orderColumn = $columns[$orderColumnIndex] ?? 'sku';

        $products = $query->orderBy('m_barang.' . ($orderColumn === 'category_name' ? 'sku' : $orderColumn), $orderDir)
            ->offset($start)
            ->limit($length)
            ->get();

        $data = [];
        foreach ($products as $p) {
            $showUrl = route('admin.products.show', $p->sku);
            $editUrl = route('admin.products.edit', $p->sku);
            $destroyUrl = route('admin.products.destroy', $p->sku);

            $actions = '
                <a class="btn btn-xs btn-primary" href="' . $showUrl . '" data-toggle="tooltip" title="Lihat"><i class="fa fa-eye"></i></a>
                <a class="btn btn-xs btn-info" href="' . $editUrl . '" data-toggle="tooltip" title="Edit"><i class="fa fa-pencil"></i></a>
                <a href="' . $destroyUrl . '" class="btn btn-xs btn-danger" onclick="return confirm(\'Hapus produk ini?\')" data-toggle="tooltip" title="Hapus"><i class="fa fa-trash"></i></a>
            ';

            $data[] = [
                'sku' => '<strong>' . e($p->sku) . '</strong>',
                'gambar' => '<img src="' . e($p->gambar_url) . '" alt="' . e($p->nama_barang) . '" class="img-thumbnail" style="width: 44px; height: 44px; object-fit: cover; border-radius: 8px; border: 1px solid #e2e8f0;">',
                'nama_barang' => e($p->nama_barang),
                'category_name' => $p->category_name 
                    ? '<span class="label label-info"><i class="fa fa-tag"></i> ' . e($p->category_name) . '</span>' 
                    : '<span class="label label-default">Tanpa Kategori</span>',
                'keterangan' => e(\Illuminate\Support\Str::limit($p->keterangan, 40) ?: '-'),
                'status' => $p->status 
                    ? '<span class="label label-success">Aktif</span>' 
                    : '<span class="label label-danger">Tidak Aktif</span>',
                'created_at' => optional($p->created_at)->format('d M Y'),
                'updated_at' => optional($p->updated_at)->format('d M Y'),
                'actions' => $actions,
            ];
        }

        return response()->json([
            'draw' => $draw,
            'recordsTotal' => $totalRecords,
            'recordsFiltered' => $filteredRecords,
            'data' => $data,
        ]);
    }

    public function getProductCreate()
    {
        $nextSku = \App\Services\CodeGenerator::generateSku();
        $categories = Category::orderBy('name')->get();
        return view('admin.products.create', compact('nextSku', 'categories'));
    }

    public function postProductCreate(Request $request)
    {
        if (empty($request->sku)) {
            $request->merge(['sku' => \App\Services\CodeGenerator::generateSku()]);
        }

        $validated = $request->validate([
            'sku'          => 'required|string|max:50|unique:m_barang,sku',
            'category_id'  => 'nullable|integer|exists:categories,id',
            'nama_barang'  => 'required|string|max:255',
            'keterangan'   => 'nullable|string',
            'status'       => 'required|boolean',
        ], [
            'sku.unique' => 'Kode SKU ":input" sudah terdaftar di sistem. Silakan gunakan SKU lain atau generate otomatis.',
            'sku.required' => 'Kode SKU wajib diisi.',
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'category_id.exists' => 'Kategori terpilih tidak valid.',
        ]);

        Product::create($validated);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil ditambahkan.');
    }

    public function getProductShow($sku)
    {
        $product = Product::with('category')->findOrFail($sku);
        return view('admin.products.show', compact('product'));
    }

    public function getProductEdit($sku)
    {
        $product = Product::findOrFail($sku);
        $categories = Category::orderBy('name')->get();
        return view('admin.products.edit', compact('product', 'categories'));
    }

    public function putProductEdit(Request $request, $sku)
    {
        $product = Product::findOrFail($sku);

        $validated = $request->validate([
            'category_id' => 'nullable|integer|exists:categories,id',
            'nama_barang' => 'required|string|max:255',
            'keterangan'  => 'nullable|string',
            'status'      => 'required|boolean',
        ], [
            'nama_barang.required' => 'Nama barang wajib diisi.',
            'category_id.exists' => 'Kategori terpilih tidak valid.',
        ]);

        $product->update($validated);

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil diperbarui.');
    }

    public function getProductDestroy($sku)
    {
        $product = Product::findOrFail($sku);
        $product->delete();

        return redirect()->route('admin.products.index')->with('success', 'Produk berhasil dihapus.');
    }
}