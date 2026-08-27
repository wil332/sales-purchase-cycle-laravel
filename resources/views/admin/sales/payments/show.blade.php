@extends('admin.layouts.admin')

@section('title', __('views.admin.sales.payments.show.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <table class="table table-bordered">
                <tr>
                    <th style="width:30%">No. Pembayaran</th>
                    <td>{{ $item->no_pembayaran }}</td>
                </tr>
                <tr>
                    <th>Tanggal Bayar</th>
                    <td>{{ $item->tanggal_bayar }}</td>
                </tr>
                <tr>
                    <th>No. Invoice</th>
                    <td>{{ $item->no_invoice_jual }}</td>
                </tr>
                <tr>
                    <th>Jumlah Bayar</th>
                    <td>{{ $item->jumlah_bayar }}</td>
                </tr>
                <tr>
                    <th>Metode Bayar</th>
                    <td>{{ $item->metode_bayar }}</td>
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

            <a class="btn btn-primary" href="{{ route('admin.sales.payments.edit', [$item->no_pembayaran]) }}">{{ __('views.admin.sales.payments.show.edit') }}</a>
            <a class="btn btn-default" href="{{ route('admin.sales.payments.index') }}">{{ __('views.admin.sales.payments.show.back') }}</a>
        </div>
    </div>
@endsection
