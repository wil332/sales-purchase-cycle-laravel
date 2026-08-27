@extends('admin.layouts.admin')

@section('title', 'Transaksi Pembayaran Xendit')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div style="margin-bottom: 15px;">
                <a class="btn btn-success" href="{{ route('admin.xendit.create') }}">+ Buat Invoice Pembayaran</a>
            </div>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>Reference ID</th>
                        <th>No. Invoice Jual</th>
                        <th>Jumlah</th>
                        <th>Status</th>
                        <th>Dibuat</th>
                        <th style="width:120px">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($payments as $payment)
                        <tr>
                            <td>{{ $payment->reference_id }}</td>
                            <td>{{ $payment->no_invoice_jual ?? '-' }}</td>
                            <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                            <td>
                                @php
                                    $badge = in_array($payment->status, ['PAID', 'SETTLED'])
                                        ? 'success'
                                        : (in_array($payment->status, ['EXPIRED', 'FAILED']) ? 'danger' : 'default');
                                @endphp
                                <span class="label label-{{ $badge }}">{{ $payment->status }}</span>
                            </td>
                            <td>{{ $payment->created_at->format('d-m-Y H:i') }}</td>
                            <td>
                                <a class="btn btn-xs btn-info" href="{{ route('admin.xendit.show', $payment->id) }}">Detail</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">Belum ada transaksi.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $payments->links() }}
        </div>
    </div>
@endsection
