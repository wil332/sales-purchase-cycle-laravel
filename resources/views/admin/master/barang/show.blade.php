@extends('admin.layouts.admin')

@section('title', __('views.admin.master.barang.show.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table class="table table-bordered">
                <tr>
                    <th style="width:30%">SKU</th>
                    <td>{{ $item->sku }}</td>
                </tr>
                <tr>
                    <th>Nama Barang</th>
                    <td>{{ $item->nama_barang }}</td>
                </tr>
                <tr>
                    <th>Keterangan</th>
                    <td>{{ $item->keterangan }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ (int) $item->status === 1 ? 'Aktif' : 'Tidak Aktif' }}</td>
                </tr>
            </table>

            <a class="btn btn-primary" href="{{ route('admin.master.barang.edit', [$item->sku]) }}">{{ __('views.admin.master.barang.show.edit') }}</a>
            <a class="btn btn-default" href="{{ route('admin.master.barang.index') }}">{{ __('views.admin.master.barang.show.back') }}</a>
        </div>
    </div>
@endsection
