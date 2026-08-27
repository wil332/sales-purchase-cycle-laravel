@extends('admin.layouts.admin')

@section('title', __('views.admin.master.pelanggan.edit.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <form action="{{ route('admin.master.pelanggan.update', [$item->id_pelanggan]) }}" method="POST" class="form-horizontal form-label-left">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_pelanggan">Nama Pelanggan <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="nama_pelanggan" type="text" class="form-control" name="nama_pelanggan" value="{{ old('nama_pelanggan', $item->nama_pelanggan) }}" required>
                        @error('nama_pelanggan') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_telp">No. Telepon</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="no_telp" type="text" class="form-control" name="no_telp" value="{{ old('no_telp', $item->no_telp) }}">
                        @error('no_telp') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="alamat">Alamat</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <textarea id="alamat" class="form-control" name="alamat">{{ old('alamat', $item->alamat) }}</textarea>
                        @error('alamat') <span class="text-danger">{{ $message }}</span> @enderror
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
                        <a class="btn btn-primary" href="{{ route('admin.master.pelanggan.index') }}">{{ __('views.admin.master.pelanggan.edit.cancel') }}</a>
                        <button type="submit" class="btn btn-success">{{ __('views.admin.master.pelanggan.edit.save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
