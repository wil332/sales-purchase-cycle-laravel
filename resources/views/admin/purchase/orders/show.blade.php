@extends('admin.layouts.admin')

@section('title', __('views.admin.purchase.orders.show.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table class="table table-bordered">
                <tr>
                    <th style="width:30%">No. Order Pembelian</th>
                    <td>{{ $item->no_order_pembelian }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>{{ $item->tanggal }}</td>
                </tr>
                <tr>
                    <th>Vendor</th>
                    <td>{{ $item->vendor->nama_vendor ?? '-' }}</td>
                </tr>
                <tr>
                    <th>ID Pengguna</th>
                    <td>{{ $item->id_pengguna }}</td>
                </tr>
                <tr>
                    <th>Tanggal Dibutuhkan</th>
                    <td>{{ $item->tanggal_dibutuhkan }}</td>
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
                            @case(2) <span class="label label-warning">Diproses</span> @break
                            @case(3) <span class="label label-primary">Selesai</span> @break
                            @default <span class="label label-default">{{ $item->status }}</span>
                        @endswitch
                    </td>
                </tr>
            </table>

            <h4>Daftar Barang Dipesan</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Nama Barang</th>
                        <th>Kuantitas</th>
                        <th>Harga Satuan</th>
                        <th>Diskon</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($item->details as $detail)
                        <tr>
                            <td>{{ $detail->sku }}</td>
                            <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                            <td>{{ $detail->kuantitas }}</td>
                            <td>{{ number_format($detail->harga_unit, 0, ',', '.') }}</td>
                            <td>{{ number_format($detail->diskon, 0, ',', '.') }}</td>
                            <td>{{ number_format($detail->total_harga, 0, ',', '.') }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center">Belum ada barang.</td></tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-right">Total Keseluruhan</th>
                        <th>{{ number_format($item->details->sum('total_harga'), 0, ',', '.') }}</th>
                    </tr>
                </tfoot>
            </table>

            <a class="btn btn-primary" href="{{ route('admin.purchase.orders.edit', [$item->no_order_pembelian]) }}">{{ __('views.admin.purchase.orders.show.edit') }}</a>
            <a class="btn btn-default" href="{{ route('admin.purchase.orders.index') }}">{{ __('views.admin.purchase.orders.show.back') }}</a>
        </div>
    </div>
@endsection