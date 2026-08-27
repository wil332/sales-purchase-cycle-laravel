@extends('admin.layouts.admin')

@section('title', __('views.admin.sales.payments.edit.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <form action="{{ route('admin.sales.payments.update', [$item->no_pembayaran]) }}" method="POST" class="form-horizontal form-label-left">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">No. Pembayaran</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" class="form-control" value="{{ $item->no_pembayaran }}" disabled>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tanggal_bayar">Tanggal Bayar <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="tanggal_bayar" type="date" class="form-control" name="tanggal_bayar" value="{{ old('tanggal_bayar', $item->tanggal_bayar) }}" required>
                        @error('tanggal_bayar') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_invoice_jual">No. Invoice <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="no_invoice_jual" type="text" class="form-control" name="no_invoice_jual" value="{{ old('no_invoice_jual', $item->no_invoice_jual) }}" required>
                        @error('no_invoice_jual') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jumlah_bayar">Jumlah Bayar <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="jumlah_bayar" type="number" step="any" class="form-control" name="jumlah_bayar" value="{{ old('jumlah_bayar', $item->jumlah_bayar) }}" required>
                        @error('jumlah_bayar') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="metode_bayar">Metode Bayar <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="metode_bayar" name="metode_bayar" class="form-control" required>
                            <option value="tunai" {{ old('metode_bayar', $item->metode_bayar) === 'tunai' ? "selected" : "" }}>Tunai</option>
                            <option value="transfer" {{ old('metode_bayar', $item->metode_bayar) === 'transfer' ? "selected" : "" }}>Transfer</option>
                            <option value="cek" {{ old('metode_bayar', $item->metode_bayar) === 'cek' ? "selected" : "" }}>Cek</option>
                        </select>
                        @error('metode_bayar') <span class="text-danger">{{ $message }}</span> @enderror
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
                            <option value="0" {{ (string) old('status', $item->status) === '0' ? "selected" : "" }}>Batal</option>
                            <option value="1" {{ (string) old('status', $item->status) === '1' ? "selected" : "" }}>Valid/Sukses</option>
                        </select>
                        @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a class="btn btn-primary" href="{{ route('admin.sales.payments.index') }}">{{ __('views.admin.sales.payments.edit.cancel') }}</a>
                        <button type="submit" class="btn btn-success">{{ __('views.admin.sales.payments.edit.save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
