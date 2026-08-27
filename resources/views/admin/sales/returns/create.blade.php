@extends('admin.layouts.admin')

@section('title', __('views.admin.sales.returns.create.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <form action="{{ route('admin.sales.returns.store') }}" method="POST" class="form-horizontal form-label-left">
                @csrf

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_retur_jual">No. Retur Penjualan <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="input-group">
                            <input id="no_retur_jual" type="text" class="form-control" name="no_retur_jual" value="{{ old('no_retur_jual', $nextNo ?? '') }}" required>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default" onclick="document.getElementById('no_retur_jual').value = 'SRET-' + new Date().getFullYear() + String(new Date().getMonth() + 1).padStart(2, '0') + '-' + Math.floor(1000 + Math.random() * 9000);" title="Generate Nomor Baru">
                                    <i class="fa fa-refresh"></i> Acak
                                </button>
                            </span>
                        </div>
                        <small class="text-muted"><i class="fa fa-info-circle"></i> Otomatis digenerate sistem.</small>
                        @error('no_retur_jual') <div class="text-danger"><strong>{{ $message }}</strong></div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tanggal">Tanggal Retur <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="tanggal" type="date" class="form-control" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        @error('tanggal') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_invoice_jual">Pilih Faktur Penjualan <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        @if(!empty($invoiceList) && $invoiceList->count() > 0)
                            <select id="no_invoice_jual" name="no_invoice_jual" class="form-control" required>
                                <option value="">-- Pilih Faktur Penjualan Terkait --</option>
                                @foreach($invoiceList as $inv)
                                    <option value="{{ $inv->no_invoice_jual }}" {{ (string) old('no_invoice_jual') === (string) $inv->no_invoice_jual ? 'selected' : '' }}>
                                        {{ $inv->no_invoice_jual }} - Pelanggan: {{ $inv->pelanggan->nama_pelanggan ?? 'Umum' }}
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
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="id_pelanggan">Pelanggan <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="id_pelanggan" name="id_pelanggan" class="form-control" required>
                            <option value="">-- Pilih Pelanggan --</option>
                            @foreach($pelangganList as $pelanggan)
                                <option value="{{ $pelanggan->id_pelanggan }}" {{ (string) old('id_pelanggan', $item->id_pelanggan ?? '') === (string) $pelanggan->id_pelanggan ? 'selected' : '' }}>
                                    {{ $pelanggan->nama_pelanggan }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_pelanggan') <span class="text-danger">{{ $message }}</span> @enderror
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
                            <option value="1" {{ (string) old('status', null) === '1' ? "selected" : "" }}>Diajukan</option>
                            <option value="2" {{ (string) old('status', null) === '2' ? "selected" : "" }}>Selesai/Disetujui</option>
                        </select>
                        @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Item Retur <span class="required">*</span></label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <table class="table table-bordered" id="items-table">
                            <thead>
                                <tr>
                                    <th style="width:30%">SKU Barang</th>
                                    <th style="width:15%">Jumlah Diretur</th>
                                    <th>Alasan Retur</th>
                                    <th style="width:60px"></th>
                                </tr>
                            </thead>
                            <tbody id="items-body">
                                @foreach (old('items', []) as $i => $row)
                                    <tr class="item-row">
                                        <td>
                                            <select name="items[{{ $i }}][sku]" class="form-control" required>
                                                <option value="">-- Pilih Barang --</option>
                                                @foreach ($barangList as $barang)
                                                    <option value="{{ $barang->sku }}" {{ ($row['sku'] ?? null) === $barang->sku ? 'selected' : '' }}>{{ $barang->sku }} - {{ $barang->nama_barang }}</option>
                                                @endforeach
                                            </select>
                                        </td>
                                        <td><input type="number" min="1" name="items[{{ $i }}][jumlah_diretur]" class="form-control" value="{{ $row['jumlah_diretur'] ?? '' }}" required></td>
                                        <td><input type="text" name="items[{{ $i }}][alasan_retur]" class="form-control" value="{{ $row['alasan_retur'] ?? '' }}"></td>
                                        <td><button type="button" class="btn btn-xs btn-danger remove-item-row">Hapus</button></td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button type="button" id="add-item-row" class="btn btn-sm btn-primary">+ Tambah Item</button>
                        @error('items') <span class="text-danger d-block">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a class="btn btn-primary" href="{{ route('admin.sales.returns.index') }}">{{ __('views.admin.sales.returns.create.cancel') }}</a>
                        <button type="submit" class="btn btn-success">{{ __('views.admin.sales.returns.create.save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <template id="item-row-template">
        <tr class="item-row">
            <td>
                <select name="items[__INDEX__][sku]" class="form-control" required>
                    <option value="">-- Pilih Barang --</option>
                    @foreach ($barangList as $barang)
                        <option value="{{ $barang->sku }}">{{ $barang->sku }} - {{ $barang->nama_barang }}</option>
                    @endforeach
                </select>
            </td>
            <td><input type="number" min="1" name="items[__INDEX__][jumlah_diretur]" class="form-control" required></td>
            <td><input type="text" name="items[__INDEX__][alasan_retur]" class="form-control"></td>
            <td><button type="button" class="btn btn-xs btn-danger remove-item-row">Hapus</button></td>
        </tr>
    </template>

    <script>
        (function () {
            var body = document.getElementById('items-body');
            var addBtn = document.getElementById('add-item-row');
            var template = document.getElementById('item-row-template').innerHTML;
            var rowIndex = body.querySelectorAll('.item-row').length;

            function addRow() {
                var html = template.split('__INDEX__').join(rowIndex);
                var wrapper = document.createElement('tbody');
                wrapper.innerHTML = html.trim();
                body.appendChild(wrapper.firstElementChild);
                rowIndex++;
            }

            addBtn.addEventListener('click', addRow);

            body.addEventListener('click', function (e) {
                if (e.target.classList.contains('remove-item-row')) {
                    var rows = body.querySelectorAll('.item-row');
                    if (rows.length > 1) {
                        e.target.closest('tr').remove();
                    } else {
                        alert('Minimal harus ada 1 item.');
                    }
                }
            });

            if (rowIndex === 0) {
                addRow();
            }
        })();
    </script>
@endsection
