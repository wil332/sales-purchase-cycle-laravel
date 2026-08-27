<?php

namespace App\Models\Sales;

use App\Models\Master\Barang;
use Illuminate\Database\Eloquent\Model;

class ShipmentDetail extends Model
{
    protected $table = 'detail_pengiriman_barang';
    protected $primaryKey = 'id_detail';
    public $timestamps = false;

    protected $fillable = [
        'no_pengiriman',
        'sku',
        'kuantitas_dikirim',
        'keterangan',
    ];

    protected $casts = [
        'kuantitas_dikirim' => 'integer',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'no_pengiriman', 'no_pengiriman');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'sku', 'sku');
    }
}
