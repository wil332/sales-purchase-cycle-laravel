<?php

namespace App\Models;

use App\Models\Sales\SalesInvoice;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'reference_id',
        'no_invoice_jual',
        'xendit_invoice_id',
        'external_id',
        'payer_email',
        'description',
        'amount',
        'paid_amount',
        'currency',
        'status',
        'invoice_url',
        'payment_method',
        'payment_channel',
        'expiry_date',
        'paid_at',
        'raw_response',
    ];

    protected $casts = [
        'amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'expiry_date' => 'datetime',
        'paid_at' => 'datetime',
        'raw_response' => 'array',
    ];

    public function salesInvoice()
    {
        return $this->belongsTo(SalesInvoice::class, 'no_invoice_jual', 'no_invoice_jual');
    }
}
