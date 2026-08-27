@extends('admin.layouts.admin')

@section('title', __('views.admin.purchase.returns.index.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div style="margin-bottom: 15px;">
                <a class="btn btn-success" href="{{ route('admin.purchase.returns.create') }}">{{ __('views.admin.purchase.returns.index.create') }}</a>
            </div>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width:15%">No. Retur</th>
                        <th>Nomor Urut</th>
                        <th>Referensi No. Penerimaan</th>
                        <th>Tanggal</th>
                        <th>ID Vendor</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th style="width:190px">{{ __('views.admin.purchase.returns.index.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td>{{ $item->nota_retur }}</td>
                            <td>{{ $item->nomor_urut }}</td>
                            <td>{{ $item->atas_penerimaan_nomor }}</td>
                            <td>{{ $item->tanggal }}</td>
                            <td>{{ $item->id_penjual }}</td>
                            <td>{{ $item->keterangan }}</td>
                            <td>
                                @switch((int) $item->status)
                                    @case(0)
                                        <span class="label label-danger">Batal</span>
                                        @break
                                    @case(1)
                                        <span class="label label-success">Aktif</span>
                                        @break
                                    @default
                                        <span class="label label-default">{{ $item->status }}</span>
                                @endswitch
                            </td>
                            <td style="white-space: nowrap;">
                                <a class="btn btn-xs btn-info" href="{{ route('admin.purchase.returns.show', [$item->nota_retur]) }}" title="Lihat"><i class="fa fa-eye"></i></a>
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.purchase.returns.edit', [$item->nota_retur]) }}" title="Edit"><i class="fa fa-pencil"></i></a>
                                <form action="{{ route('admin.purchase.returns.destroy', [$item->nota_retur]) }}" method="POST" style="display: inline-block; margin: 0;" onsubmit="return confirm('{{ __('views.admin.purchase.returns.index.confirm_delete') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-danger" title="Hapus"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center">{{ __('views.admin.purchase.returns.index.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $items->links() }}
        </div>
    </div>
@endsection
