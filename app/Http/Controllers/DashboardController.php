<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Borrowing;
use App\Models\Category;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // === UPDATED: HANYA MENGHITUNG SESUAI TIPE ===

        // 1. STATISTIK UTAMA
        $totalAset      = Item::where('is_consumable', 0)->count(); // Hanya Aset
        $totalATK       = Item::where('is_consumable', 1)->count(); // Hanya ATK
        $sedangDipinjam = Borrowing::where('status', 'borrowed')->count();

        // 2. STOK KRITIS (Kotak Merah di Atas)
        // HANYA untuk barang habis pakai (ATK)
        $stokKritis = Item::where('is_consumable', 1)
            ->where('quantity', '<=', 5)
            ->where('quantity', '>', 0)
            ->count();

        // 3. TABEL PEMINJAMAN TERAKHIR
        $recentBorrowings = Borrowing::with(['item'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // 4. LIST PERINGATAN STOK MENIPIS (Kotak Merah Kanan)
        // HANYA untuk barang habis pakai (ATK)
        $lowStockItems = Item::where('is_consumable', 1) // <--- KUNCI PERBAIKANNYA DI SINI
            ->where('quantity', '<=', 5)
            ->where('quantity', '>', 0)
            ->orderBy('quantity', 'asc')
            ->take(5)
            ->get();

        // 5. DATA GRAFIK
        $categories = Category::withCount('items')->get();
        $chartLabels = $categories->pluck('name');
        $chartData   = $categories->pluck('items_count');

        return view('admin.dashboard', compact(
            'totalAset',
            'totalATK',
            'sedangDipinjam',
            'stokKritis',
            'recentBorrowings',
            'lowStockItems', // Variabel ini yang dikirim ke view
            'chartLabels',
            'chartData'
        ));
    }
}
