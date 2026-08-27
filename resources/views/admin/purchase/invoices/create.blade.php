@extends('admin.layouts.admin')

@section('title', __('views.admin.purchase.invoices.create.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <form action="{{ route('admin.purchase.invoices.store') }}" method="POST" class="form-horizontal form-label-left">
                @csrf

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_invoice_beli">No. Tagihan (Invoice Vendor) <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="input-group">
                            <input id="no_invoice_beli" type="text" class="form-control" name="no_invoice_beli" value="{{ old('no_invoice_beli', $nextNo ?? '') }}" required>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default" onclick="document.getElementById('no_invoice_beli').value = 'PINV-' + new Date().getFullYear() + String(new Date().getMonth() + 1).padStart(2, '0') + '-' + Math.floor(1000 + Math.random() * 9000);" title="Generate Nomor Baru">
                                    <i class="fa fa-refresh"></i> Acak
                                </button>
                            </span>
                        </div>
                        <small class="text-muted"><i class="fa fa-info-circle"></i> Otomatis digenerate sistem atau masukkan nomor invoice dari faktur fisik vendor.</small>
                        @error('no_invoice_beli') <div class="text-danger"><strong>{{ $message }}</strong></div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tanggal">Tanggal <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="tanggal" type="date" class="form-control" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        @error('tanggal') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_penerimaan">No. Penerimaan</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="no_penerimaan" name="no_penerimaan" class="form-control">
                            <option value="">-- Tidak terkait Penerimaan --</option>
                            @foreach($receiptList as $receipt)
                                <option value="{{ $receipt->no_penerimaan }}" {{ (string) old('no_penerimaan') === (string) $receipt->no_penerimaan ? 'selected' : '' }}>
                                    {{ $receipt->no_penerimaan }}
                                </option>
                            @endforeach
                        </select>
                        @error('no_penerimaan') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="id_vendor">Vendor</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="id_vendor" name="id_vendor" class="form-control">
                            <option value="">-- Pilih Vendor --</option>
                            @foreach($vendorList as $vendor)
                                <option value="{{ $vendor->id_vendor }}" {{ (string) old('id_vendor') === (string) $vendor->id_vendor ? 'selected' : '' }}>
                                    {{ $vendor->nama_vendor }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_vendor') <span class="text-danger">{{ $message }}</span> @enderror
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
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jatuh_tempo">Jatuh Tempo <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="jatuh_tempo" type="date" class="form-control" name="jatuh_tempo" value="{{ old('jatuh_tempo') }}" required>
                        @error('jatuh_tempo') <span class="text-danger">{{ $message }}</span> @enderror
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
                            <option value="1" {{ (string) old('status', '1') === '1' ? 'selected' : '' }}>Belum Dibayar</option>
                            <option value="2" {{ (string) old('status') === '2' ? 'selected' : '' }}>Dibayar Sebagian</option>
                            <option value="3" {{ (string) old('status') === '3' ? 'selected' : '' }}>Lunas</option>
                        </select>
                        @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <hr>
                <h4 class="col-md-12">Daftar Barang Ditagih</h4>

                <div class="col-md-12" style="margin-bottom: 15px;">
                    <table class="table table-bordered" id="detail-table">
                        <thead>
                            <tr>
                                <th style="width:25%">SKU / Barang</th>
                                <th style="width:10%">Kuantitas</th>
                                <th style="width:15%">Harga Satuan</th>
                                <th style="width:12%">Diskon</th>
                                <th>Keterangan</th>
                                <th style="width:60px">Hapus</th>
                            </tr>
                        </thead>
                        <tbody id="detail-rows">
                            @php $rows = old('details', [['sku' => '', 'kuantitas' => '', 'harga_unit' => '', 'diskon' => 0, 'keterangan' => '']]); @endphp
                            @foreach($rows as $i => $row)
                                <tr>
                                    <td>
                                        <select name="details[{{ $i }}][sku]" class="form-control" required>
                                            <option value="">-- Pilih Barang --</option>
                                            @foreach($barangList as $barang)
                                                <option value="{{ $barang->sku }}" {{ ($row['sku'] ?? '') == $barang->sku ? 'selected' : '' }}>
                                                    {{ $barang->sku }} - {{ $barang->nama_barang }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </td>
                                    <td><input type="number" name="details[{{ $i }}][kuantitas]" class="form-control" min="1" value="{{ $row['kuantitas'] ?? '' }}" required></td>
                                    <td><input type="number" name="details[{{ $i }}][harga_unit]" class="form-control" step="0.01" min="0" value="{{ $row['harga_unit'] ?? '' }}" required></td>
                                    <td><input type="number" name="details[{{ $i }}][diskon]" class="form-control" step="0.01" min="0" value="{{ $row['diskon'] ?? 0 }}"></td>
                                    <td><input type="text" name="details[{{ $i }}][keterangan]" class="form-control" value="{{ $row['keterangan'] ?? '' }}"></td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-xs btn-danger btn-remove-row">&times;</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <button type="button" class="btn btn-sm btn-info" id="btn-add-row">+ Tambah Barang</button>
                    @error('details') <div class="text-danger">{{ $message }}</div> @enderror
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a class="btn btn-primary" href="{{ route('admin.purchase.invoices.index') }}">{{ __('views.admin.purchase.invoices.create.cancel') }}</a>
                        <button type="submit" class="btn btn-success">{{ __('views.admin.purchase.invoices.create.save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <table style="display:none;">
        <tbody id="row-template">
            <tr>
                <td>
                    <select name="details[__INDEX__][sku]" class="form-control" required>
                        <option value="">-- Pilih Barang --</option>
                        @foreach($barangList as $barang)
                            <option value="{{ $barang->sku }}">{{ $barang->sku }} - {{ $barang->nama_barang }}</option>
                        @endforeach
                    </select>
                </td>
                <td><input type="number" name="details[__INDEX__][kuantitas]" class="form-control" min="1" required></td>
                <td><input type="number" name="details[__INDEX__][harga_unit]" class="form-control" step="0.01" min="0" required></td>
                <td><input type="number" name="details[__INDEX__][diskon]" class="form-control" step="0.01" min="0" value="0"></td>
                <td><input type="text" name="details[__INDEX__][keterangan]" class="form-control"></td>
                <td class="text-center"><button type="button" class="btn btn-xs btn-danger btn-remove-row">&times;</button></td>
            </tr>
        </tbody>
    </table>

    <script>
        (function () {
            var rowIndex = {{ count(old('details', [1])) }};
            var allBarangList = @json($barangList);
            var detailRows = document.getElementById('detail-rows');
            var addBtn = document.getElementById('btn-add-row');

            function addRow(data) {
                data = data || {};
                var sku = data.sku || '';
                var qty = data.kuantitas || 1;
                var harga = data.harga_unit !== undefined ? data.harga_unit : '';
                var diskon = data.diskon !== undefined ? data.diskon : 0;
                var ket = data.keterangan || '';
                var isLocked = Boolean(data.sku);

                var tr = document.createElement('tr');

                var optionsHtml = '<option value="">-- Pilih Barang --</option>';
                allBarangList.forEach(function (b) {
                    var sel = (b.sku === sku) ? 'selected' : '';
                    optionsHtml += '<option value="' + b.sku + '" ' + sel + '>' + b.sku + ' - ' + b.nama_barang + '</option>';
                });

                tr.innerHTML = 
                    '<td>' +
                        (isLocked ? 
                            '<input type="hidden" name="details[' + rowIndex + '][sku]" value="' + sku + '">' +
                            '<input type="text" class="form-control" value="' + sku + ' - ' + (data.nama_barang || sku) + '" readonly>' :
                            '<select name="details[' + rowIndex + '][sku]" class="form-control" required>' + optionsHtml + '</select>'
                        ) +
                    '</td>' +
                    '<td><input type="number" name="details[' + rowIndex + '][kuantitas]" class="form-control" min="1" value="' + qty + '" required></td>' +
                    '<td><input type="number" name="details[' + rowIndex + '][harga_unit]" class="form-control" step="0.01" min="0" value="' + harga + '" required></td>' +
                    '<td><input type="number" name="details[' + rowIndex + '][diskon]" class="form-control" step="0.01" min="0" value="' + diskon + '"></td>' +
                    '<td><input type="text" name="details[' + rowIndex + '][keterangan]" class="form-control" value="' + ket + '"></td>' +
                    '<td class="text-center"><button type="button" class="btn btn-xs btn-danger btn-remove-row">&times;</button></td>';

                detailRows.appendChild(tr);
                rowIndex++;
            }

            addBtn.addEventListener('click', function () {
                addRow();
            });

            detailRows.addEventListener('click', function (e) {
                if (e.target.classList.contains('btn-remove-row')) {
                    var rows = document.querySelectorAll('#detail-rows tr');
                    if (rows.length > 1) {
                        e.target.closest('tr').remove();
                    } else {
                        alert('Minimal harus ada 1 barang.');
                    }
                }
            });

            var receiptSelect = document.getElementById('no_penerimaan');
            if (receiptSelect) {
                receiptSelect.addEventListener('change', function () {
                    var receiptId = this.value;
                    if (!receiptId) {
                        detailRows.innerHTML = '';
                        rowIndex = 0;
                        addRow();
                        addBtn.style.display = 'inline-block';
                        return;
                    }

                    fetch('/admin/purchase/receipts/' + receiptId + '/items-json')
                        .then(function (res) { return res.json(); })
                        .then(function (resData) {
                            if (resData.id_vendor) {
                                var vendorSelect = document.getElementById('id_vendor');
                                if (vendorSelect) {
                                    vendorSelect.value = resData.id_vendor;
                                }
                            }

                            var items = resData.items || [];
                            if (items.length > 0) {
                                detailRows.innerHTML = '';
                                rowIndex = 0;
                                items.forEach(function (item) {
                                    addRow(item);
                                });
                                addBtn.style.display = 'none';
                            }
                        })
                        .catch(function (err) {
                            console.error('Error fetching receipt items:', err);
                        });
                });
            }
        })();
    </script>
@endsection
