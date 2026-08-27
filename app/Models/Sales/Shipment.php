<?php

namespace App\Models\Sales;

use App\Models\Master\Pengguna;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Shipment extends Model
{
    protected $table = 'nota_pengiriman_barang';
    protected $primaryKey = 'no_pengiriman';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'no_pengiriman',
        'tanggal_kirim',
        'no_order_penjualan',
        'id_pengguna',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'tanggal_kirim' => 'date',
        'status' => 'integer',
    ];

    public function salesOrder()
    {
        return $this->belongsTo(SalesOrder::class, 'no_order_penjualan', 'no_order_penjualan');
    }

    // Staff gudang yang melepas barang
    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    public function details()
    {
        return $this->hasMany(ShipmentDetail::class, 'no_pengiriman', 'no_pengiriman');
    }

    public function salesInvoices()
    {
        return $this->hasMany(SalesInvoice::class, 'no_pengiriman', 'no_pengiriman');
    }

    public static function createWithDetails(array $headerData, array $detailRows): self
    {
        return DB::transaction(function () use ($headerData, $detailRows) {
            $shipment = self::create($headerData);

            foreach ($detailRows as $row) {
                $shipment->details()->create($row);
            }

            return $shipment;
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
