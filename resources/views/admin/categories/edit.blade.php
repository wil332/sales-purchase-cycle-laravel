@extends('admin.layouts.admin')

@section('title', 'Edit Kategori Produk')

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel" style="border-radius: 12px; border: 1px solid #e9edf2;">
            <div class="x_title">
                <h2><i class="fa fa-pencil text-info"></i> Edit Kategori #{{ $category->id }}</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form action="{{ route('admin.categories.update', $category->id) }}" method="POST" class="form-horizontal form-label-left">
                    @csrf
                    @method('PUT')

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                            Nama Kategori <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name', $category->name) }}" required>
                            @error('name') <div class="text-danger" style="margin-top:4px;"><strong>{{ $message }}</strong></div> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Deskripsi</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea id="description" class="form-control" name="description" rows="4">{{ old('description', $category->description) }}</textarea>
                            @error('description') <div class="text-danger" style="margin-top:4px;"><strong>{{ $message }}</strong></div> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <a class="btn btn-primary" href="{{ route('admin.categories.index') }}">Batal</a>
                            <button type="submit" class="btn btn-success">Perbarui Kategori</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
