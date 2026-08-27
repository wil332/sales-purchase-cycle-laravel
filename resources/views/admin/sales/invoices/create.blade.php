@extends('admin.layouts.admin')

@section('title', __('views.admin.sales.invoices.create.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <form action="{{ route('admin.sales.invoices.store') }}" method="POST" class="form-horizontal form-label-left">
                @csrf

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_invoice_jual">No. Tagihan (Faktur Jual) <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="input-group">
                            <input id="no_invoice_jual" type="text" class="form-control" name="no_invoice_jual" value="{{ old('no_invoice_jual', $nextNo ?? '') }}" required>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default" onclick="document.getElementById('no_invoice_jual').value = 'SINV-' + new Date().getFullYear() + String(new Date().getMonth() + 1).padStart(2, '0') + '-' + Math.floor(1000 + Math.random() * 9000);" title="Generate Nomor Baru">
                                    <i class="fa fa-refresh"></i> Acak
                                </button>
                            </span>
                        </div>
                        <small class="text-muted"><i class="fa fa-info-circle"></i> Otomatis digenerate sistem.</small>
                        @error('no_invoice_jual') <div class="text-danger"><strong>{{ $message }}</strong></div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tanggal">Tanggal Tagihan <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="tanggal" type="date" class="form-control" name="tanggal" value="{{ old('tanggal', date('Y-m-d')) }}" required>
                        @error('tanggal') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_pengiriman">Pilih Surat Jalan (Pengiriman) <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        @if(!empty($shipmentList) && $shipmentList->count() > 0)
                            <select id="no_pengiriman" name="no_pengiriman" class="form-control" required>
                                <option value="">-- Pilih Surat Jalan / Pengiriman --</option>
                                @foreach($shipmentList as $shp)
                                    <option value="{{ $shp->no_pengiriman }}" {{ (string) old('no_pengiriman') === (string) $shp->no_pengiriman ? 'selected' : '' }}>
                                        {{ $shp->no_pengiriman }} (Kirim: {{ optional($shp->tanggal_kirim)->format('d/m/Y') }}) - SO: {{ $shp->no_order_penjualan }}
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <input id="no_pengiriman" type="text" class="form-control" name="no_pengiriman" value="{{ old('no_pengiriman', null) }}" placeholder="Contoh: SHP-202608-0001" required>
                        @endif
                        @error('no_pengiriman') <span class="text-danger">{{ $message }}</span> @enderror
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
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="jatuh_tempo">Jatuh Tempo <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="jatuh_tempo" type="date" class="form-control" name="jatuh_tempo" value="{{ old('jatuh_tempo', null) }}" required>
                        @error('jatuh_tempo') <span class="text-danger">{{ $message }}</span> @enderror
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
                            <option value="1" {{ (string) old('status', null) === '1' ? "selected" : "" }}>Belum Dibayar</option>
                            <option value="2" {{ (string) old('status', null) === '2' ? "selected" : "" }}>Dibayar Sebagian</option>
                            <option value="3" {{ (string) old('status', null) === '3' ? "selected" : "" }}>Lunas</option>
                        </select>
                        @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Item Barang <span class="required">*</span></label>
                    <div class="col-md-9 col-sm-9 col-xs-12">
                        <table class="table table-bordered" id="items-table">
                            <thead>
                                <tr>
                                    <th style="width:30%">SKU Barang</th>
                                    <th style="width:12%">Kuantitas</th>
                                    <th style="width:15%">Harga Jual</th>
                                    <th style="width:15%">Diskon</th>
                                    <th>Keterangan</th>
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
                                        <td><input type="number" min="1" name="items[{{ $i }}][kuantitas]" class="form-control" value="{{ $row['kuantitas'] ?? '' }}" required></td>
                                        <td><input type="number" step="any" min="0" name="items[{{ $i }}][harga_jual]" class="form-control" value="{{ $row['harga_jual'] ?? '' }}" required></td>
                                        <td><input type="number" step="any" min="0" name="items[{{ $i }}][diskon]" class="form-control" value="{{ $row['diskon'] ?? 0 }}"></td>
                                        <td><input type="text" name="items[{{ $i }}][keterangan]" class="form-control" value="{{ $row['keterangan'] ?? '' }}"></td>
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
                        <a class="btn btn-primary" href="{{ route('admin.sales.invoices.index') }}">{{ __('views.admin.sales.invoices.create.cancel') }}</a>
                        <button type="submit" class="btn btn-success">{{ __('views.admin.sales.invoices.create.save') }}</button>
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
            <td><input type="number" min="1" name="items[__INDEX__][kuantitas]" class="form-control" required></td>
            <td><input type="number" step="any" min="0" name="items[__INDEX__][harga_jual]" class="form-control" required></td>
            <td><input type="number" step="any" min="0" name="items[__INDEX__][diskon]" class="form-control" value="0"></td>
            <td><input type="text" name="items[__INDEX__][keterangan]" class="form-control"></td>
            <td><button type="button" class="btn btn-xs btn-danger remove-item-row">Hapus</button></td>
        </tr>
    </template>

    <script>
        (function () {
            var body = document.getElementById('items-body');
            var addBtn = document.getElementById('add-item-row');
            var allBarangList = @json($barangList);
            var rowIndex = body.querySelectorAll('.item-row').length;

            function addRow(data) {
                data = data || {};
                var sku = data.sku || '';
                var qty = data.kuantitas || 1;
                var harga = data.harga_jual !== undefined ? data.harga_jual : '';
                var diskon = data.diskon !== undefined ? data.diskon : 0;
                var ket = data.keterangan || '';
                var isLocked = Boolean(data.sku);

                var tr = document.createElement('tr');
                tr.className = 'item-row';

                var optionsHtml = '<option value="">-- Pilih Barang --</option>';
                allBarangList.forEach(function (b) {
                    var sel = (b.sku === sku) ? 'selected' : '';
                    optionsHtml += '<option value="' + b.sku + '" ' + sel + '>' + b.sku + ' - ' + b.nama_barang + '</option>';
                });

                tr.innerHTML = 
                    '<td>' +
                        (isLocked ? 
                            '<input type="hidden" name="items[' + rowIndex + '][sku]" value="' + sku + '">' +
                            '<input type="text" class="form-control" value="' + sku + ' - ' + (data.nama_barang || sku) + '" readonly>' :
                            '<select name="items[' + rowIndex + '][sku]" class="form-control" required>' + optionsHtml + '</select>'
                        ) +
                    '</td>' +
                    '<td><input type="number" min="1" name="items[' + rowIndex + '][kuantitas]" class="form-control" value="' + qty + '" required></td>' +
                    '<td><input type="number" step="any" min="0" name="items[' + rowIndex + '][harga_jual]" class="form-control" value="' + harga + '" required></td>' +
                    '<td><input type="number" step="any" min="0" name="items[' + rowIndex + '][diskon]" class="form-control" value="' + diskon + '"></td>' +
                    '<td><input type="text" name="items[' + rowIndex + '][keterangan]" class="form-control" value="' + ket + '"></td>' +
                    '<td><button type="button" class="btn btn-xs btn-danger remove-item-row">Hapus</button></td>';

                body.appendChild(tr);
                rowIndex++;
            }

            addBtn.addEventListener('click', function () {
                addRow();
            });

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

            var shipmentSelect = document.getElementById('no_pengiriman');
            if (shipmentSelect && shipmentSelect.tagName.toLowerCase() === 'select') {
                shipmentSelect.addEventListener('change', function () {
                    var shipmentId = this.value;
                    if (!shipmentId) {
                        body.innerHTML = '';
                        rowIndex = 0;
                        addRow();
                        addBtn.style.display = 'inline-block';
                        return;
                    }

                    fetch('/admin/sales/shipments/' + shipmentId + '/items-json')
                        .then(function (res) { return res.json(); })
                        .then(function (resData) {
                            if (resData.id_pelanggan) {
                                var pelangganSelect = document.getElementById('id_pelanggan');
                                if (pelangganSelect) {
                                    pelangganSelect.value = resData.id_pelanggan;
                                }
                            }

                            var items = resData.items || [];
                            if (items.length > 0) {
                                body.innerHTML = '';
                                rowIndex = 0;
                                items.forEach(function (item) {
                                    addRow(item);
                                });
                                addBtn.style.display = 'none';
                            }
                        })
                        .catch(function (err) {
                            console.error('Error fetching shipment items:', err);
                        });
                });
            }

            if (rowIndex === 0) {
                addRow();
            }
        })();
    </script>
@endsection
