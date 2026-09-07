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
        return $this->belongsTo(Category::class, 'category_id', 'id');
    }

    public function isActive(): bool
    {
        return $this->status == 1;
    }
}