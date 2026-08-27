<?php

namespace App\Models\Purchase;

use App\Models\Master\Vendor;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PurchaseReturn extends Model
{
    protected $table = 'nota_retur_barang';
    protected $primaryKey = 'nota_retur';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'nota_retur',
        'nomor_urut',
        'atas_penerimaan_nomor',
        'tanggal',
        'id_penjual',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'status' => 'integer',
    ];

    public function goodsReceipt()
    {
        return $this->belongsTo(GoodsReceipt::class, 'atas_penerimaan_nomor', 'no_penerimaan');
    }

    // id_penjual references m_vendor.id_vendor
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'id_penjual', 'id_vendor');
    }

    public function details()
    {
        return $this->hasMany(PurchaseReturnDetail::class, 'nota_retur', 'nota_retur');
    }

    public static function createWithDetails(array $headerData, array $detailRows): self
    {
        return DB::transaction(function () use ($headerData, $detailRows) {
            $return = self::create($headerData);

            foreach ($detailRows as $row) {
                $return->details()->create($row);
            }

            return $return;
        });
    }

    public function syncDetails(array $detailRows): self
    {
        DB::transaction(function () use ($detailRows) {
            $this->details()->delete();

            foreach ($detailRows as $row) {
                $this->details()->create($row);
            }
        });

        return $this->fresh('details');
    }
}
