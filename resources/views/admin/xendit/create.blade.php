@extends('admin.layouts.admin')

@section('title', 'Buat Invoice Pembayaran Xendit')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul style="margin-bottom:0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.xendit.store') }}" method="POST" class="form-horizontal form-label-left">
                @csrf

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">No. Invoice Penjualan</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <select id="no_invoice_jual" name="no_invoice_jual" class="form-control">
                            <option value="">-- Tanpa referensi invoice --</option>
                            @foreach ($invoices as $invoice)
                                <option value="{{ $invoice->no_invoice_jual }}" {{ old('no_invoice_jual') === $invoice->no_invoice_jual ? 'selected' : '' }}>
                                    {{ $invoice->no_invoice_jual }}
                                </option>
                            @endforeach
                        </select>
                        @error('no_invoice_jual') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Jumlah (IDR) <span class="required">*</span></label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input id="amount" type="number" step="any" min="1" name="amount" class="form-control" value="{{ old('amount') }}" required>
                        @error('amount') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Email Pembayar</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="email" name="payer_email" class="form-control" value="{{ old('payer_email') }}">
                        @error('payer_email') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <label class="control-label col-md-3 col-sm-3 col-xs-12">Deskripsi</label>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <input type="text" name="description" class="form-control" value="{{ old('description') }}">
                        @error('description') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>

                <div class="form-group">
                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                        <a class="btn btn-primary" href="{{ route('admin.xendit.index') }}">Batal</a>
                        <button type="submit" class="btn btn-success">Buat Invoice</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            var invoiceSelect = document.getElementById('no_invoice_jual');
            var amountInput = document.getElementById('amount');

            if (invoiceSelect && amountInput) {
                invoiceSelect.addEventListener('change', function () {
                    var invoiceNo = this.value;
                    if (!invoiceNo) return;

                    fetch('/admin/sales/invoices/' + invoiceNo + '/amount-json')
                        .then(function (res) { return res.json(); })
                        .then(function (data) {
                            if (data && data.sisa_tagihan !== undefined) {
                                amountInput.value = data.sisa_tagihan;
                            }
                        })
                        .catch(function (err) {
                            console.error('Error fetching sales invoice amount:', err);
                        });
                });
            }
        });
    </script>
@endsection
