<?php

namespace App\Models\Purchase;

use App\Models\Master\Pengguna;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PurchasePayment extends Model
{
    protected $table = 'nota_pelunasan_pembelian';
    protected $primaryKey = 'no_pembayaran_beli';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'no_pembayaran_beli',
        'tanggal_bayar',
        'no_invoice_beli',
        'id_pengguna',
        'jumlah_bayar',
        'metode_bayar',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'tanggal_bayar' => 'date',
        'jumlah_bayar' => 'decimal:2',
        'status' => 'integer',
    ];

    // Invoice utama yang tercatat di header (tetap dipertahankan untuk kompatibilitas).
    public function purchaseInvoice()
    {
        return $this->belongsTo(PurchaseInvoice::class, 'no_invoice_beli', 'no_invoice_beli');
    }

    public function pengguna()
    {
        return $this->belongsTo(Pengguna::class, 'id_pengguna', 'id_pengguna');
    }

    /**
     * Baris alokasi pembayaran ke satu atau beberapa invoice (detail_pelunasan_pembelian).
     */
    public function details()
    {
        return $this->hasMany(PurchasePaymentDetail::class, 'no_pembayaran_beli', 'no_pembayaran_beli');
    }

    public function invoices()
    {
        return $this->belongsToMany(
            PurchaseInvoice::class,
            'detail_pelunasan_pembelian',
            'no_pembayaran_beli',
            'no_invoice_beli'
        )->withPivot('jumlah_dialokasikan', 'keterangan');
    }

    public static function createWithDetails(array $headerData, array $detailRows): self
    {
        return DB::transaction(function () use ($headerData, $detailRows) {
            $payment = self::create($headerData);

            foreach ($detailRows as $row) {
                $payment->details()->create($row);
            }

            return $payment;
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
