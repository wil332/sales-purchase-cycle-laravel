@extends('admin.layouts.admin')

@section('title', __('views.admin.purchase.payments.show.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table class="table table-bordered">
                <tr><th style="width:30%">No. Pembayaran</th><td>{{ $item->no_pembayaran_beli }}</td></tr>
                <tr><th>Tanggal Bayar</th><td>{{ optional($item->tanggal_bayar)->format('d-m-Y') }}</td></tr>
                <tr><th>Invoice Utama</th><td>{{ $item->purchaseInvoice->no_invoice_beli ?? $item->no_invoice_beli }}</td></tr>
                <tr><th>Dicatat Oleh</th><td>{{ $item->pengguna->nama_lengkap ?? '-' }}</td></tr>
                <tr><th>Jumlah Bayar</th><td>{{ number_format($item->jumlah_bayar, 2) }}</td></tr>
                <tr><th>Metode Bayar</th><td>{{ ucfirst($item->metode_bayar) }}</td></tr>
                <tr><th>Keterangan</th><td>{{ $item->keterangan }}</td></tr>
                <tr>
                    <th>Status</th>
                    <td>
                        @switch((int) $item->status)
                            @case(0) <span class="label label-danger">Batal</span> @break
                            @case(1) <span class="label label-success">Valid/Sukses</span> @break
                            @default <span class="label label-default">{{ $item->status }}</span>
                        @endswitch
                    </td>
                </tr>
            </table>

            <h4>Alokasi ke Invoice</h4>
            <table class="table table-bordered">
                <thead>
                    <tr><th>No. Invoice</th><th>Jumlah Dialokasikan</th><th>Keterangan</th></tr>
                </thead>
                <tbody>
                    @forelse($item->details as $detail)
                        <tr>
                            <td>{{ $detail->no_invoice_beli }}</td>
                            <td>{{ number_format($detail->jumlah_dialokasikan, 2) }}</td>
                            <td>{{ $detail->keterangan }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center">Belum ada alokasi.</td></tr>
                    @endforelse
                </tbody>
            </table>

            <a class="btn btn-primary" href="{{ route('admin.purchase.payments.edit', [$item->no_pembayaran_beli]) }}">{{ __('views.admin.purchase.payments.show.edit') }}</a>
            <a class="btn btn-default" href="{{ route('admin.purchase.payments.index') }}">{{ __('views.admin.purchase.payments.show.back') }}</a>
        </div>
    </div>
@endsection
