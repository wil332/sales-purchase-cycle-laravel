@extends('admin.layouts.admin')

@section('title', __('views.admin.purchase.requests.create.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <form action="{{ route('admin.purchase.requests.store') }}" method="POST" class="form-horizontal form-label-left">
                @csrf

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_faktur">No. Permintaan (Faktur) <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="input-group">
                            <input id="no_faktur" type="text" class="form-control" name="no_faktur" value="{{ old('no_faktur', $nextNo ?? '') }}" required>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default" onclick="document.getElementById('no_faktur').value = 'PR-' + new Date().getFullYear() + String(new Date().getMonth() + 1).padStart(2, '0') + '-' + Math.floor(1000 + Math.random() * 9000);" title="Generate Nomor Baru">
                                    <i class="fa fa-refresh"></i> Acak
                                </button>
                            </span>
                        </div>
                        <small class="text-muted"><i class="fa fa-info-circle"></i> Otomatis digenerate sistem. Anda dapat mengubahnya jika memiliki nomor nota sendiri.</small>
                        @error('no_faktur') <div class="text-danger"><strong>{{ $message }}</strong></div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tanggal">Tanggal <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="tanggal" type="date" class="form-control" name="tanggal" value="{{ old('tanggal') }}" required>
                        @error('tanggal') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="id_pengguna">Dicatat Oleh</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="id_pengguna" name="id_pengguna" class="form-control">
                            <option value="">-- Pilih Pengguna --</option>
                            @foreach($penggunaList as $pengguna)
                                <option value="{{ $pengguna->id_pengguna }}" {{ (string) old('id_pengguna') === (string) $pengguna->id_pengguna ? 'selected' : '' }}>
                                {{ $pengguna->nama_lengkap }} ({{ ucfirst($pengguna->jabatan) }})
                                </option>
                            @endforeach
                        </select>
                    @error('id_pengguna') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tanggal_diperlukan">Tanggal Diperlukan</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="tanggal_diperlukan" type="date" class="form-control" name="tanggal_diperlukan" value="{{ old('tanggal_diperlukan') }}">
                        @error('tanggal_diperlukan') <span class="text-danger">{{ $message }}</span> @enderror
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
                            <option value="2" {{ (string) old('status') === '2' ? 'selected' : '' }}>Parsial</option>
                        </select>
                        @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <hr>
                <h4 class="col-md-12">Daftar Barang Diminta</h4>

                <div class="col-md-12" style="margin-bottom: 15px;">
                    <table class="table table-bordered" id="detail-table">
                        <thead>
                            <tr>
                                <th style="width:30%">SKU / Barang</th>
                                <th style="width:15%">Kuantitas</th>
                                <th>Keterangan</th>
                                <th style="width:60px">Hapus</th>
                            </tr>
                        </thead>
                        <tbody id="detail-rows">
                            @foreach(old('details', [['sku' => '', 'kuantitas' => '', 'keterangan' => '']]) as $i => $row)
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
                                    <td>
                                        <input type="number" name="details[{{ $i }}][kuantitas]" class="form-control" min="1" value="{{ $row['kuantitas'] ?? '' }}" required>
                                    </td>
                                    <td>
                                        <input type="text" name="details[{{ $i }}][keterangan]" class="form-control" value="{{ $row['keterangan'] ?? '' }}">
                                    </td>
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
                        <a class="btn btn-primary" href="{{ route('admin.purchase.requests.index') }}">{{ __('views.admin.purchase.requests.create.cancel') }}</a>
                        <button type="submit" class="btn btn-success">{{ __('views.admin.purchase.requests.create.save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    {{-- Template baris kosong, dipakai JS buat clone --}}
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