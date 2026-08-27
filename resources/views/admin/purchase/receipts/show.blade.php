@extends('admin.layouts.admin')

@section('title', __('views.admin.purchase.receipts.show.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table class="table table-bordered">
                <tr><th style="width:30%">No. Penerimaan</th><td>{{ $item->no_penerimaan }}</td></tr>
                <tr><th>Tanggal Penerimaan</th><td>{{ $item->tanggal_penerimaan }}</td></tr>
                <tr><th>No. Order Pembelian</th><td>{{ $item->no_order_pembelian ?? '-' }}</td></tr>
                <tr><th>ID Pengguna</th><td>{{ $item->id_pengguna }}</td></tr>
                <tr><th>Keterangan</th><td>{{ $item->keterangan }}</td></tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @switch((int) $item->status)
                            @case(0) <span class="label label-danger">Masalah</span> @break
                            @case(1) <span class="label label-success">Diterima</span> @break
                            @default <span class="label label-default">{{ $item->status }}</span>
                        @endswitch
                    </td>
                </tr>
            </table>

            <h4>Daftar Barang Diterima</h4>
            <table class="table table-bordered">
                <thead>
                    <tr><th>SKU</th><th>Nama Barang</th><th>Kuantitas</th><th>Kondisi</th><th>Keterangan</th></tr>
                </thead>
                <tbody>
                    @forelse($item->details as $detail)
                        <tr>
                            <td>{{ $detail->sku }}</td>
                            <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                            <td>{{ $detail->kuantitas }}</td>
                            <td>
                                @if($detail->kondisi_barang === 'baik')
                                    <span class="label label-success">Baik</span>
                                @else
                                    <span class="label label-danger">Rusak</span>
                                @endif
                            </td>
                            <td>{{ $detail->keterangan }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center">Belum ada barang.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <a class="btn btn-primary" href="{{ route('admin.purchase.receipts.edit', [$item->no_penerimaan]) }}">{{ __('views.admin.purchase.receipts.show.edit') }}</a>
            <a class="btn btn-default" href="{{ route('admin.purchase.receipts.index') }}">{{ __('views.admin.purchase.receipts.show.back') }}</a>
        </div>
    </div>
@endsection