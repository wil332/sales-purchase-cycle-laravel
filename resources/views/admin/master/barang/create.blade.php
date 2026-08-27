@extends('admin.layouts.admin')

@section('title', __('views.admin.master.barang.create.title'))

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
            <form id="createBarangForm" action="{{ route('admin.master.barang.store') }}" method="POST" class="form-horizontal form-label-left">
                @csrf

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="sku">
                        Kode Barang (SKU) <span class="required">*</span>
                    </label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <div class="input-group">
                            <input id="sku" type="text" class="form-control" name="sku" value="{{ old('sku', $nextSku ?? '') }}" placeholder="Contoh: BRG031" required>
                            <span class="input-group-btn">
                                <button type="button" class="btn btn-default" id="btnRandomSku" title="Generate SKU Baru">
                                    <i class="fa fa-refresh"></i> Acak
                                </button>
                            </span>
                        </div>
                        <div id="sku-feedback" style="margin-top: 4px; font-size: 12px;"></div>
                        <small class="text-muted"><i class="fa fa-info-circle"></i> Otomatis digenerate sistem. Anda dapat mengubahnya jika memiliki kode SKU / Barcode khusus.</small>
                        @error('sku') <div class="text-danger" style="margin-top: 4px;"><strong>{{ $message }}</strong></div> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="nama_barang">Nama Barang <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="nama_barang" type="text" class="form-control" name="nama_barang" value="{{ old('nama_barang') }}" placeholder="Masukkan nama produk lengkap" required>
                        <div id="nama-feedback" style="margin-top: 4px; font-size: 12px;"></div>
                        @error('nama_barang') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="category_id">Kategori Produk</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="category_id" name="category_id" class="form-control">
                            <option value="">-- Tanpa Kategori --</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ (string) old('category_id') === (string) $cat->id ? 'selected' : '' }}>
                                    {{ $cat->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="keterangan">Keterangan / Spesifikasi</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <textarea id="keterangan" class="form-control" name="keterangan" rows="3" placeholder="Deskripsi atau catatan spesifikasi barang...">{{ old('keterangan', null) }}</textarea>
                        @error('keterangan') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12" for="status">Status Produk <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="status" name="status" class="form-control" required>
                            <option value="1" {{ (string) old('status', '1') === '1' ? "selected" : "" }}>Aktif (Bisa Dijual / Dipesan)</option>
                            <option value="0" {{ (string) old('status', '1') === '0' ? "selected" : "" }}>Tidak Aktif</option>
                        </select>
                        <div id="status-feedback" style="margin-top: 4px; font-size: 12px;"></div>
                        @error('status') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a class="btn btn-primary" href="{{ route('admin.master.barang.index') }}">{{ __('views.admin.master.barang.create.cancel') }}</a>
                        <button type="submit" id="btnSubmit" class="btn btn-success">{{ __('views.admin.master.barang.create.save') }}</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('scripts')
@parent
<script>
$(document.ready(function(){
    // Function check SKU via AJAX
    function validateSkuOnServer(skuVal) {
        if(!skuVal) {
            $('#sku-feedback').html('<span class="text-danger"><i class="fa fa-times-circle"></i> SKU tidak boleh kosong.</span>');
            return;
        }
        $('#sku-feedback').html('<span class="text-info"><i class="fa fa-spinner fa-spin"></i> Memeriksa ketersediaan SKU...</span>');
        
        $.get("{{ route('admin.master.barang.check-sku') }}", { sku: skuVal }, function(res) {
            if(res.exists) {
                $('#sku-feedback').html('<span class="text-danger"><strong><i class="fa fa-exclamation-triangle"></i> ' + res.message + '</strong></span>');
                $('#sku').addClass('parsley-error');
            } else {
                $('#sku-feedback').html('<span class="text-success"><i class="fa fa-check-circle"></i> ' + res.message + '</span>');
                $('#sku').removeClass('parsley-error');
            }
        });
    }

    // 1. Live validation on Blur & Change for SKU
    $('#sku').on('blur change', function(){
        validateSkuOnServer($(this).val().trim());
    });

    // Randomize SKU button
    $('#btnRandomSku').click(function(){
        let randomVal = 'BRG' + String(Math.floor(100 + Math.random() * 900));
        $('#sku').val(randomVal);
        validateSkuOnServer(randomVal);
    });

    // 2. Live validation on Blur & Change for Nama Barang
    $('#nama_barang').on('blur change input', function(){
        let val = $(this).val().trim();
        if(val.length === 0) {
            $('#nama-feedback').html('<span class="text-danger"><i class="fa fa-times-circle"></i> Nama barang wajib diisi.</span>');
            $(this).addClass('parsley-error');
        } else if(val.length < 3) {
            $('#nama-feedback').html('<span class="text-warning"><i class="fa fa-info-circle"></i> Nama barang terlalu pendek (minimal 3 karakter).</span>');
            $(this).removeClass('parsley-error');
        } else {
            $('#nama-feedback').html('<span class="text-success"><i class="fa fa-check-circle"></i> Nama barang valid.</span>');
            $(this).removeClass('parsley-error');
        }
    });

    // 3. Live validation on Change for Status
    $('#status').on('change blur', function(){
        $('#status-feedback').html('<span class="text-success"><i class="fa fa-check-circle"></i> Status terpilih.</span>');
    });

    // Perform initial check on load
    if($('#sku').val().trim() !== '') {
        validateSkuOnServer($('#sku').val().trim());
    }
});
</script>
@endsection
