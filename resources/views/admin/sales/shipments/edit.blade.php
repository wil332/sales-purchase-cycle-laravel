@extends('admin.layouts.admin')

@section('title', __('views.admin.sales.shipments.edit.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <form action="{{ route('admin.sales.shipments.update', [$item->no_pengiriman]) }}" method="POST" class="form-horizontal form-label-left">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">No. Pengiriman</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" class="form-control" value="{{ $item->no_pengiriman }}" disabled>
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="tanggal_kirim">Tanggal Kirim <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="tanggal_kirim" type="date" class="form-control" name="tanggal_kirim" value="{{ old('tanggal_kirim', optional($item->tanggal_kirim)->format('Y-m-d')) }}" required>
                        @error('tanggal_kirim') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_order_penjualan">No. Order Penjualan <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="no_order_penjualan" type="text" class="form-control" name="no_order_penjualan" value="{{ old('no_order_penjualan', $item->no_order_penjualan) }}" required>
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
                        <textarea id="keterangan" class="form-control" name="keterangan">{{ old('keterangan', $item->keterangan) }}</textarea>
                        @error('keterangan') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="status" name="status" class="form-control" required>
                            <option value="0" {{ (string) old('status', $item->status) === '0' ? "selected" : "" }}>Batal</option>
                            <option value="1" {{ (string) old('status', $item->status) === '1' ? "selected" : "" }}>Dalam Pengiriman</option>
                            <option value="2" {{ (string) old('status', $item->status) === '2' ? "selected" : "" }}>Telah Diterima</option>
                        </select>
                        @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                @php
                    $oldItems = old('items', $item->details->map(function ($d) {
                        return [
                            'sku' => $d->sku,
                            'kuantitas_dikirim' => $d->kuantitas_dikirim,
                            'keterangan' => $d->keterangan,
                        ];
                    })->toArray());
                @endphp

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
                                @foreach ($oldItems as $i => $row)
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
                        <a class="btn btn-primary" href="{{ route('admin.sales.shipments.index') }}">{{ __('views.admin.sales.shipments.edit.cancel') }}</a>
                        <button type="submit" class="btn btn-success">{{ __('views.admin.sales.shipments.edit.save') }}</button>
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
