<?php

namespace App\Models\Purchase;

use App\Models\Master\Barang;
use Illuminate\Database\Eloquent\Model;

class GoodsReceiptDetail extends Model
{
    protected $table = 'detail_penerimaan_barang';
    protected $primaryKey = 'id_detail';
    public $timestamps = false;

    protected $fillable = [
        'no_penerimaan',
        'sku',
        'kuantitas',
        'kondisi_barang',
        'keterangan',
    ];

    protected $casts = [
        'kuantitas' => 'integer',
    ];

    public function goodsReceipt()
    {
        return $this->belongsTo(GoodsReceipt::class, 'no_penerimaan', 'no_penerimaan');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'sku', 'sku');
    }
}
