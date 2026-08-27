<?php

namespace App\Models\Sales;

use Illuminate\Database\Eloquent\Model;

class SalesPayment extends Model
{
    protected $table = 'nota_pelunasan_penjualan';
    protected $primaryKey = 'no_pembayaran';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'no_pembayaran',
        'tanggal_bayar',
        'no_invoice_jual',
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

    // Catatan: tidak ada tabel detail_pelunasan_penjualan di database,
    // beda dengan sisi pembelian (nota_pelunasan_pembelian) yang punya detail.
    public function salesInvoice()
    {
        return $this->belongsTo(SalesInvoice::class, 'no_invoice_jual', 'no_invoice_jual');
    }
}
