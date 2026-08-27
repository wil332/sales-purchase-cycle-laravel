@extends('admin.layouts.admin')

@section('title', 'Detail Pembayaran Xendit')

@section('content')
    <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <table class="table table-bordered">
                <tr>
                    <th style="width:25%">Reference ID</th>
                    <td>{{ $payment->reference_id }}</td>
                </tr>
                <tr>
                    <th>No. Invoice Jual</th>
                    <td>{{ $payment->no_invoice_jual ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Xendit Invoice ID</th>
                    <td>{{ $payment->xendit_invoice_id ?? '-' }}</td>
                </tr>
                <tr>
                    <th>Jumlah</th>
                    <td>Rp {{ number_format($payment->amount, 0, ',', '.') }}</td>
                </tr>
                <tr>
    <th>Status</th>
    <td>
        <span id="payment-status" class="label label-default">
            {{ $payment->status }}
        </span>
    </td>
</tr>
                <tr>
                    <th>URL Invoice</th>
                    <td>
                        @if ($payment->invoice_url)
                            <a href="{{ $payment->invoice_url }}" target="_blank" rel="noopener">{{ $payment->invoice_url }}</a>
                        @else
                            -
                        @endif
                    </td>
                </tr>
                <tr>
                    <th>Kadaluarsa</th>
                    <td>{{ optional($payment->expiry_date)->format('d-m-Y H:i') ?? '-' }}</td>
                </tr>
                <tr>
    <th>Dibayar Pada</th>
    <td id="paid-at">
        {{ optional($payment->paid_at)->format('d-m-Y H:i') ?? '-' }}
    </td>
</tr>
                <tr>
    <th>Metode Pembayaran</th>
    <td id="payment-method">
        {{ $payment->payment_method ?? '-' }}
        {{ $payment->payment_channel ? '(' . $payment->payment_channel . ')' : '' }}
    </td>
</tr>
            </table>


            <a class="btn btn-default" href="{{ route('admin.xendit.index') }}">Kembali</a>
        </div>
    </div>
@endsection
@section('scripts')
@parent
<script>

setInterval(function(){

    $.get(
        "{{ route('admin.xendit.poll',$payment->id) }}",
        function(res){

            if(!res.success)
                return;

            $("#payment-status").text(res.status);

            $("#paid-at").text(res.paid_at ?? "-");

            let method = res.payment_method ?? "-";

            if(res.payment_channel){
                method += " ("+res.payment_channel+")";
            }

            $("#payment-method").text(method);

        }
    );

},5000);

let timer = setInterval(function(){

    $.get(
        "{{ route('admin.xendit.poll',$payment->id) }}",
        function(res){

            if(!res.success)
                return;

            $("#payment-status").text(res.status);

            if(res.status==="PAID" || res.status==="SETTLED"){
                clearInterval(timer);
            }

        }
    );

},5000);

</script>

@endsection