@extends('admin.layouts.admin')

@section('title', __('views.admin.master.pengguna.index.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div style="margin-bottom: 15px;">
                <a class="btn btn-success" href="{{ route('admin.master.pengguna.create') }}">{{ __('views.admin.master.pengguna.index.create') }}</a>
            </div>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width:15%">ID</th>
                        <th>Nama Lengkap</th>
                        <th>Username</th>
                        <th>Jabatan</th>
                        <th>Status</th>
                        <th style="width:190px">{{ __('views.admin.master.pengguna.index.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td>{{ $item->id_pengguna }}</td>
                            <td>{{ $item->nama_lengkap }}</td>
                            <td>{{ $item->username }}</td>
                            <td>{{ $item->jabatan }}</td>
                            <td>
                                @switch((int) $item->status)
                                    @case(0)
                                        <span class="label label-danger">Nonaktif</span>
                                        @break
                                    @case(1)
                                        <span class="label label-success">Aktif</span>
                                        @break
                                    @default
                                        <span class="label label-default">{{ $item->status }}</span>
                                @endswitch
                            </td>
                            <td style="white-space: nowrap;">
                                <a class="btn btn-xs btn-info" href="{{ route('admin.master.pengguna.show', [$item->id_pengguna]) }}" title="Lihat"><i class="fa fa-eye"></i></a>
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.master.pengguna.edit', [$item->id_pengguna]) }}" title="Edit"><i class="fa fa-pencil"></i></a>
                                <form action="{{ route('admin.master.pengguna.destroy', [$item->id_pengguna]) }}" method="POST" style="display: inline-block; margin: 0;" onsubmit="return confirm('{{ __('views.admin.master.pengguna.index.confirm_delete') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-danger" title="Hapus"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">{{ __('views.admin.master.pengguna.index.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $items->links() }}
        </div>
    </div>
@endsection
