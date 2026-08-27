@extends('admin.layouts.admin')

@section('title', __('views.admin.purchase.returns.create.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <form action="{{ route('admin.purchase.returns.store') }}" method="POST" class="form-horizontal form-label-left">
                @csrf

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nota_retur">No. Retur Pembelian <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="input-group">
                            <input id="nota_retur" type="text" class="form-control" name="nota_retur" value="{{ old('nota_retur', $nextNo ?? '') }}" oninput="document.getElementById('nomor_urut').value = this.value;" required>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default" onclick="let n = 'PRET-' + new Date().getFullYear() + String(new Date().getMonth() + 1).padStart(2, '0') + '-' + Math.floor(1000 + Math.random() * 9000); document.getElementById('nota_retur').value = n; document.getElementById('nomor_urut').value = n;" title="Generate Nomor Baru">
                                    <i class="fa fa-refresh"></i> Acak
                                </button>
                            </span>
                        </div>
                        <small class="text-muted"><i class="fa fa-info-circle"></i> Otomatis digenerate sistem.</small>
                        @error('nota_retur') <div class="text-danger"><strong>{{ $message }}</strong></div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nomor_urut">Nomor Urut / Referensi <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="nomor_urut" type="text" class="form-control" name="nomor_urut" value="{{ old('nomor_urut', $nextNo ?? '') }}" required>
                        @error('nomor_urut') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="atas_penerimaan_nomor">Referensi No. Penerimaan</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="atas_penerimaan_nomor" name="atas_penerimaan_nomor" class="form-control">
                            <option value="">-- Tidak terkait Penerimaan --</option>
                            @foreach($receiptList as $receipt)
                                <option value="{{ $receipt->no_penerimaan }}" {{ (string) old('atas_penerimaan_nomor') === (string) $receipt->no_penerimaan ? 'selected' : '' }}>
                                    {{ $receipt->no_penerimaan }}
                                </option>
                            @endforeach
                        </select>
                        @error('atas_penerimaan_nomor') <span class="text-danger">{{ $message }}</span> @enderror
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
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="id_penjual">Vendor</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="id_penjual" name="id_penjual" class="form-control">
                            <option value="">-- Pilih Vendor --</option>
                            @foreach($vendorList as $vendor)
                                <option value="{{ $vendor->id_vendor }}" {{ (string) old('id_penjual') === (string) $vendor->id_vendor ? 'selected' : '' }}>
                                    {{ $vendor->nama_vendor }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_penjual') <span class="text-danger">{{ $message }}</span> @enderror
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
                            <option value="1" {{ (string) old('status', '1') === '1' ? 'selected' : '' }}>Aktif</option>
                        </select>
                        @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <hr>
                <h4 class="col-md-12">Daftar Barang Diretur</h4>

                <div class="col-md-12" style="margin-bottom: 15px;">
                    <table class="table table-bordered" id="detail-table">
                        <thead>
                            <tr>
                                <th style="width:25%">SKU / Barang</th>
                                <th style="width:10%">Jumlah</th>
                                <th style="width:15%">Harga Satuan</th>
                                <th style="width:12%">Diskon</th>
                                <th>Keterangan</th>
                                <th style="width:60px">Hapus</th>
                            </tr>
                        </thead>
                        <tbody id="detail-rows">
                            @php $rows = old('details', [['sku' => '', 'jumlah' => '', 'harga_unit' => '', 'diskon' => 0, 'keterangan' => '']]); @endphp
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
                                    <td><input type="number" name="details[{{ $i }}][jumlah]" class="form-control" min="1" value="{{ $row['jumlah'] ?? '' }}" required></td>
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
                        <a class="btn btn-primary" href="{{ route('admin.purchase.returns.index') }}">{{ __('views.admin.purchase.returns.create.cancel') }}</a>
                        <button type="submit" class="btn btn-success">{{ __('views.admin.purchase.returns.create.save') }}</button>
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
                <td><input type="number" name="details[__INDEX__][jumlah]" class="form-control" min="1" required></td>
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
