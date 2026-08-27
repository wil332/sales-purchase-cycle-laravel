@extends('admin.layouts.admin')

@section('title', __('views.admin.master.pelanggan.index.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div style="margin-bottom: 15px;">
                <a class="btn btn-success" href="{{ route('admin.master.pelanggan.create') }}">{{ __('views.admin.master.pelanggan.index.create') }}</a>
            </div>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width:15%">ID</th>
                        <th>Nama Pelanggan</th>
                        <th>No. Telepon</th>
                        <th>Alamat</th>
                        <th>Status</th>
                        <th style="width:190px">{{ __('views.admin.master.pelanggan.index.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td>{{ $item->id_pelanggan }}</td>
                            <td>{{ $item->nama_pelanggan }}</td>
                            <td>{{ $item->no_telp }}</td>
                            <td>{{ $item->alamat }}</td>
                            <td>
                                @switch((int) $item->status)
                                    @case(0)
                                        <span class="label label-danger">Tidak Aktif</span>
                                        @break
                                    @case(1)
                                        <span class="label label-success">Aktif</span>
                                        @break
                                    @default
                                        <span class="label label-default">{{ $item->status }}</span>
                                @endswitch
                            </td>
                            <td style="white-space: nowrap;">
                                <a class="btn btn-xs btn-info" href="{{ route('admin.master.pelanggan.show', [$item->id_pelanggan]) }}" title="Lihat"><i class="fa fa-eye"></i></a>
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.master.pelanggan.edit', [$item->id_pelanggan]) }}" title="Edit"><i class="fa fa-pencil"></i></a>
                                <form action="{{ route('admin.master.pelanggan.destroy', [$item->id_pelanggan]) }}" method="POST" style="display: inline-block; margin: 0;" onsubmit="return confirm('{{ __('views.admin.master.pelanggan.index.confirm_delete') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-danger" title="Hapus"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">{{ __('views.admin.master.pelanggan.index.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $items->links() }}
        </div>
    </div>
@endsection
