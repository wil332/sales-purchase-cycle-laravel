@extends('admin.layouts.admin')

@section('title', __('views.admin.sales.shipments.show.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table class="table table-bordered">
                <tr>
                    <th style="width:30%">No. Pengiriman</th>
                    <td>{{ $item->no_pengiriman }}</td>
                </tr>
                <tr>
                    <th>Tanggal Kirim</th>
                    <td>{{ $item->tanggal_kirim }}</td>
                </tr>
                <tr>
                    <th>No. Order Penjualan</th>
                    <td>{{ $item->no_order_penjualan }}</td>
                </tr>
                <tr>
                    <th>Staff Gudang</th>
                    <td>{{ $item->pengguna->nama_lengkap ?? $item->id_pengguna }}</td>
                </tr>
                <tr>
                    <th>Keterangan</th>
                    <td>{{ $item->keterangan }}</td>
                </tr>
                <tr>
                    <th>Status</th>
                    <td>{{ $item->status }}</td>
                </tr>
            </table>

            <h4>Item Barang</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Nama Barang</th>
                        <th>Kuantitas Dikirim</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($item->details as $detail)
                        <tr>
                            <td>{{ $detail->sku }}</td>
                            <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                            <td>{{ $detail->kuantitas_dikirim }}</td>
                            <td>{{ $detail->keterangan }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada item.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <a class="btn btn-primary" href="{{ route('admin.sales.shipments.edit', [$item->no_pengiriman]) }}">{{ __('views.admin.sales.shipments.show.edit') }}</a>
            <a class="btn btn-default" href="{{ route('admin.sales.shipments.index') }}">{{ __('views.admin.sales.shipments.show.back') }}</a>
        </div>
    </div>
@endsection
