@extends('admin.layouts.admin')

@section('title', __('views.admin.sales.invoices.edit.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <form action="{{ route('admin.sales.invoices.update', [$item->no_invoice_jual]) }}" method="POST" class="form-horizontal form-label-left">
                @csrf
                @method('PUT')

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">No. Invoice</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" class="form-control" value="{{ $item->no_invoice_jual }}" disabled>
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
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="no_pengiriman">No. Pengiriman <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="no_pengiriman" type="text" class="form-control" name="no_pengiriman" value="{{ old('no_pengiriman', $item->no_pengiriman) }}" required>
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
                        <input id="jatuh_tempo" type="date" class="form-control" name="jatuh_tempo" value="{{ old('jatuh_tempo', optional($item->jatuh_tempo)->format('Y-m-d')) }}" required>
                        @error('jatuh_tempo') <span class="text-danger">{{ $message }}</span> @enderror
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
                            <option value="1" {{ (string) old('status', $item->status) === '1' ? "selected" : "" }}>Belum Dibayar</option>
                            <option value="2" {{ (string) old('status', $item->status) === '2' ? "selected" : "" }}>Dibayar Sebagian</option>
                            <option value="3" {{ (string) old('status', $item->status) === '3' ? "selected" : "" }}>Lunas</option>
                        </select>
                        @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                @php
                    $oldItems = old('items', $item->details->map(function ($d) {
                        return [
                            'sku' => $d->sku,
                            'kuantitas' => $d->kuantitas,
                            'harga_jual' => $d->harga_jual,
                            'diskon' => $d->diskon,
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
                                    <th style="width:30%">SKU Barang</th>
                                    <th style="width:12%">Kuantitas</th>
                                    <th style="width:15%">Harga Jual</th>
                                    <th style="width:15%">Diskon</th>
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
                        <a class="btn btn-primary" href="{{ route('admin.sales.invoices.index') }}">{{ __('views.admin.sales.invoices.edit.cancel') }}</a>
                        <button type="submit" class="btn btn-success">{{ __('views.admin.sales.invoices.edit.save') }}</button>
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
