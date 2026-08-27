@extends('admin.layouts.admin')

@section('title', __('views.admin.products.create.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            {{ Form::model($product, ['route' => ['admin.products.update', $product->sku], 'method' => 'put', 'class' => 'form-horizontal form-label-left']) }}

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sku">
                        SKU
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="sku" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('sku')) parsley-error @endif"
                               name="sku" value="{{ old('sku', $product->sku) }}" required>
                        @if($errors->has('sku'))
                            <ul class="parsley-errors-list filled">
                                @foreach($errors->get('sku') as $error)
                                    <li class="parsley-required">{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_barang">
                        {{ __('views.admin.products.create.name') }}
                        <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="nama_barang" type="text" class="form-control col-md-7 col-xs-12 @if($errors->has('nama_barang')) parsley-error @endif"
                               name="nama_barang" value="{{ old('nama_barang', $product->nama_barang) }}" required>
                        @if($errors->has('nama_barang'))
                            <ul class="parsley-errors-list filled">
                                @foreach($errors->get('nama_barang') as $error)
                                    <li class="parsley-required">{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="category_id">
                        Kategori Produk
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="category_id" name="category_id" class="form-control col-md-7 col-xs-12">
                            <option value="">-- Tanpa Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ (string) old('category_id', $product->category_id) === (string) $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @if($errors->has('category_id'))
                            <ul class="parsley-errors-list filled">
                                @foreach($errors->get('category_id') as $error)
                                    <li class="parsley-required">{{ $error }}</li>
                                @endforeach
                            </ul>
                        @endif
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="keterangan">
                        {{ __('views.admin.products.create.description') }}
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <textarea id="keterangan" class="form-control" name="keterangan">{{ old('keterangan', $product->keterangan) }}</textarea>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">
                        {{ __('views.admin.products.create.status') }}
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="status" name="status" class="form-control">
                            <option value="1" {{ old('status', $product->status) == 1 ? 'selected' : '' }}>{{ __('views.admin.products.index.active') }}</option>
                            <option value="0" {{ old('status', $product->status) == 0 ? 'selected' : '' }}>{{ __('views.admin.products.index.inactive') }}</option>
                        </select>
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a class="btn btn-primary" href="{{ URL::previous() }}">{{ __('views.admin.products.create.cancel') }}</a>
                        <button type="submit" class="btn btn-success">{{ __('views.admin.products.create.save') }}</button>
                    </div>
                </div>
            {{ Form::close() }}
        </div>
    </div>
@endsection