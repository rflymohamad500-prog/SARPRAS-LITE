<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Borrowing extends Model
{
    protected $guarded = [];

    // Relasi ke Barang
    public function item()
    {
        return $this->belongsTo(Item::class);
    }
}
