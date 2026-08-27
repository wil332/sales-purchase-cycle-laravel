@extends('admin.layouts.admin')

@section('title', __('views.admin.purchase.orders.edit.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <form action="{{ route('admin.purchase.orders.update', [$item->no_order_pembelian]) }}" method="POST" class="form-horizontal form-label-left">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">No. Order Pembelian</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" class="form-control" value="{{ $item->no_order_pembelian }}" disabled>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tanggal">Tanggal <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="tanggal" type="date" class="form-control" name="tanggal" value="{{ old('tanggal', optional($item->tanggal)->format('Y-m-d')) }}" required>
                        @error('tanggal') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="id_vendor">Vendor</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="id_vendor" name="id_vendor" class="form-control">
                            <option value="">-- Pilih Vendor --</option>
                            @foreach($vendorList as $vendor)
                                <option value="{{ $vendor->id_vendor }}" {{ (string) old('id_vendor', $item->id_vendor) === (string) $vendor->id_vendor ? 'selected' : '' }}>
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
                                <option value="{{ $pengguna->id_pengguna }}" {{ (string) old('id_pengguna', $item->id_pengguna ?? '') === (string) $pengguna->id_pengguna ? 'selected' : '' }}>
                                    {{ $pengguna->nama_lengkap }} ({{ ucfirst($pengguna->jabatan) }})
                                </option>
                            @endforeach
                        </select>
                        @error('id_pengguna') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tanggal_dibutuhkan">Tanggal Dibutuhkan</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="tanggal_dibutuhkan" type="date" class="form-control" name="tanggal_dibutuhkan" value="{{ old('tanggal_dibutuhkan', optional($item->tanggal_dibutuhkan)->format('Y-m-d')) }}">
                        @error('tanggal_dibutuhkan') <span class="text-danger">{{ $message }}</span> @enderror
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
                            <option value="0" {{ (string) old('status') === '0' ? 'selected' : '' }}>Batal</option>
                            <option value="1" {{ (string) old('status', '1') === '1' ? 'selected' : '' }}>Aktif</option>
                            <option value="2" {{ (string) old('status') === '2' ? 'selected' : '' }}>Diproses</option>
                            <option value="3" {{ (string) old('status') === '3' ? 'selected' : '' }}>Selesai</option>
                        </select>
                        @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <hr>
                <h4 class="col-md-12">Daftar Barang Dipesan</h4>

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
                            @php $rows = old('details', $item->details->toArray()); @endphp
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
                        <a class="btn btn-primary" href="{{ route('admin.purchase.orders.index') }}">Batal</a>
                        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
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
            var rowIndex = {{ count(old('details', $item->details->toArray())) }};
            var allBarangList = @json($barangList);
            var currentBarangOptions = allBarangList;

            function renderSelectOptions(selectEl, selectedVal) {
                var html = '<option value="">-- Pilih Barang --</option>';
                currentBarangOptions.forEach(function (b) {
                    var sel = (b.sku === selectedVal) ? 'selected' : '';
                    html += '<option value="' + b.sku + '" ' + sel + '>' + b.sku + ' - ' + b.nama_barang + '</option>';
                });
                selectEl.innerHTML = html;
            }

            function updateAllBarangSelects() {
                var templateSelect = document.querySelector('#row-template select');
                if (templateSelect) {
                    renderSelectOptions(templateSelect, '');
                }

                document.querySelectorAll('#detail-rows select[name$="[sku]"]').forEach(function (sel) {
                    var prevVal = sel.value;
                    renderSelectOptions(sel, prevVal);
                });
            }

            var vendorSelect = document.getElementById('id_vendor');
            if (vendorSelect) {
                vendorSelect.addEventListener('change', function () {
                    var vendorId = this.value;
                    if (!vendorId) {
                        currentBarangOptions = allBarangList;
                        updateAllBarangSelects();
                        return;
                    }

                    fetch('/admin/master/vendor/' + vendorId + '/barang')
                        .then(function (res) { return res.json(); })
                        .then(function (data) {
                            if (data && data.length > 0) {
                                currentBarangOptions = data;
                            } else {
                                currentBarangOptions = [];
                            }
                            updateAllBarangSelects();
                        })
                        .catch(function (err) {
                            console.error('Error fetching vendor products:', err);
                        });
                });
            }

            document.getElementById('btn-add-row').addEventListener('click', function () {
                var templateRow = document.querySelector('#row-template tr').cloneNode(true);
                templateRow.innerHTML = templateRow.innerHTML.replaceAll('__INDEX__', rowIndex);
                document.getElementById('detail-rows').appendChild(templateRow);
                rowIndex++;
            });

            document.getElementById('detail-rows').addEventListener('click', function (e) {
                if (e.target.classList.contains('btn-remove-row')) {
                    var rows = document.querySelectorAll('#detail-rows tr');
                    if (rows.length > 1) {
                        e.target.closest('tr').remove();
                    } else {
                        alert('Minimal harus ada 1 barang.');
                    }
                }
            });
        })();
    </script>
@endsection