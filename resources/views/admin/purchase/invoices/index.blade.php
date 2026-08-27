@extends('admin.layouts.admin')

@section('title', __('views.admin.purchase.invoices.index.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div style="margin-bottom: 15px;">
                <a class="btn btn-success" href="{{ route('admin.purchase.invoices.create') }}">{{ __('views.admin.purchase.invoices.index.create') }}</a>
            </div>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width:15%">No. Invoice</th>
                        <th>Tanggal</th>
                        <th>No. Penerimaan</th>
                        <th>ID Vendor</th>
                        <th>ID Pengguna</th>
                        <th>Jatuh Tempo</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th style="width:190px">{{ __('views.admin.purchase.invoices.index.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td>{{ $item->no_invoice_beli }}</td>
                            <td>{{ $item->tanggal }}</td>
                            <td>{{ $item->no_penerimaan }}</td>
                            <td>{{ $item->id_vendor }}</td>
                            <td>{{ $item->id_pengguna }}</td>
                            <td>{{ $item->jatuh_tempo }}</td>
                            <td>{{ $item->keterangan }}</td>
                            <td>
                                @switch((int) $item->status)
                                    @case(0)
                                        <span class="label label-danger">Batal</span>
                                        @break
                                    @case(1)
                                        <span class="label label-warning">Belum Dibayar</span>
                                        @break
                                    @case(2)
                                        <span class="label label-info">Dibayar Sebagian</span>
                                        @break
                                    @case(3)
                                        <span class="label label-success">Lunas</span>
                                        @break
                                    @default
                                        <span class="label label-default">{{ $item->status }}</span>
                                @endswitch
                            </td>
                            <td style="white-space: nowrap;">
                                <a class="btn btn-xs btn-info" href="{{ route('admin.purchase.invoices.show', [$item->no_invoice_beli]) }}" title="Lihat"><i class="fa fa-eye"></i></a>
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.purchase.invoices.edit', [$item->no_invoice_beli]) }}" title="Edit"><i class="fa fa-pencil"></i></a>
                                <form action="{{ route('admin.purchase.invoices.destroy', [$item->no_invoice_beli]) }}" method="POST" style="display: inline-block; margin: 0;" onsubmit="return confirm('{{ __('views.admin.purchase.invoices.index.confirm_delete') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-danger" title="Hapus"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="text-center">{{ __('views.admin.purchase.invoices.index.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $items->links() }}
        </div>
    </div>
@endsection
