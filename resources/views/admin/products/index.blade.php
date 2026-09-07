@extends('admin.layouts.admin')

@section('title', __('views.admin.products.index.title'))

@php
    use Illuminate\Support\Str;
@endphp

@section('content')
    <div class="row" style="margin-bottom: 16px;">
        <div class="col-md-12">
            <a href="{{ route('admin.products.create') }}" class="btn btn-success">
                <i class="fa fa-plus"></i> Tambah Produk Baru
            </a>
            <a href="{{ route('admin.categories.index') }}" class="btn btn-info pull-right">
                <i class="fa fa-folder-open"></i> Kelola Kategori
            </a>
        </div>
    </div>
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <div class="x_panel" style="border-radius: 12px; border: 1px solid #e9edf2; box-shadow: 0 2px 10px rgba(0,0,0,0.04);">
                <div class="x_title">
                    <h2><i class="fa fa-cubes text-primary"></i> Daftar Barang</h2>
                    <div class="clearfix"></div>
                </div>
                <div class="x_content">
                    <div class="table-responsive">
                        <table id="products-table" class="table table-striped table-bordered table-hover" style="width:100%;">
                            <thead>
                                <tr>
                                    <th style="width: 60px;">Foto</th>
                                    <th>SKU</th>
                                    <th>Nama Barang</th>
                                    <th>Kategori</th>
                                    <th>Keterangan</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Updated</th>
                                    <th style="width: 110px;">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach($products as $product)
                                <tr>
                                    <td class="text-center" style="vertical-align: middle;">
                                        <img src="{{ $product->gambar_url }}" alt="{{ $product->nama_barang }}" class="img-thumbnail" style="width: 44px; height: 44px; object-fit: cover; border-radius: 8px; border: 1px solid #cbd5e1; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
                                    </td>
                                    <td style="vertical-align: middle;"><strong>{{ $product->sku }}</strong></td>
                                    <td style="vertical-align: middle;">{{ $product->nama_barang }}</td>
                                    <td style="vertical-align: middle;">
                                        @if($product->category_name)
                                            <span class="label label-info"><i class="fa fa-tag"></i> {{ $product->category_name }}</span>
                                        @elseif($product->category)
                                            <span class="label label-info"><i class="fa fa-tag"></i> {{ $product->category->name }}</span>
                                        @else
                                            <span class="label label-default">Tanpa Kategori</span>
                                        @endif
                                    </td>
                                    <td style="vertical-align: middle;">{{ Str::limit($product->keterangan, 40) ?? '-' }}</td>
                                    <td style="vertical-align: middle;">
                                        @if($product->status)
                                            <span class="label label-success">{{ __('views.admin.products.index.active') }}</span>
                                        @else
                                            <span class="label label-danger">{{ __('views.admin.products.index.inactive') }}</span>
                                        @endif
                                    </td>
                                    <td style="vertical-align: middle;">{{ optional($product->created_at)->format('d M Y') }}</td>
                                    <td style="vertical-align: middle;">{{ optional($product->updated_at)->format('d M Y') }}</td>
                                    <td style="vertical-align: middle;">
                                        <a class="btn btn-xs btn-primary" href="{{ route('admin.products.show', [$product->sku]) }}" data-toggle="tooltip" title="{{ __('views.admin.products.index.show') }}">
                                            <i class="fa fa-eye"></i>
                                        </a>
                                        <a class="btn btn-xs btn-info" href="{{ route('admin.products.edit', [$product->sku]) }}" data-toggle="tooltip" title="{{ __('views.admin.products.index.edit') }}">
                                            <i class="fa fa-pencil"></i>
                                        </a>
                                        <a href="{{ route('admin.products.destroy', [$product->sku]) }}" class="btn btn-xs btn-danger" onclick="return confirm('Apakah Anda yakin ingin menghapus produk ini?');" data-toggle="tooltip" title="{{ __('views.admin.products.index.delete') }}">
                                            <i class="fa fa-trash"></i>
                                        </a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
@parent
<script>
$(document).ready(function(){
    $('#products-table').DataTable({
        pageLength: 10,
        responsive: true,
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ],
        language: {
            search: "Cari Produk / Kategori:",
            lengthMenu: "Tampilkan _MENU_ baris",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ produk",
            emptyTable: "Belum ada data produk."
        }
    });
});
</script>
@endsection
