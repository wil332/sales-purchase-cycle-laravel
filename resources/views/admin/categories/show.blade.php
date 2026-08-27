@extends('admin.layouts.admin')

@section('title', 'Detail Kategori: ' . $category->name)

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel" style="border-radius: 12px; border: 1px solid #e9edf2;">
            <div class="x_title">
                <h2><i class="fa fa-folder-open text-primary"></i> Detail Kategori "{{ $category->name }}"</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <table class="table table-bordered">
                    <tr>
                        <th style="width: 200px;">ID Kategori</th>
                        <td>{{ $category->id }}</td>
                    </tr>
                    <tr>
                        <th>Nama Kategori</th>
                        <td><strong>{{ $category->name }}</strong></td>
                    </tr>
                    <tr>
                        <th>Deskripsi</th>
                        <td>{{ $category->description ?: 'Tidak ada deskripsi.' }}</td>
                    </tr>
                    <tr>
                        <th>Total Produk Terkait</th>
                        <td><span class="label label-success" style="font-size: 13px;">{{ $category->products->count() }} Produk</span></td>
                    </tr>
                    <tr>
                        <th>Tanggal Dibuat</th>
                        <td>{{ optional($category->created_at)->format('d M Y H:i:s') }}</td>
                    </tr>
                </table>

                <h4 style="margin-top: 24px; font-weight: 700;">Daftar Produk dalam Kategori Ini:</h4>
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>SKU</th>
                                <th>Nama Barang</th>
                                <th>Keterangan</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($category->products as $p)
                                <tr>
                                    <td><strong>{{ $p->sku }}</strong></td>
                                    <td>{{ $p->nama_barang }}</td>
                                    <td>{{ $p->keterangan ?: '-' }}</td>
                                    <td>
                                        @if($p->status)
                                            <span class="label label-primary">Aktif</span>
                                        @else
                                            <span class="label label-danger">Tidak Aktif</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="text-center text-muted" style="padding: 20px;">Belum ada produk yang terhubung ke kategori ini.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div style="margin-top: 16px;">
                    <a href="{{ route('admin.categories.index') }}" class="btn btn-default">Kembali</a>
                    <a href="{{ route('admin.categories.edit', $category->id) }}" class="btn btn-info"><i class="fa fa-pencil"></i> Edit Kategori</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
