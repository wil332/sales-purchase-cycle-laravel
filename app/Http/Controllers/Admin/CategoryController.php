<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    /**
     * Display a listing of categories.
     */
    public function index()
    {
        return view('admin.categories.index');
    }

    /**
     * Server-side DataTables endpoint for categories.
     */
    public function getData(Request $request)
    {
        $draw = intval($request->get('draw', 1));
        $start = intval($request->get('start', 0));
        $length = intval($request->get('length', 10));
        $searchValue = $request->input('search.value', '');

        $query = Category::withCount('products');

        $totalRecords = $query->count();

        if (!empty($searchValue)) {
            $query->where(function ($q) use ($searchValue) {
                $q->where('name', 'LIKE', "%{$searchValue}%")
                  ->orWhere('description', 'LIKE', "%{$searchValue}%");
            });
        }

        $filteredRecords = $query->count();

        // Ordering
        $columns = ['id', 'name', 'description', 'products_count', 'created_at'];
        $orderColumnIndex = $request->input('order.0.column', 0);
        $orderDir = $request->input('order.0.dir', 'asc');
        $orderColumn = $columns[$orderColumnIndex] ?? 'id';

        $categories = $query->orderBy($orderColumn, $orderDir)
            ->offset($start)
            ->limit($length)
            ->get();

        $data = [];
        foreach ($categories as $cat) {
            $showUrl = route('admin.categories.show', $cat->id);
            $editUrl = route('admin.categories.edit', $cat->id);
            $destroyUrl = route('admin.categories.destroy', $cat->id);

            $actions = '
                <a href="' . $showUrl . '" class="btn btn-xs btn-primary" data-toggle="tooltip" title="Lihat"><i class="fa fa-eye"></i></a>
                <a href="' . $editUrl . '" class="btn btn-xs btn-info" data-toggle="tooltip" title="Edit"><i class="fa fa-pencil"></i></a>
                <form action="' . $destroyUrl . '" method="POST" style="display:inline-block;" onsubmit="return confirm(\'Apakah Anda yakin ingin menghapus kategori ini?\');">
                    ' . csrf_field() . '
                    ' . method_field('DELETE') . '
                    <button type="submit" class="btn btn-xs btn-danger" data-toggle="tooltip" title="Hapus"><i class="fa fa-trash"></i></button>
                </form>
            ';

            $data[] = [
                'id' => $cat->id,
                'name' => '<strong>' . e($cat->name) . '</strong>',
                'description' => e(\Illuminate\Support\Str::limit($cat->description, 60) ?: '-'),
                'products_count' => '<span class="badge bg-green">' . $cat->products_count . ' Produk</span>',
                'created_at' => optional($cat->created_at)->format('d M Y H:i'),
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

    /**
     * Show form for creating a new category.
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created category.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name',
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Nama Kategori wajib diisi.',
            'name.unique' => 'Nama Kategori ":input" sudah terdaftar.',
        ]);

        Category::create($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori baru berhasil ditambahkan.');
    }

    /**
     * Display the specified category.
     */
    public function show($id)
    {
        $category = Category::with('products')->findOrFail($id);
        return view('admin.categories.show', compact('category'));
    }

    /**
     * Show form for editing the specified category.
     */
    public function edit($id)
    {
        $category = Category::findOrFail($id);
        return view('admin.categories.edit', compact('category'));
    }

    /**
     * Update the specified category.
     */
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:categories,name,' . $id,
            'description' => 'nullable|string',
        ], [
            'name.required' => 'Nama Kategori wajib diisi.',
            'name.unique' => 'Nama Kategori ":input" sudah digunakan oleh kategori lain.',
        ]);

        $category->update($validated);

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil diperbarui.');
    }

    /**
     * Remove the specified category.
     */
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return redirect()->route('admin.categories.index')->with('success', 'Kategori berhasil dihapus.');
    }
}
