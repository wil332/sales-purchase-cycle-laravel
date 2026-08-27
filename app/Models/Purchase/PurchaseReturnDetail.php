<?php

namespace App\Models\Purchase;

use App\Models\Master\Barang;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturnDetail extends Model
{
    protected $table = 'detail_retur_barang';
    protected $primaryKey = 'id_detail';
    public $timestamps = false;

    protected $fillable = [
        'nota_retur',
        'sku',
        'jumlah',
        'harga_unit',
        'diskon',
        'total_harga',
        'keterangan',
    ];

    protected $casts = [
        'jumlah' => 'integer',
        'harga_unit' => 'decimal:2',
        'diskon' => 'decimal:2',
        'total_harga' => 'decimal:2',
    ];

    public function purchaseReturn()
    {
        return $this->belongsTo(PurchaseReturn::class, 'nota_retur', 'nota_retur');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'sku', 'sku');
    }

    protected static function booted()
    {
        static::saving(function (self $detail) {
            $subtotal = $detail->harga_unit * $detail->jumlah;
            $detail->total_harga = $subtotal - ($detail->diskon ?? 0);
        });
    }
}
