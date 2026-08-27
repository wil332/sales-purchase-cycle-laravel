<?php

namespace App\Models\Purchase;

use App\Models\Master\Barang;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequestDetail extends Model
{
    protected $table = 'detail_permintaan_pembelian';
    protected $primaryKey = 'id_detail';
    public $timestamps = false;

    protected $fillable = [
        'no_faktur',
        'sku',
        'kuantitas',
        'keterangan',
    ];

    protected $casts = [
        'kuantitas' => 'integer',
    ];

    public function purchaseRequest()
    {
        return $this->belongsTo(PurchaseRequest::class, 'no_faktur', 'no_faktur');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'sku', 'sku');
    }
}
