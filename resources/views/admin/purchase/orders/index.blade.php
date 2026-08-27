@extends('admin.layouts.admin')

@section('title', __('views.admin.purchase.orders.index.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div style="margin-bottom: 15px;">
                <a class="btn btn-success" href="{{ route('admin.purchase.orders.create') }}">{{ __('views.admin.purchase.orders.index.create') }}</a>
            </div>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width:15%">No. Order Pembelian</th>
                        <th>Tanggal</th>
                        <th>Vendor / Pemasok</th>
                        <th>Petugas</th>
                        <th>Tanggal Dibutuhkan</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th style="width:190px">{{ __('views.admin.purchase.orders.index.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td>{{ $item->no_order_pembelian }}</td>
                            <td>{{ optional($item->tanggal)->format('d M Y') ?: $item->tanggal }}</td>
                            <td>{{ $item->vendor->nama_vendor ?? '-' }}</td>
                            <td>{{ $item->pengguna->nama_lengkap ?? '-' }}</td>
                            <td>{{ $item->tanggal_dibutuhkan }}</td>
                            <td>{{ $item->keterangan }}</td>
                            <td>
                                @switch((int) $item->status)
                                    @case(0)
                                        <span class="label label-danger">Batal</span>
                                        @break
                                    @case(1)
                                        <span class="label label-success">Aktif</span>
                                        @break
                                    @case(2)
                                        <span class="label label-info">Diproses</span>
                                        @break
                                    @default
                                        <span class="label label-default">{{ $item->status }}</span>
                                @endswitch
                            </td>
                            <td style="white-space: nowrap;">
                                <a class="btn btn-xs btn-info" href="{{ route('admin.purchase.orders.show', [$item->no_order_pembelian]) }}" title="Lihat"><i class="fa fa-eye"></i></a>
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.purchase.orders.edit', [$item->no_order_pembelian]) }}" title="Edit"><i class="fa fa-pencil"></i></a>
                                <form action="{{ route('admin.purchase.orders.destroy', [$item->no_order_pembelian]) }}" method="POST" style="display: inline-block; margin: 0;" onsubmit="return confirm('{{ __('views.admin.purchase.orders.index.confirm_delete') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-danger" title="Hapus"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">{{ __('views.admin.purchase.orders.index.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $items->links() }}
        </div>
    </div>
@endsection
