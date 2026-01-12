<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Item;
use App\Models\Transaction;
use App\Models\Borrowing; // Tambahkan Model Borrowing

class ReportController extends Controller
{
    // 1. Halaman Menu Laporan
    public function index()
    {
        return view('admin.reports.index');
    }

    // 2. Laporan Aset Tetap (Bisa Filter Ruangan Nanti)
    public function asset(Request $request)
    {
        $items = Item::where('is_consumable', 0)
            ->with(['category', 'room'])
            ->orderBy('code', 'asc')
            ->get();

        return view('admin.reports.print_asset', compact('items'));
    }

    // 3. Laporan Stok Habis Pakai
    public function consumable(Request $request)
    {
        $items = Item::where('is_consumable', 1)
            ->orderBy('name', 'asc')
            ->get();

        return view('admin.reports.print_consumable', compact('items'));
    }

    // 4. Laporan Transaksi (Masuk/Keluar)
    public function transaction(Request $request)
    {
        $startDate = $request->start_date ?? date('Y-m-01');
        $endDate   = $request->end_date ?? date('Y-m-d');

        $transactions = Transaction::whereBetween('date', [$startDate, $endDate])
            ->with(['item', 'user'])
            ->orderBy('date', 'desc') // Urutkan dari yang terbaru
            ->get();

        return view('admin.reports.print_transaction', compact('transactions', 'startDate', 'endDate'));
    }

    // 5. [BARU] Laporan Peminjaman
    public function borrowing(Request $request)
    {
        $startDate = $request->start_date ?? date('Y-m-01');
        $endDate   = $request->end_date ?? date('Y-m-d');

        // Ambil data peminjaman berdasarkan tanggal pinjam
        $borrowings = Borrowing::whereBetween('borrow_date', [$startDate, $endDate])
            ->with(['item'])
            ->orderBy('borrow_date', 'desc')
            ->get();

        return view('admin.reports.print_borrowing', compact('borrowings', 'startDate', 'endDate'));
    }
}
