<?php

namespace App\Models\Master;

use App\Models\Purchase\PurchaseOrder;
use App\Models\Purchase\PurchaseInvoice;
use App\Models\Purchase\PurchaseReturn;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    protected $table = 'm_vendor';
    protected $primaryKey = 'id_vendor';

    protected $fillable = [
        'nama_vendor',
        'no_telp',
        'alamat',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function purchaseOrders()
    {
        return $this->hasMany(PurchaseOrder::class, 'id_vendor', 'id_vendor');
    }

    public function purchaseInvoices()
    {
        return $this->hasMany(PurchaseInvoice::class, 'id_vendor', 'id_vendor');
    }

    // nota_retur_barang.id_penjual references m_vendor.id_vendor
    public function purchaseReturns()
    {
        return $this->hasMany(PurchaseReturn::class, 'id_penjual', 'id_vendor');
    }

    public function barangs()
    {
        return $this->belongsToMany(Barang::class, 'vendor_barang', 'id_vendor', 'sku')
                    ->withPivot('harga_beli')
                    ->withTimestamps();
    }
}
