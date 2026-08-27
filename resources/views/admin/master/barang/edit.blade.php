@extends('admin.layouts.admin')

@section('title', __('views.admin.master.barang.edit.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <form action="{{ route('admin.master.barang.update', [$item->sku]) }}" method="POST" class="form-horizontal form-label-left">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sku">SKU</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="sku" type="text" class="form-control" value="{{ $item->sku }}" disabled>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_barang">Nama Barang <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="nama_barang" type="text" class="form-control" name="nama_barang" value="{{ old('nama_barang', $item->nama_barang) }}" required>
                        @error('nama_barang') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="category_id">Kategori Produk</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="category_id" name="category_id" class="form-control">
                            <option value="">-- Tanpa Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ (string) old('category_id', $item->category_id) === (string) $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="keterangan">Keterangan</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <textarea id="keterangan" class="form-control" name="keterangan">{{ old('keterangan', $item->keterangan) }}</textarea>
                        @error('keterangan') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="status" name="status" class="form-control" required>
                            <option value="1" {{ (string) old('status', $item->status) === '1' ? "selected" : "" }}>Aktif</option>
                            <option value="0" {{ (string) old('status', $item->status) === '0' ? "selected" : "" }}>Tidak Aktif</option>
                        </select>
                        @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a class="btn btn-primary" href="{{ route('admin.master.barang.index') }}">{{ __('views.admin.master.barang.edit.cancel') }}</a>
                        <button type="submit" class="btn btn-success">{{ __('views.admin.master.barang.edit.save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
