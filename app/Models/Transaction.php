<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'item_id',
        'user_id',
        'type',
        'amount',
        'date',
        'notes'
    ];

    // Relasi ke Barang
    public function item()
    {
        return $this->belongsTo(Item::class);
    }

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
