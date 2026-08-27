@extends('admin.layouts.admin')

@section('title', __('views.admin.master.pelanggan.show.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table class="table table-bordered">
                <tr>
                    <th style="width:30%">Nama Pelanggan</th>
                    <td>{{ $item->nama_pelanggan }}</td>
                </tr>
                <tr>
                    <th>No. Telepon</th>
                    <td>{{ $item->no_telp }}</td>
                </tr>
                <tr>
                    <th>Alamat</th>
                    <td>{{ $item->alamat }}</td>
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

            <a class="btn btn-primary" href="{{ route('admin.master.pelanggan.edit', [$item->id_pelanggan]) }}">{{ __('views.admin.master.pelanggan.show.edit') }}</a>
            <a class="btn btn-default" href="{{ route('admin.master.pelanggan.index') }}">{{ __('views.admin.master.pelanggan.show.back') }}</a>
        </div>
    </div>
@endsection
