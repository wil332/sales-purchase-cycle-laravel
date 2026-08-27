@extends('admin.layouts.admin')

@section('title', __('views.admin.products.index.show'))

@section('content')
    <div class="row">
        <div class="col-md-12">
            <table class="table table-bordered">
                <tr>
                    <th>{{ __('views.admin.products.index.table_header_0') }}</th>
                    <td>{{ $product->sku }}</td>
                </tr>
                <tr>
                    <th>{{ __('views.admin.products.index.table_header_1') }}</th>
                    <td>{{ $product->nama_barang }}</td>
                </tr>
                <tr>
                    <th>{{ __('views.admin.products.index.table_header_2') }}</th>
                    <td>{{ $product->keterangan }}</td>
                </tr>
                <tr>
                    <th>{{ __('views.admin.products.index.table_header_3') }}</th>
                    <td>
                        @if($product->status)
                            <span class="label label-primary">{{ __('views.admin.products.index.active') }}</span>
                        @else
                            <span class="label label-danger">{{ __('views.admin.products.index.inactive') }}</span>
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>{{ __('views.admin.products.index.table_header_4') }}</th>
                    <td>{{ $product->created_at }}</td>
                </tr>
                <tr>
                    <th>{{ __('views.admin.products.index.table_header_5') }}</th>
                    <td>{{ $product->updated_at }}</td>
                </tr>
            </table>

            <a class="btn btn-primary" href="{{ route('admin.products.index') }}">
                {{ __('views.admin.products.create.cancel') }}
            </a>
        </div>
    </div>
@endsection