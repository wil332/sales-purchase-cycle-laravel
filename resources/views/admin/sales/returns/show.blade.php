@extends('admin.layouts.admin')

@section('title', __('views.admin.sales.returns.show.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table class="table table-bordered">
                <tr>
                    <th style="width:30%">No. Retur</th>
                    <td>{{ $item->no_retur_jual }}</td>
                </tr>
                <tr>
                    <th>Tanggal</th>
                    <td>{{ $item->tanggal }}</td>
                </tr>
                <tr>
                    <th>No. Invoice</th>
                    <td>{{ $item->no_invoice_jual }}</td>
                </tr>
                <tr>
                    <th>Pelanggan</th>
                    <td>{{ $item->pelanggan->nama_pelanggan ?? $item->id_pelanggan }}</td>
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

            <h4>Item Retur</h4>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>SKU</th>
                        <th>Nama Barang</th>
                        <th>Jumlah Diretur</th>
                        <th>Alasan Retur</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($item->details as $detail)
                        <tr>
                            <td>{{ $detail->sku }}</td>
                            <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                            <td>{{ $detail->jumlah_diretur }}</td>
                            <td>{{ $detail->alasan_retur }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center">Belum ada item.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            <a class="btn btn-primary" href="{{ route('admin.sales.returns.edit', [$item->no_retur_jual]) }}">{{ __('views.admin.sales.returns.show.edit') }}</a>
            <a class="btn btn-default" href="{{ route('admin.sales.returns.index') }}">{{ __('views.admin.sales.returns.show.back') }}</a>
        </div>
    </div>
@endsection
