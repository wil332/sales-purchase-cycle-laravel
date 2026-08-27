<?php

namespace App\Models\Purchase;

use App\Models\Master\Vendor;
use App\Models\Master\Pengguna;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PurchaseOrder extends Model
{
    protected $table = 'nota_order_pembelian';
    protected $primaryKey = 'no_order_pembelian';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'no_order_pembelian',
        'tanggal',
        'id_vendor',
        'id_pengguna',
        'tanggal_dibutuhkan',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'tanggal_dibutuhkan' => 'date',
        'status' => 'integer',
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'id_vendor', 'id_vendor');
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function details()
    {
        return $this->hasMany(PurchaseOrderDetail::class, 'no_order_pembelian', 'no_order_pembelian');
    }

    public function goodsReceipts()
    {
        return $this->hasMany(GoodsReceipt::class, 'no_order_pembelian', 'no_order_pembelian');
    }

    public static function createWithDetails(array $headerData, array $detailRows): self
    {
        return DB::transaction(function () use ($headerData, $detailRows) {
            $order = self::create($headerData);

            foreach ($detailRows as $row) {
                $order->details()->create($row);
            }

            return $order;
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

    /**
     * Recalculate status for this purchase order:
     * 1 = Aktif, 2 = Diproses, 3 = Selesai
     */
    public function updateOrderStatus(): void
    {
        if ($this->status === 0) {
            return; // Maintain Canceled status
        }

        $totalDipesan = $this->details->sum('kuantitas');
        $totalDiterima = \App\Models\Purchase\GoodsReceiptDetail::whereHas('goodsReceipt', function ($q) {
            $q->where('no_order_pembelian', $this->no_order_pembelian)->where('status', '>=', 1);
        })->sum('kuantitas');

        if ($totalDipesan > 0 && $totalDiterima >= $totalDipesan) {
            $newStatus = 3; // Selesai
        } elseif ($totalDiterima > 0) {
            $newStatus = 2; // Diproses
        } else {
            $newStatus = 1; // Aktif
        }

        if ($this->status !== $newStatus) {
            $this->update(['status' => $newStatus]);
        }
    }
}
