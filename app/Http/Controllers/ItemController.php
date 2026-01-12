<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Room;
// PENTING: Tambahkan Model ItemMutation agar riwayat tersimpan
use App\Models\ItemMutation;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ItemController extends Controller
{
    // Tampilkan Daftar Barang Tetap
    public function index(Request $request)
    {
        // [BARU] Ambil Data Ruangan untuk Dropdown di Modal Mutasi
        $rooms = Room::all();

        // 1. KUNCI UTAMA: Filter hanya Barang Tetap (is_consumable = 0)
        $query = Item::where('is_consumable', 0)->with(['category', 'room']);

        // 2. Logika Pencarian
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('code', 'LIKE', "%{$search}%")
                    ->orWhere('barcode', 'LIKE', "%{$search}%");
            });
        }

        // 3. Ambil datanya (Urutkan dari yang terbaru)
        $items = $query->orderBy('created_at', 'desc')->get();

        // [UPDATE] Kirim juga variabel $rooms ke view
        return view('admin.items.index', compact('items', 'rooms'));
    }

    // Form Tambah Barang
    public function create()
    {
        $categories = Category::all();
        $rooms = Room::all();

        // Buat Kode Unik Otomatis (Cth: INV-2025001)
        $lastItem = Item::latest()->first();
        $nextId = $lastItem ? $lastItem->id + 1 : 1;
        $kodeOtomatis = 'INV-' . date('Y') . sprintf('%04d', $nextId);

        return view('admin.items.create', compact('categories', 'rooms', 'kodeOtomatis'));
    }

    // Simpan Barang
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:items,code',
            'name' => 'required',
            'barcode' => 'nullable|string|max:50|unique:items,barcode',
            'category_id' => 'required',
            'room_id' => 'required',
            'unit' => 'required',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
        $data['is_consumable'] = 0; // Set sebagai Barang Tetap

        // 1. Handle Upload Foto
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('items', 'public');
            $data['image'] = $path;
        }

        // 2. Simpan ke Database
        Item::create($data);

        return redirect()->route('admin.items.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    // MENAMPILKAN FORM EDIT
    public function edit(Item $item)
    {
        $categories = Category::all();
        $rooms = Room::all();
        return view('admin.items.edit', compact('item', 'categories', 'rooms'));
    }

    // PROSES UPDATE DATA
    public function update(Request $request, Item $item)
    {
        $request->validate([
            'code' => 'required|unique:items,code,' . $item->id,
            'name' => 'required',
            'barcode' => 'nullable|string|max:50|unique:items,barcode,' . $item->id,
            'category_id' => 'required',
            'room_id' => 'required',
            'unit' => 'required',
            'quantity' => 'required|integer|min:1',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $data['image'] = $request->file('image')->store('items', 'public');
        } else {
            $data['image'] = $item->image;
        }

        $item->update($data);

        return redirect()->route('admin.items.index')->with('success', 'Data barang berhasil diperbarui!');
    }

    // Hapus Barang
    public function destroy(Item $item)
    {
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();
        return redirect()->route('admin.items.index')->with('success', 'Barang dihapus!');
    }

    // Detail & QR Code
    public function show(Item $item)
    {
        // Generate QR Code untuk Detail
        $qrCode = QrCode::size(200)->generate(route('admin.items.show', $item->id));
        return view('admin.items.show', compact('item', 'qrCode'));
    }

    // ==========================================
    // BAGIAN BARU: FITUR MUTASI (PINDAH RUANGAN)
    // ==========================================

    // 1. Proses Pemindahan Barang
    public function mutate(Request $request, $id)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
        ]);

        $item = Item::findOrFail($id);

        // Ambil Data Lama & Baru
        $oldRoomName = $item->room->name ?? 'Belum Ada Ruangan';
        $newRoom = Room::findOrFail($request->room_id);
        $newRoomName = $newRoom->name;

        // 1. Simpan Riwayat Mutasi (Agar bisa dicetak suratnya)
        $mutation = ItemMutation::create([
            'item_id' => $item->id,
            'origin_room' => $oldRoomName,
            'destination_room' => $newRoomName,
            'mutation_date' => now(),
            'user_id' => Auth::id(),
        ]);

        // 2. Update Lokasi Barang di Database Utama
        $item->update(['room_id' => $request->room_id]);

        // 3. Arahkan ke Halaman Cetak Surat
        return redirect()->route('mutations.print', $mutation->id);
    }

    // 2. Tampilkan Halaman Cetak Surat Mutasi
    public function printMutation($id)
    {
        $mutation = ItemMutation::with(['item', 'user'])->findOrFail($id);
        return view('admin.items.print_mutation', compact('mutation'));
    }
}
