<?php

namespace App\Models\Sales;

use App\Models\Master\Barang;
use Illuminate\Database\Eloquent\Model;

class SalesReturnDetail extends Model
{
    protected $table = 'detail_retur_penjualan';
    protected $primaryKey = 'id_detail';
    public $timestamps = false;

    protected $fillable = [
        'no_retur_jual',
        'sku',
        'jumlah_diretur',
        'alasan_retur',
    ];

    protected $casts = [
        'jumlah_diretur' => 'integer',
    ];

    public function salesReturn()
    {
        return $this->belongsTo(SalesReturn::class, 'no_retur_jual', 'no_retur_jual');
    }

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'sku', 'sku');
    }
}
