<?php

namespace App\Models\Master;

use App\Models\Purchase\PurchaseOrderDetail;
use App\Models\Purchase\PurchaseRequestDetail;
use App\Models\Purchase\GoodsReceiptDetail;
use App\Models\Purchase\PurchaseInvoiceDetail;
use App\Models\Purchase\PurchaseReturnDetail;
use App\Models\Sales\SalesOrderDetail;
use App\Models\Sales\ShipmentDetail;
use App\Models\Sales\SalesInvoiceDetail;
use App\Models\Sales\SalesReturnDetail;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    protected $table = 'm_barang';
    protected $primaryKey = 'sku';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'sku',
        'category_id',
        'nama_barang',
        'keterangan',
        'gambar',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    protected $appends = ['gambar_url'];

    public function getGambarUrlAttribute(): string
    {
        if (!empty($this->gambar)) {
            if (filter_var($this->gambar, FILTER_VALIDATE_URL)) {
                return $this->gambar;
            }
            return asset('storage/' . $this->gambar);
        }

        $keywords = [
            1 => 'laptop,computer',
            2 => 'mouse,keyboard',
            3 => 'harddrive,ssd',
            4 => 'router,network',
            5 => 'smartphone,gadget',
        ];

        $keyword = $keywords[$this->category_id ?? 1] ?? 'technology,gadget';
        $lockSeed = abs(crc32($this->sku ?? 'BRG001'));

        return "https://loremflickr.com/400/400/{$keyword}?lock={$lockSeed}";
    }

    public function category()
    {
        return $this->belongsTo(\App\Models\Category::class, 'category_id', 'id');
    }

    // Purchase side
    public function purchaseRequestDetails()
    {
        return $this->hasMany(PurchaseRequestDetail::class, 'sku', 'sku');
    }

    public function purchaseOrderDetails()
    {
        return $this->hasMany(PurchaseOrderDetail::class, 'sku', 'sku');
    }

    public function goodsReceiptDetails()
    {
        return $this->hasMany(GoodsReceiptDetail::class, 'sku', 'sku');
    }

    public function purchaseInvoiceDetails()
    {
        return $this->hasMany(PurchaseInvoiceDetail::class, 'sku', 'sku');
    }

    public function purchaseReturnDetails()
    {
        return $this->hasMany(PurchaseReturnDetail::class, 'sku', 'sku');
    }

    // Sales side
    public function salesOrderDetails()
    {
        return $this->hasMany(SalesOrderDetail::class, 'sku', 'sku');
    }

    public function shipmentDetails()
    {
        return $this->hasMany(ShipmentDetail::class, 'sku', 'sku');
    }

    public function salesInvoiceDetails()
    {
        return $this->hasMany(SalesInvoiceDetail::class, 'sku', 'sku');
    }

    public function salesReturnDetails()
    {
        return $this->hasMany(SalesReturnDetail::class, 'sku', 'sku');
    }

    public function vendors()
    {
        return $this->belongsToMany(Vendor::class, 'vendor_barang', 'sku', 'id_vendor')
                    ->withPivot('harga_beli')
                    ->withTimestamps();
    }
}
