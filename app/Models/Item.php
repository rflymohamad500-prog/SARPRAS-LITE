<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Category;
use App\Models\Room;

class Item extends Model
{
    // Kolom yang boleh diisi
    protected $fillable = [
        'code',
        'name',
        'category_id',
        'room_id',
        'quantity',
        'unit',
        'barcode',
        'condition',
        'price',
        'source',
        'purchase_date',
        'image',
        'is_consumable',
        'qr_code_data'
    ];

    // Relasi ke Kategori
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    // Relasi ke Ruangan
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
