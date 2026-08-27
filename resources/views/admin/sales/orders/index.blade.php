@extends('admin.layouts.admin')

@section('title', __('views.admin.sales.orders.index.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div style="margin-bottom: 15px;">
                <a class="btn btn-success" href="{{ route('admin.sales.orders.create') }}">{{ __('views.admin.sales.orders.index.create') }}</a>
            </div>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width:15%">No. Order Penjualan</th>
                        <th>Tanggal</th>
                        <th>Pelanggan</th>
                        <th>Sales</th>
                        <th>Jml Item</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th style="width:190px">{{ __('views.admin.sales.orders.index.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td>{{ $item->no_order_penjualan }}</td>
                            <td>{{ $item->tanggal }}</td>
                            <td>{{ $item->pelanggan->nama_pelanggan ?? $item->id_pelanggan }}</td>
                            <td>{{ $item->pengguna->nama_lengkap ?? $item->id_pengguna }}</td>
                            <td>{{ $item->details_count }}</td>
                            <td>{{ $item->keterangan }}</td>
                            <td>
                                @switch((int) $item->status)
                                    @case(0)
                                        <span class="label label-danger">Batal</span>
                                        @break
                                    @case(1)
                                        <span class="label label-default">Aktif/Draft</span>
                                        @break
                                    @case(2)
                                        <span class="label label-info">Diproses</span>
                                        @break
                                    @case(3)
                                        <span class="label label-success">Selesai</span>
                                        @break
                                    @default
                                        <span class="label label-primary">{{ $item->status === '$' ? 'Selesai' : ($item->status ?: 'Aktif') }}</span>
                                @endswitch
                            </td>
                            <td style="white-space: nowrap;">
                                <a class="btn btn-xs btn-info" href="{{ route('admin.sales.orders.show', [$item->no_order_penjualan]) }}" title="Lihat"><i class="fa fa-eye"></i></a>
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.sales.orders.edit', [$item->no_order_penjualan]) }}" title="Edit"><i class="fa fa-pencil"></i></a>
                                <form action="{{ route('admin.sales.orders.destroy', [$item->no_order_penjualan]) }}" method="POST" style="display: inline-block; margin: 0;" onsubmit="return confirm('{{ __('views.admin.sales.orders.index.confirm_delete') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-danger" title="Hapus"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">{{ __('views.admin.sales.orders.index.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $items->links() }}
        </div>
    </div>
@endsection
