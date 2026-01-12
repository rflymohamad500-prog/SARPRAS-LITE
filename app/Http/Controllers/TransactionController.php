<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class TransactionController extends Controller
{
    // Menampilkan Riwayat Transaksi
    public function index()
    {
        // Urutkan dari yang terbaru
        $transactions = Transaction::with(['item', 'user'])->latest()->get();
        return view('admin.transactions.index', compact('transactions'));
    }

    // Form Barang Masuk (Penambahan Stok)
    public function createIn()
    {
        // Hanya ambil barang habis pakai (consumable) untuk fitur stok ini
        $items = Item::where('is_consumable', 1)->get();
        return view('admin.transactions.create_in', compact('items'));
    }

    // Proses Simpan Barang Masuk
    public function storeIn(Request $request)
    {
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'amount' => 'required|integer|min:1',
            'date' => 'required|date',
        ]);

        // Gunakan DB Transaction agar data aman (Atomicity)
        DB::transaction(function () use ($request) {
            // 1. Catat di tabel transactions
            Transaction::create([
                'item_id' => $request->item_id,
                'user_id' => Auth::id(),
                'type' => 'masuk',
                'amount' => $request->amount,
                'date' => $request->date,
                'notes' => $request->notes,
            ]);

            // 2. Tambah stok di tabel items
            $item = Item::find($request->item_id);
            $item->increment('quantity', $request->amount);
        });

        return redirect()->route('admin.transactions.index')->with('success', 'Stok berhasil ditambahkan!');
    }

    // Form Barang Keluar (Pengurangan Stok) + Scanner
    public function createOut()
    {
        // Ambil barang yang stoknya > 0
        $items = Item::where('is_consumable', 1)
            ->where('quantity', '>', 0)
            ->get();

        return view('admin.transactions.create_out', compact('items'));
    }

    // Proses Simpan Barang Keluar
    public function storeOut(Request $request)
    {
        // 1. Validasi
        $request->validate([
            'item_id' => 'required|exists:items,id',
            'amount'  => 'required|integer|min:1', // Pastikan input name di view adalah 'amount'
            'date'    => 'required|date',
            'notes'   => 'required|string',
        ]);

        // 2. Ambil Data Barang
        $item = Item::findOrFail($request->item_id);

        // 3. Cek Stok (Validasi Server-side)
        if ($item->quantity < $request->amount) {
            return back()
                ->withInput()
                ->with('error', 'Gagal! Stok tidak cukup. Sisa stok saat ini: ' . $item->quantity);
        }

        // 4. Simpan Transaksi & Kurangi Stok (Atomic Transaction)
        DB::transaction(function () use ($request, $item) {
            // A. Catat Transaksi
            Transaction::create([
                'item_id' => $request->item_id,
                'user_id' => Auth::id(),
                'type'    => 'keluar',      // Sesuai format database Anda
                'amount'  => $request->amount,
                'date'    => $request->date,
                'notes'   => $request->notes,
            ]);

            // B. Kurangi Stok
            $item->decrement('quantity', $request->amount);
        });

        return redirect()->route('admin.transactions.index')->with('success', 'Barang keluar berhasil dicatat!');
    }
}
