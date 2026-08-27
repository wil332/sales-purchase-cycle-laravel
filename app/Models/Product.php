<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
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
        'status',
    ];

    protected $casts = [
        'status' => 'boolean',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function isActive(): bool
    {
        return $this->status == 1;
    }
}