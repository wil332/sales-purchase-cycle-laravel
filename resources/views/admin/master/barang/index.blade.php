@extends('admin.layouts.admin')

@section('title', __('views.admin.master.barang.index.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div style="margin-bottom: 15px;">
                <a class="btn btn-success" href="{{ route('admin.master.barang.create') }}">{{ __('views.admin.master.barang.index.create') }}</a>
            </div>

            <table class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th style="width: 60px;">Foto</th>
                        <th style="width:15%">SKU</th>
                        <th>Nama Barang</th>
                        <th>Keterangan</th>
                        <th>Status</th>
                        <th style="width:190px">{{ __('views.admin.master.barang.index.actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($items as $item)
                        <tr>
                            <td class="text-center" style="vertical-align: middle;">
                                <img src="{{ $item->gambar_url }}" alt="{{ $item->nama_barang }}" class="img-thumbnail" style="width: 44px; height: 44px; object-fit: cover; border-radius: 8px; border: 1px solid #cbd5e1; box-shadow: 0 1px 3px rgba(0,0,0,0.08);">
                            </td>
                            <td style="vertical-align: middle;">{{ $item->sku }}</td>
                            <td style="vertical-align: middle;">{{ $item->nama_barang }}</td>
                            <td style="vertical-align: middle;">{{ $item->keterangan }}</td>
                            <td style="vertical-align: middle;">
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
                            <td style="vertical-align: middle; white-space: nowrap;">
                                <a class="btn btn-xs btn-info" href="{{ route('admin.master.barang.show', [$item->sku]) }}" title="Lihat"><i class="fa fa-eye"></i></a>
                                <a class="btn btn-xs btn-primary" href="{{ route('admin.master.barang.edit', [$item->sku]) }}" title="Edit"><i class="fa fa-pencil"></i></a>
                                <form action="{{ route('admin.master.barang.destroy', [$item->sku]) }}" method="POST" style="display: inline-block; margin: 0;" onsubmit="return confirm('{{ __('views.admin.master.barang.index.confirm_delete') }}')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-xs btn-danger" title="Hapus"><i class="fa fa-trash"></i></button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-center">{{ __('views.admin.master.barang.index.empty') }}</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

            {{ $items->links() }}
        </div>
    </div>
@endsection
