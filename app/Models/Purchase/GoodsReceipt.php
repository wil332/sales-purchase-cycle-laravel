<?php

namespace App\Models\Purchase;

use App\Models\Master\Pengguna;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class GoodsReceipt extends Model
{
    protected $table = 'nota_penerimaan_barang';
    protected $primaryKey = 'no_penerimaan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'no_penerimaan',
        'tanggal_penerimaan',
        'no_order_pembelian',
        'id_pengguna',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'tanggal_penerimaan' => 'date',
        'status' => 'integer',
    ];

    public function purchaseOrder()
    {
        return $this->belongsTo(PurchaseOrder::class, 'no_order_pembelian', 'no_order_pembelian');
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function details()
    {
        return $this->hasMany(GoodsReceiptDetail::class, 'no_penerimaan', 'no_penerimaan');
    }

    public function purchaseInvoices()
    {
        return $this->hasMany(PurchaseInvoice::class, 'no_penerimaan', 'no_penerimaan');
    }

    // nota_retur_barang.atas_penerimaan_nomor -> nota_penerimaan_barang.no_penerimaan
    public function purchaseReturns()
    {
        return $this->hasMany(PurchaseReturn::class, 'atas_penerimaan_nomor', 'no_penerimaan');
    }

    public static function createWithDetails(array $headerData, array $detailRows): self
    {
        return DB::transaction(function () use ($headerData, $detailRows) {
            $receipt = self::create($headerData);

            foreach ($detailRows as $row) {
                $receipt->details()->create($row);
            }

            return $receipt;
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
