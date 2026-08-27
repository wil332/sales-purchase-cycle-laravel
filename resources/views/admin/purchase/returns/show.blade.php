@extends('admin.layouts.admin')

@section('title', __('views.admin.purchase.returns.show.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table class="table table-bordered">
                <tr><th style="width:30%">No. Retur</th><td>{{ $item->nota_retur }}</td></tr>
                <tr><th>Nomor Urut</th><td>{{ $item->nomor_urut }}</td></tr>
                <tr><th>Referensi Penerimaan</th><td>{{ $item->goodsReceipt->no_penerimaan ?? $item->atas_penerimaan_nomor ?? '-' }}</td></tr>
                <tr><th>Tanggal</th><td>{{ optional($item->tanggal)->format('d-m-Y') }}</td></tr>
                <tr><th>Vendor</th><td>{{ $item->vendor->nama_vendor ?? '-' }}</td></tr>
                <tr><th>Keterangan</th><td>{{ $item->keterangan }}</td></tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @switch((int) $item->status)
                            @case(0) <span class="label label-danger">Batal</span> @break
                            @case(1) <span class="label label-success">Aktif</span> @break
                            @default <span class="label label-default">{{ $item->status }}</span>
                        @endswitch
                    </td>
                </tr>
            </table>

            <h4>Daftar Barang Diretur</h4>
            <table class="table table-bordered">
                <thead>
                    <tr><th>SKU</th><th>Nama Barang</th><th>Jumlah</th><th>Harga Satuan</th><th>Diskon</th><th>Total</th><th>Keterangan</th></tr>
                </thead>
                <tbody>
                    @forelse($item->details as $detail)
                        <tr>
                            <td>{{ $detail->sku }}</td>
                            <td>{{ $detail->barang->nama_barang ?? '-' }}</td>
                            <td>{{ $detail->jumlah }}</td>
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

            <a class="btn btn-primary" href="{{ route('admin.purchase.returns.edit', [$item->nota_retur]) }}">{{ __('views.admin.purchase.returns.show.edit') }}</a>
            <a class="btn btn-default" href="{{ route('admin.purchase.returns.index') }}">{{ __('views.admin.purchase.returns.show.back') }}</a>
        </div>
    </div>
@endsection
