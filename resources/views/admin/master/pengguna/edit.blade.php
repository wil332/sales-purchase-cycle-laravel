@extends('admin.layouts.admin')

@section('title', __('views.admin.master.pengguna.edit.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <form action="{{ route('admin.master.pengguna.update', [$item->id_pengguna]) }}" method="POST" class="form-horizontal form-label-left">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_lengkap">Nama Lengkap <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="nama_lengkap" type="text" class="form-control" name="nama_lengkap" value="{{ old('nama_lengkap', $item->nama_lengkap) }}" required>
                        @error('nama_lengkap') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="username">Username <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="username" type="text" class="form-control" name="username" value="{{ old('username', $item->username) }}" required>
                        @error('username') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="password">Password</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="password" type="password" class="form-control" name="password" placeholder="Kosongkan jika tidak ingin mengubah password">
                        @error('password') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jabatan">Jabatan <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="jabatan" name="jabatan" class="form-control" required>
                            <option value="gudang" {{ old('jabatan', $item->jabatan) === 'gudang' ? 'selected' : '' }}>Gudang</option>
                            <option value="purchasing" {{ old('jabatan', $item->jabatan) === 'purchasing' ? 'selected' : '' }}>Purchasing</option>
                            <option value="sales" {{ old('jabatan', $item->jabatan) === 'sales' ? 'selected' : '' }}>Sales</option>
                            <option value="manajer" {{ old('jabatan', $item->jabatan) === 'manajer' ? 'selected' : '' }}>Manajer</option>
                            <option value="admin" {{ old('jabatan', $item->jabatan) === 'admin' ? 'selected' : '' }}>Admin</option>
                        </select>
                        @error('jabatan') <span class="text-danger">{{ $message }}</span> @enderror
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
                            <option value="0" {{ (string) old('status', $item->status) === '0' ? "selected" : "" }}>Nonaktif</option>
                        </select>
                        @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a class="btn btn-primary" href="{{ route('admin.master.pengguna.index') }}">{{ __('views.admin.master.pengguna.edit.cancel') }}</a>
                        <button type="submit" class="btn btn-success">{{ __('views.admin.master.pengguna.edit.save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
