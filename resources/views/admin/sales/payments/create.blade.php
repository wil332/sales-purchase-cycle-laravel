@extends('admin.layouts.admin')

@section('title', __('views.admin.sales.payments.create.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <form action="{{ route('admin.sales.payments.store') }}" method="POST" class="form-horizontal form-label-left">
                @csrf

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_pembayaran">No. Bukti Pembayaran <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="input-group">
                            <input id="no_pembayaran" type="text" class="form-control" name="no_pembayaran" value="{{ old('no_pembayaran', $nextNo ?? '') }}" required>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default" onclick="document.getElementById('no_pembayaran').value = 'SPAY-' + new Date().getFullYear() + String(new Date().getMonth() + 1).padStart(2, '0') + '-' + Math.floor(1000 + Math.random() * 9000);" title="Generate Nomor Baru">
                                    <i class="fa fa-refresh"></i> Acak
                                </button>
                            </span>
                        </div>
                        <small class="text-muted"><i class="fa fa-info-circle"></i> Otomatis digenerate sistem.</small>
                        @error('no_pembayaran') <div class="text-danger"><strong>{{ $message }}</strong></div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tanggal_bayar">Tanggal Bayar <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="tanggal_bayar" type="date" class="form-control" name="tanggal_bayar" value="{{ old('tanggal_bayar', date('Y-m-d')) }}" required>
                        @error('tanggal_bayar') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_invoice_jual">Pilih Faktur Penjualan <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        @if(!empty($invoiceList) && $invoiceList->count() > 0)
                            <select id="no_invoice_jual" name="no_invoice_jual" class="form-control" required>
                                <option value="">-- Pilih Faktur Penjualan --</option>
                                @foreach($invoiceList as $inv)
                                    <option value="{{ $inv->no_invoice_jual }}" {{ (string) old('no_invoice_jual') === (string) $inv->no_invoice_jual ? 'selected' : '' }}>
                                        {{ $inv->no_invoice_jual }} - Pelanggan: {{ $inv->pelanggan->nama_pelanggan ?? 'Umum' }} (Tgl: {{ optional($inv->tanggal)->format('d/m/Y') }})
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <input id="no_invoice_jual" type="text" class="form-control" name="no_invoice_jual" value="{{ old('no_invoice_jual', null) }}" placeholder="Contoh: SINV-202608-0001" required>
                        @endif
                        @error('no_invoice_jual') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jumlah_bayar">Jumlah Bayar <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="jumlah_bayar" type="number" step="any" class="form-control" name="jumlah_bayar" value="{{ old('jumlah_bayar', null) }}" required>
                        @error('jumlah_bayar') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="metode_bayar">Metode Bayar <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="metode_bayar" name="metode_bayar" class="form-control" required>
                            <option value="tunai" {{ old('metode_bayar', null) === 'tunai' ? "selected" : "" }}>Tunai</option>
                            <option value="transfer" {{ old('metode_bayar', null) === 'transfer' ? "selected" : "" }}>Transfer</option>
                            <option value="cek" {{ old('metode_bayar', null) === 'cek' ? "selected" : "" }}>Cek</option>
                        </select>
                        @error('metode_bayar') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="keterangan">Keterangan</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <textarea id="keterangan" class="form-control" name="keterangan">{{ old('keterangan', null) }}</textarea>
                        @error('keterangan') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="status" name="status" class="form-control" required>
                            <option value="0" {{ (string) old('status', null) === '0' ? "selected" : "" }}>Batal</option>
                            <option value="1" {{ (string) old('status', null) === '1' ? "selected" : "" }}>Valid/Sukses</option>
                        </select>
                        @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a class="btn btn-primary" href="{{ route('admin.sales.payments.index') }}">{{ __('views.admin.sales.payments.create.cancel') }}</a>
                        <button type="submit" class="btn btn-success">{{ __('views.admin.sales.payments.create.save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var invoiceSelect = document.getElementById('no_invoice_jual');
            var jumlahInput = document.getElementById('jumlah_bayar');

            if (invoiceSelect && invoiceSelect.tagName.toLowerCase() === 'select' && jumlahInput) {
                invoiceSelect.addEventListener('change', function () {
                    var invoiceNo = this.value;
                    if (!invoiceNo) return;

                    fetch('/admin/sales/invoices/' + invoiceNo + '/amount-json')
                        .then(function (res) { return res.json(); })
                        .then(function (data) {
                            if (data && data.sisa_tagihan !== undefined) {
                                jumlahInput.value = data.sisa_tagihan;
                            }
                        })
                        .catch(function (err) {
                            console.error('Error fetching sales invoice amount:', err);
                        });
                });
            }
        });
    </script>
@endsection
