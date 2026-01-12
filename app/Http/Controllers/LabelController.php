<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;

class LabelController extends Controller
{
    // 1. Halaman Pilih Barang (HANYA ASET TETAP)
    public function index()
    {
        // Filter: is_consumable = 0 (Hanya Barang Tetap)
        $items = Item::where('is_consumable', 0)
            ->with(['category', 'room'])
            ->orderBy('created_at', 'desc') // Urutkan dari yang terbaru
            ->get();

        return view('admin.labels.index', compact('items'));
    }

    // 2. Proses Cetak (Preview Label)
    public function print(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
        ], [
            'ids.required' => 'Centang minimal satu barang untuk dicetak!'
        ]);

        // Ambil data barang sesuai ID yang dicentang
        $items = Item::whereIn('id', $request->ids)->with('room')->get();

        return view('admin.labels.print', compact('items'));
    }
}
