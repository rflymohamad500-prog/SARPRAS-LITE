<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;       // Pastikan Model Item ada
use App\Models\Borrowing;  // Pastikan Model Peminjaman ada

class DashboardController extends Controller
{
    public function index()
    {
        // --- 1. HITUNG DATA UNTUK KARTU ATAS ---

        // Total Aset (Laptop, Meja, dll - yang is_consumable = 0)
        $totalAset = Item::where('is_consumable', 0)->count();

        // Total Jenis ATK (Kertas, Spidol, dll - yang is_consumable = 1)
        $totalATK = Item::where('is_consumable', 1)->count();

        // Barang Sedang Dipinjam (Status belum 'returned')
        $sedangDipinjam = Borrowing::where('status', 'borrowed')->count();

        // Stok Menipis (Kurang dari atau sama dengan 5)
        $stokKritis = Item::where('quantity', '<=', 5)
            ->where('quantity', '>', 0) // Jangan hitung yang 0 (biasanya barang rusak/hilang)
            ->count();

        // --- 2. AMBIL DATA UNTUK TABEL ---

        // 5 Peminjaman Terakhir (Biar petugas tau siapa yang baru pinjam)
        $recentBorrowings = Borrowing::with(['item', 'item.room'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 5 Barang yang Stoknya Paling Sedikit (Prioritas Belanja)
        $lowStockItems = Item::where('quantity', '<=', 5)
            ->where('quantity', '>', 0)
            ->orderBy('quantity', 'asc')
            ->take(5)
            ->get();

        // Kirim semua variabel ke View
        return view('admin.dashboard', compact(
            'totalAset',
            'totalATK',
            'sedangDipinjam',
            'stokKritis',
            'recentBorrowings',
            'lowStockItems'
        ));
    }
}
