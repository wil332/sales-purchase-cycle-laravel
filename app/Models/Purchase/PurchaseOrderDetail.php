<?php

namespace App\Models\Purchase;

use App\Models\Master\Barang;
use Illuminate\Database\Eloquent\Model;

class PurchaseOrderDetail extends Model
{
    protected $table = 'detail_order_pembelian';
    protected $primaryKey = 'id_detail';
    public $timestamps = false;

    protected $fillable = [
        'no_order_pembelian',
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

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'no_order_pembelian', 'no_order_pembelian');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'sku', 'sku');
    }

    /**
     * Hitung ulang total_harga = (harga_unit * kuantitas) - diskon, dipanggil sebelum simpan.
     */
    protected static function booted()
    {
        static::saving(function (self $detail) {
            $subtotal = $detail->harga_unit * $detail->kuantitas;
            $detail->total_harga = $subtotal - ($detail->diskon ?? 0);
        });
    }
}
