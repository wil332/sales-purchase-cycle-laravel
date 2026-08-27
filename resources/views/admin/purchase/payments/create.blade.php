@extends('admin.layouts.admin')

@section('title', __('views.admin.purchase.payments.create.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <form action="{{ route('admin.purchase.payments.store') }}" method="POST" class="form-horizontal form-label-left">
                @csrf

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_pembayaran_beli">No. Bukti Pembayaran <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="input-group">
                            <input id="no_pembayaran_beli" type="text" class="form-control" name="no_pembayaran_beli" value="{{ old('no_pembayaran_beli', $nextNo ?? '') }}" required>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default" onclick="document.getElementById('no_pembayaran_beli').value = 'PPAY-' + new Date().getFullYear() + String(new Date().getMonth() + 1).padStart(2, '0') + '-' + Math.floor(1000 + Math.random() * 9000);" title="Generate Nomor Baru">
                                    <i class="fa fa-refresh"></i> Acak
                                </button>
                            </span>
                        </div>
                        <small class="text-muted"><i class="fa fa-info-circle"></i> Otomatis digenerate sistem.</small>
                        @error('no_pembayaran_beli') <div class="text-danger"><strong>{{ $message }}</strong></div> @enderror
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
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_invoice_beli">Invoice Utama <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="no_invoice_beli" name="no_invoice_beli" class="form-control" required>
                            <option value="">-- Pilih Invoice --</option>
                            @foreach($invoiceList as $invoice)
                                <option value="{{ $invoice->no_invoice_beli }}" {{ (string) old('no_invoice_beli') === (string) $invoice->no_invoice_beli ? 'selected' : '' }}>
                                    {{ $invoice->no_invoice_beli }}
                                </option>
                            @endforeach
                        </select>
                        @error('no_invoice_beli') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="id_pengguna">Dicatat Oleh</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="id_pengguna" name="id_pengguna" class="form-control">
                            <option value="">-- Pilih Pengguna --</option>
                            @foreach($penggunaList as $pengguna)
                                <option value="{{ $pengguna->id_pengguna }}" {{ (string) old('id_pengguna') === (string) $pengguna->id_pengguna ? 'selected' : '' }}>
                                    {{ $pengguna->nama_lengkap }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_pengguna') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jumlah_bayar">Jumlah Bayar <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="jumlah_bayar" type="number" step="0.01" min="0" class="form-control" name="jumlah_bayar" value="{{ old('jumlah_bayar') }}" required>
                        @error('jumlah_bayar') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="metode_bayar">Metode Bayar <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="metode_bayar" name="metode_bayar" class="form-control" required>
                            <option value="tunai" {{ old('metode_bayar') === 'tunai' ? 'selected' : '' }}>Tunai</option>
                            <option value="transfer" {{ old('metode_bayar') === 'transfer' ? 'selected' : '' }}>Transfer</option>
                            <option value="cek" {{ old('metode_bayar') === 'cek' ? 'selected' : '' }}>Cek</option>
                        </select>
                        @error('metode_bayar') <span class="text-danger">{{ $message }}</span> @enderror
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
                            <option value="0" {{ (string) old('status') === '0' ? 'selected' : '' }}>Batal</option>
                            <option value="1" {{ (string) old('status', '1') === '1' ? 'selected' : '' }}>Valid/Sukses</option>
                        </select>
                        @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <hr>
                <h4 class="col-md-12">Alokasi ke Invoice</h4>
                <p class="col-md-12 text-muted">Opsional. Isi baris ini kalau satu pembayaran ini dipecah/dialokasikan ke lebih dari satu invoice. Kalau dikosongkan, seluruh jumlah bayar otomatis dialokasikan ke Invoice Utama di atas.</p>

                <div class="col-md-12" style="margin-bottom: 15px;">
                    <table class="table table-bordered" id="detail-table">
                        <thead>
                            <tr>
                                <th style="width:35%">No. Invoice</th>
                                <th style="width:20%">Jumlah Dialokasikan</th>
                                <th>Keterangan</th>
                                <th style="width:60px">Hapus</th>
                            </tr>
                        </thead>
                        <tbody id="detail-rows">
                            @php $rows = old('details', []); @endphp
                            @foreach($rows as $i => $row)
                                <tr>
                                    <td>
                                        <select name="details[{{ $i }}][no_invoice_beli]" class="form-control">
                                            <option value="">-- Pilih Invoice --</option>
                                            @foreach($invoiceList as $invoice)
                                                <option value="{{ $invoice->no_invoice_beli }}" {{ ($row['no_invoice_beli'] ?? '') == $invoice->no_invoice_beli ? 'selected' : '' }}>
                                                    {{ $invoice->no_invoice_beli }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="number" name="details[{{ $i }}][jumlah_dialokasikan]" class="form-control" step="0.01" min="0" value="{{ $row['jumlah_dialokasikan'] ?? '' }}"></td>
                                    <td><input type="text" name="details[{{ $i }}][keterangan]" class="form-control" value="{{ $row['keterangan'] ?? '' }}"></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-xs btn-danger btn-remove-row">&times;</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-sm btn-info" id="btn-add-row">+ Tambah Alokasi</button>
                    @error('details') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a class="btn btn-primary" href="{{ route('admin.purchase.payments.index') }}">{{ __('views.admin.purchase.payments.create.cancel') }}</a>
                        <button type="submit" class="btn btn-success">{{ __('views.admin.purchase.payments.create.save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <table style="display:none;">
        <tbody id="row-template">
            <tr>
                <td>
                    <select name="details[__INDEX__][no_invoice_beli]" class="form-control">
                        <option value="">-- Pilih Invoice --</option>
                        @foreach($invoiceList as $invoice)
                            <option value="{{ $invoice->no_invoice_beli }}">{{ $invoice->no_invoice_beli }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" name="details[__INDEX__][jumlah_dialokasikan]" class="form-control" step="0.01" min="0"></td>
                <td><input type="text" name="details[__INDEX__][keterangan]" class="form-control"></td>
                <td class="text-center"><button type="button" class="btn btn-xs btn-danger btn-remove-row">&times;</button></td>
            </tr>
        </tbody>
    </table>

    <script>
        (function () {
            var rowIndex = {{ count(old('details', [])) }};

            document.getElementById('btn-add-row').addEventListener('click', function () {
                var templateRow = document.querySelector('#row-template tr').cloneNode(true);
                templateRow.innerHTML = templateRow.innerHTML.replaceAll('__INDEX__', rowIndex);
                document.getElementById('detail-rows').appendChild(templateRow);
                rowIndex++;
            });

            document.getElementById('detail-rows').addEventListener('click', function (e) {
                if (e.target.classList.contains('btn-remove-row')) {
                    e.target.closest('tr').remove();
                }
            });

            var invoiceSelect = document.getElementById('no_invoice_beli');
            var jumlahInput = document.getElementById('jumlah_bayar');

            if (invoiceSelect && jumlahInput) {
                invoiceSelect.addEventListener('change', function () {
                    var invoiceNo = this.value;
                    if (!invoiceNo) return;

                    fetch('/admin/purchase/invoices/' + invoiceNo + '/amount-json')
                        .then(function (res) { return res.json(); })
                        .then(function (data) {
                            if (data && data.sisa_tagihan !== undefined) {
                                jumlahInput.value = data.sisa_tagihan;
                            }
                        })
                        .catch(function (err) {
                            console.error('Error fetching invoice amount:', err);
                        });
                });
            }
        })();
    </script>
@endsection
