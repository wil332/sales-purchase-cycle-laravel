<?php

namespace App\Models\Sales;

use App\Models\Master\Pelanggan;
use App\Models\Master\Pengguna;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SalesOrder extends Model
{
    protected $table = 'nota_order_penjualan';
    protected $primaryKey = 'no_order_penjualan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'no_order_penjualan',
        'tanggal',
        'id_pelanggan',
        'id_pengguna',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'status' => 'integer',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    // Sales yang melayani
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function details()
    {
        return $this->hasMany(SalesOrderDetail::class, 'no_order_penjualan', 'no_order_penjualan');
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'no_order_penjualan', 'no_order_penjualan');
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
     * Recalculate status for this sales order:
     * 1 = Aktif, 2 = Diproses, 3 = Selesai
     */
    public function updateOrderStatus(): void
    {
        if ($this->status === 0) {
            return; // Maintain Canceled status
        }

        $totalDipesan = $this->details->sum('kuantitas');
        $totalDikirim = \App\Models\Sales\ShipmentDetail::whereHas('shipment', function ($q) {
            $q->where('no_order_penjualan', $this->no_order_penjualan)->where('status', '>=', 1);
        })->sum('kuantitas_dikirim');

        if ($totalDipesan > 0 && $totalDikirim >= $totalDipesan) {
            $newStatus = 3; // Selesai
        } elseif ($totalDikirim > 0) {
            $newStatus = 2; // Diproses
        } else {
            $newStatus = 1; // Aktif
        }

        if ($this->status !== $newStatus) {
            $this->update(['status' => $newStatus]);
        }
    }
}
