@extends('admin.layouts.admin')

@section('title', __('views.admin.sales.payments.index.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div style="margin-bottom: 15px;">
                <a class="btn btn-success" href="{{ route('admin.sales.payments.create') }}">{{ __('views.admin.sales.payments.index.create') }}</a>
            </div>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width:15%">No. Pembayaran</th>
                        <th>Tanggal Bayar</th>
                        <th>No. Invoice</th>
                        <th>Jumlah Bayar</th>
                        <th>Metode Bayar</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th style="width:190px">{{ __('views.admin.sales.payments.index.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td>{{ $item->no_pembayaran }}</td>
                            <td>{{ $item->tanggal_bayar }}</td>
                            <td>{{ $item->no_invoice_jual }}</td>
                            <td>{{ $item->jumlah_bayar }}</td>
                            <td>{{ $item->metode_bayar }}</td>
                            <td>{{ $item->keterangan }}</td>
                            <td>
                                @switch((int) $item->status)
                                    @case(0)
                                        <span class="label label-danger">Batal</span>
                                        @break
                                    @case(1)
                                        <span class="label label-success">Valid/Sukses</span>
                                        @break
                                    @default
                                        <span class="label label-default">{{ $item->status }}</span>
                                @endswitch
                            </td>
                            <td style="white-space: nowrap;">
                                <a class="btn btn-xs btn-info" href="{{ route('admin.sales.payments.show', [$item->no_pembayaran]) }}" title="Lihat"><i class="fa fa-eye"></i></a>
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.sales.payments.edit', [$item->no_pembayaran]) }}" title="Edit"><i class="fa fa-pencil"></i></a>
                                <form action="{{ route('admin.sales.payments.destroy', [$item->no_pembayaran]) }}" method="POST" style="display: inline-block; margin: 0;" onsubmit="return confirm('{{ __('views.admin.sales.payments.index.confirm_delete') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-danger" title="Hapus"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">{{ __('views.admin.sales.payments.index.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $items->links() }}
        </div>
    </div>
@endsection
