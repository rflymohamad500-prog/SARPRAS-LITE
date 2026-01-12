<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    // Agar kolom 'name' bisa diisi
    protected $fillable = ['name'];

    // --- TAMBAHAN WAJIB AGAR TIDAK ERROR ---
    // Fungsi ini memberitahu Laravel bahwa:
    // "Satu Kategori (Category) memiliki banyak Barang (Item)"
    public function items()
    {
        return $this->hasMany(Item::class);
    }
}
