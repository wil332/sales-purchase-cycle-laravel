<?php

namespace App\Models\Master;

use App\Models\Sales\SalesOrder;
use App\Models\Sales\SalesInvoice;
use App\Models\Sales\SalesReturn;
use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'm_pelanggan';
    protected $primaryKey = 'id_pelanggan';

    protected $fillable = [
        'nama_pelanggan',
        'no_telp',
        'alamat',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function salesOrders()
    {
        return $this->hasMany(SalesOrder::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function salesInvoices()
    {
        return $this->hasMany(SalesInvoice::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function salesReturns()
    {
        return $this->hasMany(SalesReturn::class, 'id_pelanggan', 'id_pelanggan');
    }
}
