@extends('admin.layouts.admin')

@section('title', __('views.admin.master.pengguna.show.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table class="table table-bordered">
                <tr>
                    <th style="width:30%">Nama Lengkap</th>
                    <td>{{ $item->nama_lengkap }}</td>
                </tr>
                <tr>
                    <th>Username</th>
                    <td>{{ $item->username }}</td>
                </tr>
                <tr>
                    <th>Jabatan</th>
                    <td>{{ ucfirst($item->jabatan) }}</td>
                </tr>
                <tr>
                    <th>Keterangan</th>
                    <td>{{ $item->keterangan }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ (int) $item->status === 1 ? 'Aktif' : 'Nonaktif' }}</td>
                </tr>
            </table>

            <a class="btn btn-primary" href="{{ route('admin.master.pengguna.edit', [$item->id_pengguna]) }}">{{ __('views.admin.master.pengguna.show.edit') }}</a>
            <a class="btn btn-default" href="{{ route('admin.master.pengguna.index') }}">{{ __('views.admin.master.pengguna.show.back') }}</a>
        </div>
    </div>
@endsection
