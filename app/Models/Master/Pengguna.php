<?php

namespace App\Models\Master;

use App\Models\Purchase\PurchaseRequest;
use App\Models\Purchase\PurchaseOrder;
use App\Models\Purchase\GoodsReceipt;
use App\Models\Purchase\PurchaseInvoice;
use App\Models\Purchase\PurchasePayment;
use App\Models\Sales\SalesOrder;
use App\Models\Sales\Shipment;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class Pengguna extends Model
{
    protected $table = 'm_pengguna';
    protected $primaryKey = 'id_pengguna';

    protected $fillable = [
        'nama_lengkap',
        'username',
        'password',
        'jabatan',
        'keterangan',
        'status',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function setPasswordAttribute($value)
    {
        if (! empty($value)) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    public function scopeJabatan($query, ...$jabatan)
    {
        $jabatan = collect($jabatan)->flatten()->all();
        return $query->whereIn('jabatan', $jabatan)->where('status', 1);
    }

    public function purchaseRequests()
    {
        return $this->hasMany(PurchaseRequest::class, 'id_pengguna', 'id_pengguna');
    }

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'id_pengguna', 'id_pengguna');
    }

    public function goodsReceipts()
    {
        return $this->hasMany(GoodsReceipt::class, 'id_pengguna', 'id_pengguna');
    }

    public function purchaseInvoices()
    {
        return $this->hasMany(PurchaseInvoice::class, 'id_pengguna', 'id_pengguna');
    }

    public function purchasePayments()
    {
        return $this->hasMany(PurchasePayment::class, 'id_pengguna', 'id_pengguna');
    }

    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class, 'id_pengguna', 'id_pengguna');
    }

    public function shipments()
    {
        return $this->hasMany(Shipment::class, 'id_pengguna', 'id_pengguna');
    }
}
