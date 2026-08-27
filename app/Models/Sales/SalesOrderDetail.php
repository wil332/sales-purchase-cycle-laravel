<?php

namespace App\Models\Sales;

use App\Models\Master\Barang;
use Illuminate\Database\Eloquent\Model;

class SalesOrderDetail extends Model
{
    protected $table = 'detail_order_penjualan';
    protected $primaryKey = 'id_detail';
    public $timestamps = false;

    protected $fillable = [
        'no_order_penjualan',
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

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'no_order_penjualan', 'no_order_penjualan');
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
