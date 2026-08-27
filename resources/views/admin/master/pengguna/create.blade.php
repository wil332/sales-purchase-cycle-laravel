@extends('admin.layouts.admin')

@section('title', __('views.admin.master.pengguna.create.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <form action="{{ route('admin.master.pengguna.store') }}" method="POST" class="form-horizontal form-label-left">
                @csrf

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_lengkap">Nama Lengkap <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="nama_lengkap" type="text" class="form-control" name="nama_lengkap" value="{{ old('nama_lengkap') }}" required>
                        @error('nama_lengkap') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Username <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="username" type="text" class="form-control" name="username" value="{{ old('username') }}" required>
                        @error('username') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Password <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="password" type="password" class="form-control" name="password" required>
                        @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jabatan">Jabatan <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="jabatan" name="jabatan" class="form-control" required>
                            <option value="" disabled {{ old('jabatan') ? '' : 'selected' }}>-- Pilih Jabatan --</option>
                            <option value="gudang" {{ old('jabatan') === 'gudang' ? 'selected' : '' }}>Gudang</option>
                            <option value="purchasing" {{ old('jabatan') === 'purchasing' ? 'selected' : '' }}>Purchasing</option>
                            <option value="sales" {{ old('jabatan') === 'sales' ? 'selected' : '' }}>Sales</option>
                            <option value="manajer" {{ old('jabatan') === 'manajer' ? 'selected' : '' }}>Manajer</option>
                            <option value="admin" {{ old('jabatan') === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('jabatan') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="keterangan">Keterangan</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <textarea id="keterangan" class="form-control" name="keterangan">{{ old('keterangan') }}</textarea>
                        @error('keterangan') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="status" name="status" class="form-control" required>
                            <option value="1" {{ (string) old('status', '1') === '1' ? "selected" : "" }}>Aktif</option>
                            <option value="0" {{ (string) old('status', '1') === '0' ? "selected" : "" }}>Nonaktif</option>
                        </select>
                        @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a class="btn btn-primary" href="{{ route('admin.master.pengguna.index') }}">{{ __('views.admin.master.pengguna.create.cancel') }}</a>
                        <button type="submit" class="btn btn-success">{{ __('views.admin.master.pengguna.create.save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
