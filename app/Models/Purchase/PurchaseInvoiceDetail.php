<?php

namespace App\Models\Purchase;

use App\Models\Master\Barang;
use Illuminate\Database\Eloquent\Model;

class PurchaseInvoiceDetail extends Model
{
    protected $table = 'detail_tagihan_pembelian';
    protected $primaryKey = 'id_detail';
    public $timestamps = false;

    protected $fillable = [
        'no_invoice_beli',
        'sku',
        'kuantitas',
        'harga_unit',
        'diskon',
        'total_harga',
        'keterangan',
    ];

    protected $casts = [
        'kuantitas' => 'integer',
        'harga_unit' => 'decimal:2',
        'diskon' => 'decimal:2',
        'total_harga' => 'decimal:2',
    ];

    public function purchaseInvoice()
    {
        return $this->belongsTo(PurchaseInvoice::class, 'no_invoice_beli', 'no_invoice_beli');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'sku', 'sku');
    }

    protected static function booted()
    {
        static::saving(function (self $detail) {
            $subtotal = $detail->harga_unit * $detail->kuantitas;
            $detail->total_harga = $subtotal - ($detail->diskon ?? 0);
        });
    }
}
