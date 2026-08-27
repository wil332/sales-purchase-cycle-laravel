@extends('admin.layouts.admin')

@section('title', __('views.admin.purchase.receipts.edit.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <form action="{{ route('admin.purchase.receipts.update', [$item->no_penerimaan]) }}" method="POST" class="form-horizontal form-label-left">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">No. Penerimaan</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" class="form-control" value="{{ $item->no_penerimaan }}" disabled>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tanggal_penerimaan">Tanggal Penerimaan <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="tanggal_penerimaan" type="date" class="form-control" name="tanggal_penerimaan" value="{{ old('tanggal_penerimaan', optional($item->tanggal_penerimaan)->format('Y-m-d')) }}" required>
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
                                <option value="{{ $pengguna->id_pengguna }}" {{ (string) old('id_pengguna', $item->id_pengguna ?? '') === (string) $pengguna->id_pengguna ? 'selected' : '' }}>
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
                        <textarea id="keterangan" class="form-control" name="keterangan">{{ old('keterangan', $item->keterangan) }}</textarea>
                        @error('keterangan') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="status" name="status" class="form-control" required>
                            <option value="0" {{ (string) old('status', $item->status) === '0' ? 'selected' : '' }}>Masalah</option>
                            <option value="1" {{ (string) old('status', $item->status) === '1' ? 'selected' : '' }}>Diterima</option>
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
                                @php $rows = old('items', $item->details->toArray()); @endphp
                                @foreach ($rows as $i => $row)
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
                        <a class="btn btn-primary" href="{{ route('admin.purchase.receipts.index') }}">Batal</a>
                        <button type="submit" class="btn btn-success">Simpan Perubahan</button>
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
                    if (rows.length > 1) { e.target.closest('tr').remove(); }
                    else { alert('Minimal harus ada 1 item.'); }
                }
            });
        })();
    </script>
@endsection