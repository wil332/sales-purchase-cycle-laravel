<?php

namespace App\Models\Purchase;

use Illuminate\Database\Eloquent\Model;

class PurchasePaymentDetail extends Model
{
    protected $table = 'detail_pelunasan_pembelian';
    protected $primaryKey = 'id_detail';
    public $timestamps = false;

    protected $fillable = [
        'no_pembayaran_beli',
        'no_invoice_beli',
        'jumlah_dialokasikan',
        'keterangan',
    ];

    protected $casts = [
        'jumlah_dialokasikan' => 'decimal:2',
    ];

    public function purchasePayment()
    {
        return $this->belongsTo(PurchasePayment::class, 'no_pembayaran_beli', 'no_pembayaran_beli');
    }

    public function purchaseInvoice()
    {
        return $this->belongsTo(PurchaseInvoice::class, 'no_invoice_beli', 'no_invoice_beli');
    }
}
