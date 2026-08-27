<?php

namespace App\Models\Purchase;

use App\Models\Master\Pengguna;
use Illuminate\Database\Eloquent\Model;

class PurchaseRequest extends Model
{
    protected $table = 'nota_permintaan_pembelian';
    protected $primaryKey = 'no_faktur';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'no_faktur',
        'tanggal',
        'id_pengguna',
        'tanggal_diperlukan',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'tanggal_diperlukan' => 'date',
        'status' => 'integer',
    ];

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    
    public function details()
    {
        return $this->hasMany(PurchaseRequestDetail::class, 'no_faktur', 'no_faktur');
    }

    
    public static function createWithDetails(array $headerData, array $detailRows): self
    {
        return \Illuminate\Support\Facades\DB::transaction(function () use ($headerData, $detailRows) {
            $request = self::create($headerData);

            foreach ($detailRows as $row) {
                $request->details()->create($row);
            }

            return $request;
        });
    }

    /**
     * Ganti seluruh baris detail (hapus yang lama, simpan yang baru) dalam satu transaksi.
     */
    public function syncDetails(array $detailRows): self
    {
        \Illuminate\Support\Facades\DB::transaction(function () use ($detailRows) {
            $this->details()->delete();

            foreach ($detailRows as $row) {
                $this->details()->create($row);
            }
        });

        return $this->fresh('details');
    }
}
