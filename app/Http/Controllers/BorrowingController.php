<?php

namespace App\Http\Controllers;

use App\Models\Borrowing;
use App\Models\Item;
use App\Models\Room; // <--- PENTING: Panggil Model Room
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
    // 1. Daftar Peminjaman
    public function index()
    {
        $borrowings = Borrowing::with('item')
            ->orderBy('status', 'asc') // Tampilkan yang 'borrowed' dulu
            ->orderBy('created_at', 'desc') // Lalu urutkan dari yang terbaru
            ->paginate(10); // <--- INI PERBAIKANNYA (Jangan pakai get())

        return view('admin.borrowings.index', compact('borrowings'));
    }

    // 2. Form Pinjam
    public function create()
    {
        // Ambil data Ruangan untuk pilihan "Lokasi Penggunaan"
        $rooms = Room::all();

        // Ambil barang aset tetap yang stoknya > 0
        $items = Item::where('is_consumable', 0)
            ->where('quantity', '>', 0)
            ->with('room')
            ->get();

        // Kirim variabel $items dan $rooms ke view
        return view('admin.borrowings.create', compact('items', 'rooms'));
    }

    // 3. Proses Simpan
    public function store(Request $request)
    {
        $request->validate([
            'item_id'       => 'required',
            'borrower_name' => 'required',
            'location'      => 'required', // <--- Wajib pilih lokasi tujuan
            'amount'        => 'required|integer|min:1',
            'borrow_date'   => 'required|date',
            'return_date_plan' => 'required|date|after_or_equal:borrow_date',
        ]);

        $item = Item::findOrFail($request->item_id);

        if ($item->quantity < $request->amount) {
            return back()->with('error', 'Stok tidak cukup! Tersisa: ' . $item->quantity);
        }

        DB::transaction(function () use ($request, $item) {
            Borrowing::create([
                'item_id'       => $request->item_id,
                'borrower_name' => $request->borrower_name,
                'location'      => $request->location, // <--- Simpan Lokasi Tujuan
                'amount'        => $request->amount,
                'borrow_date'   => $request->borrow_date,
                'return_date_plan' => $request->return_date_plan,
                'status'        => 'borrowed',
                'notes'         => $request->notes
            ]);

            // Kurangi Stok Barang
            $item->decrement('quantity', $request->amount);
        });

        return redirect()->route('admin.borrowings.index')->with('success', 'Peminjaman berhasil dicatat!');
    }

    // 4. Proses Kembali
    public function returnItem($id)
    {
        $borrowing = Borrowing::findOrFail($id);

        if ($borrowing->status == 'returned') {
            return back()->with('error', 'Barang ini sudah dikembalikan!');
        }

        DB::transaction(function () use ($borrowing) {
            $borrowing->update([
                'status' => 'returned',
                'actual_return_date' => now(),
            ]);

            // Kembalikan Stok
            $borrowing->item->increment('quantity', $borrowing->amount);
        });

        return back()->with('success', 'Barang berhasil dikembalikan!');
    }


    // HAPUS MASSAL (BULK DELETE)
    public function bulkDestroy(Request $request)
    {
        // Validasi: Pastikan ada ID yang dikirim
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:borrowings,id',
        ]);

        // Hapus data yang ID-nya ada dalam daftar centang
        Borrowing::whereIn('id', $request->ids)->delete();

        return redirect()->back()->with('success', 'Data peminjaman terpilih berhasil dihapus.');
    }
}
