@extends('admin.layouts.admin')

@section('title', __('views.admin.purchase.invoices.show.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table class="table table-bordered">
                <tr><th style="width:30%">No. Invoice</th><td>{{ $item->no_invoice_beli }}</td></tr>
                <tr><th>Tanggal</th><td>{{ optional($item->tanggal)->format('d-m-Y') }}</td></tr>
                <tr><th>No. Penerimaan</th><td>{{ $item->no_penerimaan ?? '-' }}</td></tr>
                <tr><th>Vendor</th><td>{{ $item->vendor->nama_vendor ?? '-' }}</td></tr>
                <tr><th>Dicatat Oleh</th><td>{{ $item->pengguna->nama_lengkap ?? '-' }}</td></tr>
                <tr><th>Jatuh Tempo</th><td>{{ optional($item->jatuh_tempo)->format('d-m-Y') }}</td></tr>
                <tr><th>Keterangan</th><td>{{ $item->keterangan }}</td></tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @switch((int) $item->status)
                            @case(0) <span class="label label-danger">Batal</span> @break
                            @case(1) <span class="label label-warning">Belum Dibayar</span> @break
                            @case(2) <span class="label label-info">Dibayar Sebagian</span> @break
                            @case(3) <span class="label label-success">Lunas</span> @break
                            @default <span class="label label-default">{{ $item->status }}</span>
                        @endswitch
                    </td>
                </tr>
            </table>

            <h4>Daftar Barang Ditagih</h4>
            <table class="table table-bordered">
                <thead>
                    <tr><th>SKU</th><th>Nama Barang</th><th>Kuantitas</th><th>Harga Satuan</th><th>Diskon</th><th>Total</th><th>Keterangan</th></tr>
                </thead>
                <tbody>
                    @forelse($item->details as $detail)
                        <tr>
                            <td>{{ $detail->sku }}</td>
                            <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                            <td>{{ $detail->kuantitas }}</td>
                            <td>{{ number_format($detail->harga_unit, 2) }}</td>
                            <td>{{ number_format($detail->diskon, 2) }}</td>
                            <td>{{ number_format($detail->total_harga, 2) }}</td>
                            <td>{{ $detail->keterangan }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center">Belum ada barang.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <a class="btn btn-primary" href="{{ route('admin.purchase.invoices.edit', [$item->no_invoice_beli]) }}">{{ __('views.admin.purchase.invoices.show.edit') }}</a>
            <a class="btn btn-default" href="{{ route('admin.purchase.invoices.index') }}">{{ __('views.admin.purchase.invoices.show.back') }}</a>
        </div>
    </div>
@endsection
