@extends('admin.layouts.admin')

@section('title', __('views.admin.sales.orders.show.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table class="table table-bordered">
                <tr>
                    <th style="width:30%">No. Order Penjualan</th>
                    <td>{{ $item->no_order_penjualan }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>{{ $item->tanggal }}</td>
                </tr>
                <tr>
                    <th>Pelanggan</th>
                    <td>{{ $item->pelanggan->nama_pelanggan ?? $item->id_pelanggan }}</td>
                </tr>
                <tr>
                    <th>Sales</th>
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
                        <th>Kuantitas</th>
                        <th>Harga Jual</th>
                        <th>Diskon</th>
                        <th>Total Harga</th>
                        <th>Keterangan</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($item->details as $detail)
                        <tr>
                            <td>{{ $detail->sku }}</td>
                            <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                            <td>{{ $detail->kuantitas }}</td>
                            <td>{{ number_format($detail->harga_jual, 2) }}</td>
                            <td>{{ number_format($detail->diskon, 2) }}</td>
                            <td>{{ number_format($detail->total_harga, 2) }}</td>
                            <td>{{ $detail->keterangan }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">Belum ada item.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-right">Total</th>
                        <th>{{ number_format($item->details->sum('total_harga'), 2) }}</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>

            <a class="btn btn-primary" href="{{ route('admin.sales.orders.edit', [$item->no_order_penjualan]) }}">{{ __('views.admin.sales.orders.show.edit') }}</a>
            <a class="btn btn-default" href="{{ route('admin.sales.orders.index') }}">{{ __('views.admin.sales.orders.show.back') }}</a>
        </div>
    </div>
@endsection
