@extends('admin.layouts.admin')

@section('title', __('views.admin.purchase.receipts.create.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            @if(!empty($prefillItems))
                <div class="alert alert-info">Form ini otomatis terisi dari Purchase Order. Silakan periksa kondisi barang, lalu simpan.</div>
            @endif

            <form action="{{ route('admin.purchase.receipts.store') }}" method="POST" class="form-horizontal form-label-left">
                @csrf

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_penerimaan">No. Penerimaan <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="input-group">
                            <input id="no_penerimaan" type="text" class="form-control" name="no_penerimaan" value="{{ old('no_penerimaan', $prefillHeader['no_penerimaan'] ?? ($nextNo ?? '')) }}" required>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default" onclick="document.getElementById('no_penerimaan').value = 'GR-' + new Date().getFullYear() + String(new Date().getMonth() + 1).padStart(2, '0') + '-' + Math.floor(1000 + Math.random() * 9000);" title="Generate Nomor Baru">
                                    <i class="fa fa-refresh"></i> Acak
                                </button>
                            </span>
                        </div>
                        <small class="text-muted"><i class="fa fa-info-circle"></i> Otomatis digenerate sistem. Anda dapat mengubahnya jika memiliki nomor surat jalan/penerimaan sendiri.</small>
                        @error('no_penerimaan') <div class="text-danger"><strong>{{ $message }}</strong></div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tanggal_penerimaan">Tanggal Penerimaan <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="tanggal_penerimaan" type="date" class="form-control" name="tanggal_penerimaan" value="{{ old('tanggal_penerimaan', date('Y-m-d')) }}" required>
                        @error('tanggal_penerimaan') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_order_pembelian">No. Order Pembelian</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="no_order_pembelian" name="no_order_pembelian" class="form-control">
                            <option value="">-- Tanpa Referensi PO --</option>
                            @foreach($orderList as $order)
                                <option value="{{ $order->no_order_pembelian }}"
                                    {{ (string) old('no_order_pembelian', $prefillHeader['no_order_pembelian'] ?? ($item->no_order_pembelian ?? '')) === (string) $order->no_order_pembelian ? 'selected' : '' }}>
                                    {{ $order->no_order_pembelian }} — {{ $order->tanggal }} ({{ $order->vendor->nama_vendor ?? 'Tanpa Vendor' }})
                                </option>
                            @endforeach
                        </select>
                        @error('no_order_pembelian') <span class="text-danger">{{ $message }}</span> @enderror
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
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="keterangan">Keterangan</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <textarea id="keterangan" class="form-control" name="keterangan">{{ old('keterangan', $prefillHeader['keterangan'] ?? '') }}</textarea>
                        @error('keterangan') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="status" name="status" class="form-control" required>
                            <option value="0" {{ (string) old('status') === '0' ? 'selected' : '' }}>Masalah</option>
                            <option value="1" {{ (string) old('status', '1') === '1' ? 'selected' : '' }}>Diterima</option>
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
                                    <th style="width:15%">Kondisi</th>
                                    <th>Keterangan</th>
                                    <th style="width:60px"></th>
                                </tr>
                            </thead>
                            <tbody id="items-body">
                                @foreach (old('items', $prefillItems ?? []) as $i => $row)
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
                                        <td>
                                            <select name="items[{{ $i }}][kondisi_barang]" class="form-control" required>
                                                <option value="baik" {{ ($row['kondisi_barang'] ?? 'baik') === 'baik' ? 'selected' : '' }}>Baik</option>
                                                <option value="rusak" {{ ($row['kondisi_barang'] ?? '') === 'rusak' ? 'selected' : '' }}>Rusak</option>
                                            </select>
                                        </td>
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
                        <a class="btn btn-primary" href="{{ route('admin.purchase.receipts.index') }}">{{ __('views.admin.purchase.receipts.create.cancel') }}</a>
                        <button type="submit" class="btn btn-success">{{ __('views.admin.purchase.receipts.create.save') }}</button>
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
            <td>
                <select name="items[__INDEX__][kondisi_barang]" class="form-control" required>
                    <option value="baik">Baik</option>
                    <option value="rusak">Rusak</option>
                </select>
            </td>
            <td><input type="text" name="items[__INDEX__][keterangan]" class="form-control"></td>
            <td><button type="button" class="btn btn-xs btn-danger remove-item-row">Hapus</button></td>
        </tr>
    </template>

    <script>
        (function () {
            var body = document.getElementById('items-body');
            var addBtn = document.getElementById('add-item-row');
            var template = document.getElementById('item-row-template').innerHTML;
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
                        '<input type="number" min="1" ' + (maxQty ? 'max="' + maxQty + '"' : '') + ' name="items[' + rowIndex + '][kuantitas]" class="form-control" value="' + qty + '" required>' +
                        (maxQty ? '<small class="text-muted">Maks: ' + maxQty + '</small>' : '') +
                    '</td>' +
                    '<td>' +
                        '<select name="items[' + rowIndex + '][kondisi_barang]" class="form-control" required>' +
                            '<option value="baik" selected>Baik</option>' +
                            '<option value="rusak">Rusak</option>' +
                        '</select>' +
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

            var orderSelect = document.getElementById('no_order_pembelian');
            if (orderSelect) {
                orderSelect.addEventListener('change', function () {
                    var orderId = this.value;
                    if (!orderId) {
                        body.innerHTML = '';
                        rowIndex = 0;
                        addRow();
                        addBtn.style.display = 'inline-block';
                        return;
                    }

                    fetch('/admin/purchase/orders/' + orderId + '/items-json')
                        .then(function (res) { return res.json(); })
                        .then(function (resData) {
                            var items = resData.items || resData;
                            if (items && items.length > 0) {
                                body.innerHTML = '';
                                rowIndex = 0;
                                items.forEach(function (item) {
                                    addRow(item);
                                });
                                addBtn.style.display = 'none'; // Kunci tombol tambah manual saat menggunakan referensi PO
                            }
                        })
                        .catch(function (err) {
                            console.error('Error fetching PO items:', err);
                        });
                });
            }

            if (rowIndex === 0) { addRow(); }
        })();
    </script>
@endsection