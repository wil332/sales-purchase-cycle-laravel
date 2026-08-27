<?php

namespace App\Models\Sales;

use App\Models\Master\Pelanggan;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class SalesInvoice extends Model
{
    protected $table = 'nota_tagihan_penjualan';
    protected $primaryKey = 'no_invoice_jual';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'no_invoice_jual',
        'tanggal',
        'no_pengiriman',
        'id_pelanggan',
        'jatuh_tempo',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jatuh_tempo' => 'date',
        'status' => 'integer',
    ];

    public function shipment()
    {
        return $this->belongsTo(Shipment::class, 'no_pengiriman', 'no_pengiriman');
    }

    public function pelanggan()
    {
        return $this->belongsTo(Pelanggan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function details()
    {
        return $this->hasMany(SalesInvoiceDetail::class, 'no_invoice_jual', 'no_invoice_jual');
    }

    public function payments()
    {
        return $this->hasMany(SalesPayment::class, 'no_invoice_jual', 'no_invoice_jual');
    }

    public function salesReturns()
    {
        return $this->hasMany(SalesReturn::class, 'no_invoice_jual', 'no_invoice_jual');
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
     * Recalculate payment status for this sales invoice:
     * 1 = Belum Dibayar, 2 = Dibayar Sebagian, 3 = Lunas
     */
    public function updatePaymentStatus(): void
    {
        if ($this->status === 0) {
            return; // Maintain Canceled status
        }

        $totalTagihan = $this->details->sum(function ($d) {
            return ($d->kuantitas * $d->harga_jual) - ($d->diskon ?? 0);
        });

        $sudahDibayarManual = SalesPayment::where('no_invoice_jual', $this->no_invoice_jual)
            ->where('status', 1)
            ->sum('jumlah_bayar');

        $sudahDibayarXendit = \App\Models\Payment::where('no_invoice_jual', $this->no_invoice_jual)
            ->whereIn('status', ['PAID', 'SETTLED', 'PAID/SETTLED'])
            ->sum(DB::raw('COALESCE(paid_amount, amount)'));

        $totalSudahDibayar = $sudahDibayarManual + $sudahDibayarXendit;

        if ($totalTagihan > 0 && $totalSudahDibayar >= $totalTagihan) {
            $newStatus = 3; // Lunas
        } elseif ($totalSudahDibayar > 0) {
            $newStatus = 2; // Dibayar Sebagian
        } else {
            $newStatus = 1; // Belum Dibayar
        }

        if ($this->status !== $newStatus) {
            $this->update(['status' => $newStatus]);
        }
    }
}
