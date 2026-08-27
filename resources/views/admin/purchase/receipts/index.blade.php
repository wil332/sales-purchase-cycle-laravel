@extends('admin.layouts.admin')

@section('title', __('views.admin.purchase.receipts.index.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div style="margin-bottom: 15px;">
                <a class="btn btn-success" href="{{ route('admin.purchase.receipts.create') }}">{{ __('views.admin.purchase.receipts.index.create') }}</a>
            </div>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width:15%">No. Penerimaan</th>
                        <th>Tanggal Penerimaan</th>
                        <th>No. Order Pembelian</th>
                        <th>ID Pengguna</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th style="width:190px">{{ __('views.admin.purchase.receipts.index.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td>{{ $item->no_penerimaan }}</td>
                            <td>{{ $item->tanggal_penerimaan }}</td>
                            <td>{{ $item->no_order_pembelian }}</td>
                            <td>{{ $item->id_pengguna }}</td>
                            <td>{{ $item->keterangan }}</td>
                            <td>
                                @switch((int) $item->status)
                                    @case(0)
                                        <span class="label label-danger">Masalah</span>
                                        @break
                                    @case(1)
                                        <span class="label label-success">Diterima</span>
                                        @break
                                    @default
                                        <span class="label label-default">{{ $item->status }}</span>
                                @endswitch
                            </td>
                            <td style="white-space: nowrap;">
                                <a class="btn btn-xs btn-info" href="{{ route('admin.purchase.receipts.show', [$item->no_penerimaan]) }}" title="Lihat"><i class="fa fa-eye"></i></a>
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.purchase.receipts.edit', [$item->no_penerimaan]) }}" title="Edit"><i class="fa fa-pencil"></i></a>
                                <form action="{{ route('admin.purchase.receipts.destroy', [$item->no_penerimaan]) }}" method="POST" style="display: inline-block; margin: 0;" onsubmit="return confirm('{{ __('views.admin.purchase.receipts.index.confirm_delete') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-danger" title="Hapus"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-center">{{ __('views.admin.purchase.receipts.index.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $items->links() }}
        </div>
    </div>
@endsection
