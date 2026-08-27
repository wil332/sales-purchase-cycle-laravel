<?php

namespace App\Models\Purchase;

use App\Models\Master\Vendor;
use App\Models\Master\Pengguna;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class PurchaseInvoice extends Model
{
    protected $table = 'nota_tagihan_pembelian';
    protected $primaryKey = 'no_invoice_beli';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'no_invoice_beli',
        'tanggal',
        'no_penerimaan',
        'id_vendor',
        'id_pengguna',
        'jatuh_tempo',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jatuh_tempo' => 'date',
        'status' => 'integer',
    ];

    public function goodsReceipt()
    {
        return $this->belongsTo(GoodsReceipt::class, 'no_penerimaan', 'no_penerimaan');
    }

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
        return $this->hasMany(PurchaseInvoiceDetail::class, 'no_invoice_beli', 'no_invoice_beli');
    }

    /**
     * Alokasi pembayaran yang menyentuh invoice ini (lewat detail_pelunasan_pembelian).
     */
    public function paymentAllocations()
    {
        return $this->hasMany(PurchasePaymentDetail::class, 'no_invoice_beli', 'no_invoice_beli');
    }

    public function payments()
    {
        return $this->belongsToMany(
            PurchasePayment::class,
            'detail_pelunasan_pembelian',
            'no_invoice_beli',
            'no_pembayaran_beli'
        )->withPivot('jumlah_dialokasikan', 'keterangan');
    }

    public static function createWithDetails(array $headerData, array $detailRows): self
    {
        return DB::transaction(function () use ($headerData, $detailRows) {
            $invoice = self::create($headerData);

            foreach ($detailRows as $row) {
                $invoice->details()->create($row);
            }

            return $invoice;
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
     * Recalculate payment status for this purchase invoice:
     * 1 = Belum Dibayar, 2 = Dibayar Sebagian, 3 = Lunas
     */
    public function updatePaymentStatus(): void
    {
        if ($this->status === 0) {
            return; // Maintain Canceled status
        }

        $totalTagihan = $this->details->sum(function ($d) {
            return ($d->kuantitas * $d->harga_unit) - ($d->diskon ?? 0);
        });

        $sudahDibayar = PurchasePaymentDetail::where('no_invoice_beli', $this->no_invoice_beli)
            ->whereHas('purchasePayment', function ($q) {
                $q->where('status', 1);
            })
            ->sum('jumlah_dialokasikan');

        if ($totalTagihan > 0 && $sudahDibayar >= $totalTagihan) {
            $newStatus = 3; // Lunas
        } elseif ($sudahDibayar > 0) {
            $newStatus = 2; // Dibayar Sebagian
        } else {
            $newStatus = 1; // Belum Dibayar
        }

        if ($this->status !== $newStatus) {
            $this->update(['status' => $newStatus]);
        }
    }
}
