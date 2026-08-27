@extends('admin.layouts.admin')

@section('title', __('views.admin.purchase.requests.show.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table class="table table-bordered">
                <tr>
                    <th style="width:30%">No. Faktur</th>
                    <td>{{ $item->no_faktur }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>{{ $item->tanggal }}</td>
                </tr>
                <tr>
                    <th>ID Pengguna (Diminta Oleh)</th>
                    <td>{{ $item->id_pengguna }}</td>
                </tr>
                <tr>
                    <th>Tanggal Diperlukan</th>
                    <td>{{ $item->tanggal_diperlukan }}</td>
                </tr>
                <tr>
                    <th>Keterangan</th>
                    <td>{{ $item->keterangan }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @switch((int) $item->status)
                            @case(0) <span class="label label-danger">Batal</span> @break
                            @case(1) <span class="label label-success">Aktif</span> @break
                            @case(2) <span class="label label-warning">Parsial</span> @break
                            @default <span class="label label-default">{{ $item->status }}</span>
                        @endswitch
                    </td>
                </tr>
            </table>

            <h4>Daftar Barang Diminta</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Nama Barang</th>
                        <th>Kuantitas</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($item->details as $detail)
                        <tr>
                            <td>{{ $detail->sku }}</td>
                            <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                            <td>{{ $detail->kuantitas }}</td>
                            <td>{{ $detail->keterangan }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center">Belum ada barang.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <a class="btn btn-primary" href="{{ route('admin.purchase.requests.edit', [$item->no_faktur]) }}">{{ __('views.admin.purchase.requests.show.edit') }}</a>
            <a class="btn btn-default" href="{{ route('admin.purchase.requests.index') }}">{{ __('views.admin.purchase.requests.show.back') }}</a>
        </div>
    </div>
@endsection