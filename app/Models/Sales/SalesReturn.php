<?php

namespace App\Models\Sales;

use App\Models\Master\Pelanggan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SalesReturn extends Model
{
    protected $table = 'nota_retur_penjualan';
    protected $primaryKey = 'no_retur_jual';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'no_retur_jual',
        'tanggal',
        'no_invoice_jual',
        'id_pelanggan',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'status' => 'integer',
    ];

    public function salesInvoice()
    {
        return $this->belongsTo(SalesInvoice::class, 'no_invoice_jual', 'no_invoice_jual');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function details()
    {
        return $this->hasMany(SalesReturnDetail::class, 'no_retur_jual', 'no_retur_jual');
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
