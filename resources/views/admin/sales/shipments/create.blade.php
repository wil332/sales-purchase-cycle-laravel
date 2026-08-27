@extends('admin.layouts.admin')

@section('title', __('views.admin.sales.shipments.create.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <form action="{{ route('admin.sales.shipments.store') }}" method="POST" class="form-horizontal form-label-left">
                @csrf

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_pengiriman">No. Surat Jalan (Pengiriman) <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="input-group">
                            <input id="no_pengiriman" type="text" class="form-control" name="no_pengiriman" value="{{ old('no_pengiriman', $nextNo ?? '') }}" required>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default" onclick="document.getElementById('no_pengiriman').value = 'SHP-' + new Date().getFullYear() + String(new Date().getMonth() + 1).padStart(2, '0') + '-' + Math.floor(1000 + Math.random() * 9000);" title="Generate Nomor Baru">
                                    <i class="fa fa-refresh"></i> Acak
                                </button>
                            </span>
                        </div>
                        <small class="text-muted"><i class="fa fa-info-circle"></i> Otomatis digenerate sistem.</small>
                        @error('no_pengiriman') <div class="text-danger"><strong>{{ $message }}</strong></div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tanggal_kirim">Tanggal Kirim <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="tanggal_kirim" type="date" class="form-control" name="tanggal_kirim" value="{{ old('tanggal_kirim', date('Y-m-d')) }}" required>
                        @error('tanggal_kirim') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_order_penjualan">Pilih Sales Order (SO) <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        @if(!empty($salesOrderList) && $salesOrderList->count() > 0)
                            <select id="no_order_penjualan" name="no_order_penjualan" class="form-control" required>
                                <option value="">-- Pilih Sales Order --</option>
                                @foreach($salesOrderList as $so)
                                    <option value="{{ $so->no_order_penjualan }}" {{ (string) old('no_order_penjualan') === (string) $so->no_order_penjualan ? 'selected' : '' }}>
                                        {{ $so->no_order_penjualan }} - {{ $so->pelanggan->nama_pelanggan ?? 'Umum' }} ({{ optional($so->tanggal)->format('d/m/Y') }})
                                    </option>
                                @endforeach
                            </select>
                        @else
                            <input id="no_order_penjualan" type="text" class="form-control" name="no_order_penjualan" value="{{ old('no_order_penjualan', null) }}" placeholder="Contoh: SO-202608-0001" required>
                        @endif
                        @error('no_order_penjualan') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="id_pengguna">Sales yang Melayani</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="id_pengguna" name="id_pengguna" class="form-control">
                            <option value="">-- Pilih Sales --</option>
                            @foreach($penggunaList as $pengguna)
                                <option value="{{ $pengguna->id_pengguna }}" {{ (string) old('id_pengguna', $item->id_pengguna ?? '') === (string) $pengguna->id_pengguna ? 'selected' : '' }}>
                                    {{ $pengguna->nama_lengkap }}
                                </option>
                            @endforeach
                        </select>
                        @error('id_pengguna') <span class="text-danger">{{ $message }}</span> @enderror
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
                            <option value="1" {{ (string) old('status', null) === '1' ? "selected" : "" }}>Dalam Pengiriman</option>
                            <option value="2" {{ (string) old('status', null) === '2' ? "selected" : "" }}>Telah Diterima</option>
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
                                    <th style="width:35%">SKU Barang</th>
                                    <th style="width:15%">Kuantitas Dikirim</th>
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
                                        <td><input type="number" min="1" name="items[{{ $i }}][kuantitas_dikirim]" class="form-control" value="{{ $row['kuantitas_dikirim'] ?? '' }}" required></td>
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
                        <a class="btn btn-primary" href="{{ route('admin.sales.shipments.index') }}">{{ __('views.admin.sales.shipments.create.cancel') }}</a>
                        <button type="submit" class="btn btn-success">{{ __('views.admin.sales.shipments.create.save') }}</button>
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
            <td><input type="number" min="1" name="items[__INDEX__][kuantitas_dikirim]" class="form-control" required></td>
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
                var qty = data.kuantitas_sisa || data.kuantitas || 1;
                var maxQty = data.kuantitas_sisa || data.kuantitas || '';
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
                    '<td>' +
                        '<input type="number" min="1" ' + (maxQty ? 'max="' + maxQty + '"' : '') + ' name="items[' + rowIndex + '][kuantitas_dikirim]" class="form-control" value="' + qty + '" required>' +
                        (maxQty ? '<small class="text-muted">Maks: ' + maxQty + '</small>' : '') +
                    '</td>' +
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
                    if (rows.length > 1) { e.target.closest('tr').remove(); }
                    else { alert('Minimal harus ada 1 item.'); }
                }
            });

            var soSelect = document.getElementById('no_order_penjualan');
            if (soSelect && soSelect.tagName.toLowerCase() === 'select') {
                soSelect.addEventListener('change', function () {
                    var soId = this.value;
                    if (!soId) {
                        body.innerHTML = '';
                        rowIndex = 0;
                        addRow();
                        addBtn.style.display = 'inline-block';
                        return;
                    }

                    fetch('/admin/sales/orders/' + soId + '/items-json')
                        .then(function (res) { return res.json(); })
                        .then(function (resData) {
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
                            console.error('Error fetching SO items:', err);
                        });
                });
            }

            if (rowIndex === 0) {
                addRow();
            }
        })();
    </script>
@endsection
