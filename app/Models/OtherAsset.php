<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OtherAsset extends Model
{
    use HasFactory;

    // Izinkan semua kolom ini diisi
    protected $fillable = [
        'code',
        'barcode',
        'name',
        'category',
        'location',
        'amount',
        'unit',
        'condition',
        'price',
        'source_of_fund',
        'acquisition_date',
        'image'
    ];
}
