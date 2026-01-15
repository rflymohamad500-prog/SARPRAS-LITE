<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Category; // <--- TAMBAHKAN INI (PENTING)
use App\Models\Transaction;
use App\Models\Borrowing;

class ReportController extends Controller
{
    // 1. Halaman Menu Laporan (UPDATE: Kirim data kategori untuk dropdown)
    public function index()
    {
        // Kita ambil kategori di sini supaya bisa buat Dropdown Filter di halaman menu
        $categories = Category::all();

        return view('admin.reports.index', compact('categories'));
    }

    // 2. Laporan Aset Tetap (UPDATE: Tambahkan Logika Filter)
    public function asset(Request $request)
    {
        // Query Dasar: Aset Tetap
        $query = Item::where('is_consumable', 0)
            ->with(['category', 'room'])
            ->orderBy('code', 'asc');

        // --- LOGIKA FILTER KATEGORI ---
        $selectedCategory = 'Semua Kategori'; // Default judul

        if ($request->filled('category_id')) {
            // Filter Query
            $query->where('category_id', $request->category_id);

            // Ambil nama kategori untuk judul laporan
            $cat = Category::find($request->category_id);
            if ($cat) {
                $selectedCategory = $cat->name;
            }
        }

        $items = $query->get();

        // Kirim $items dan $selectedCategory ke view cetak
        return view('admin.reports.print_asset', compact('items', 'selectedCategory'));
    }

    // 3. Laporan Stok Habis Pakai (TETAP)
    public function consumable(Request $request)
    {
        $items = Item::where('is_consumable', 1)
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.reports.print_consumable', compact('items'));
    }

    // 4. Laporan Transaksi (TETAP)
    public function transaction(Request $request)
    {
        $startDate = $request->start_date ?? date('Y-m-01');
        $endDate   = $request->end_date ?? date('Y-m-d');

        $transactions = Transaction::whereBetween('date', [$startDate, $endDate])
            ->with(['item', 'user'])
            ->orderBy('date', 'desc')
            ->get();

        return view('admin.reports.print_transaction', compact('transactions', 'startDate', 'endDate'));
    }

    // 5. Laporan Peminjaman (TETAP)
    public function borrowing(Request $request)
    {
        $startDate = $request->start_date ?? date('Y-m-01');
        $endDate   = $request->end_date ?? date('Y-m-d');

        $borrowings = Borrowing::whereBetween('borrow_date', [$startDate, $endDate])
            ->with(['item'])
            ->orderBy('borrow_date', 'desc')
            ->get();

        return view('admin.reports.print_borrowing', compact('borrowings', 'startDate', 'endDate'));
    }
}
