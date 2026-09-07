@extends('admin.layouts.admin')

@section('title', 'Manajemen Kategori Produk')

@section('content')
<div class="row" style="margin-bottom: 16px;">
    <div class="col-md-12">
        <a href="{{ route('admin.categories.create') }}" class="btn btn-success">
            <i class="fa fa-plus"></i> Tambah Kategori Baru
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel" style="border-radius: 12px; border: 1px solid #e9edf2; box-shadow: 0 2px 10px rgba(0,0,0,0.04);">
            <div class="x_title">
                <h2><i class="fa fa-folder-open text-primary"></i> Kategori Produk </h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <div class="table-responsive">
                    <table id="categories-table" class="table table-striped table-bordered table-hover dt-responsive nowrap" style="width:100%;">
                        <thead>
                            <tr>
                                <th style="width: 60px;">ID</th>
                                <th>Nama Kategori</th>
                                <th>Deskripsi</th>
                                <th>Jumlah Produk</th>
                                <th>Tanggal Dibuat</th>
                                <th style="width: 120px;">Aksi</th>
                            </tr>
                        </thead>
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
$(document).ready(function() {
    $('#categories-table').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('admin.categories.data') }}",
        columns: [
            { data: 'id', name: 'id' },
            { data: 'name', name: 'name' },
            { data: 'description', name: 'description' },
            { data: 'products_count', name: 'products_count', searchable: false },
            { data: 'created_at', name: 'created_at' },
            { data: 'actions', name: 'actions', orderable: false, searchable: false }
        ],
        pageLength: 10,
        responsive: true,
        order: [[0, 'desc']],
        language: {
            processing: '<i class="fa fa-spinner fa-spin fa-2x fa-fw text-primary"></i><span class="sr-only">Memuat data...</span>',
            search: "Cari Kategori:",
            lengthMenu: "Tampilkan _MENU_ baris",
            info: "Menampilkan _START_ sampai _END_ dari _TOTAL_ kategori",
            infoEmpty: "Menampilkan 0 sampai 0 dari 0 kategori",
            emptyTable: "Belum ada kategori produk.",
            paginate: {
                first: "Pertama",
                previous: "Sebelumnya",
                next: "Selanjutnya",
                last: "Terakhir"
            }
        }
    });
});
</script>
@endsection
