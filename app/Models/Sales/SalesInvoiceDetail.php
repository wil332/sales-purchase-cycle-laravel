<?php

namespace App\Models\Sales;

use App\Models\Master\Barang;
use Illuminate\Database\Eloquent\Model;

class SalesInvoiceDetail extends Model
{
    protected $table = 'detail_tagihan_penjualan';
    protected $primaryKey = 'id_detail';
    public $timestamps = false;

    protected $fillable = [
        'no_invoice_jual',
        'sku',
        'kuantitas',
        'harga_jual',
        'diskon',
        'total_harga',
        'keterangan',
    ];

    protected $casts = [
        'kuantitas' => 'integer',
        'harga_jual' => 'decimal:2',
        'diskon' => 'decimal:2',
        'total_harga' => 'decimal:2',
    ];

    public function salesInvoice()
    {
        return $this->belongsTo(SalesInvoice::class, 'no_invoice_jual', 'no_invoice_jual');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'sku', 'sku');
    }

    protected static function booted()
    {
        static::saving(function (self $detail) {
            $subtotal = $detail->harga_jual * $detail->kuantitas;
            $detail->total_harga = $subtotal - ($detail->diskon ?? 0);
        });
    }
}
