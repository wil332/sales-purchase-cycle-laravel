@extends('admin.layouts.admin')

@section('title', 'Tambah Kategori Produk Baru')

@section('content')
<div class="row">
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="x_panel" style="border-radius: 12px; border: 1px solid #e9edf2;">
            <div class="x_title">
                <h2><i class="fa fa-plus-circle text-success"></i> Form Tambah Kategori</h2>
                <div class="clearfix"></div>
            </div>
            <div class="x_content">
                <form action="{{ route('admin.categories.store') }}" method="POST" class="form-horizontal form-label-left">
                    @csrf

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="name">
                            Nama Kategori <span class="required">*</span>
                        </label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" placeholder="Contoh: Elektronik / Pakaian" required>
                            @error('name') <div class="text-danger" style="margin-top:4px;"><strong>{{ $message }}</strong></div> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="description">Deskripsi</label>
                        <div class="col-md-6 col-sm-6 col-xs-12">
                            <textarea id="description" class="form-control" name="description" rows="4" placeholder="Keterangan singkat tentang kelompok kategori ini...">{{ old('description') }}</textarea>
                            @error('description') <div class="text-danger" style="margin-top:4px;"><strong>{{ $message }}</strong></div> @enderror
                        </div>
                    </div>

                    <div class="form-group">
                        <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                            <a class="btn btn-primary" href="{{ route('admin.categories.index') }}">Batal</a>
                            <button type="submit" class="btn btn-success">Simpan Kategori</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
