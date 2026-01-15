<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Room;
use App\Models\ItemMutation; // Model Mutasi
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class ItemController extends Controller
{
    // Tampilkan Daftar Barang Tetap (LENGKAP DENGAN FILTER & TOTAL)
    public function index(Request $request)
    {
        // 1. Siapkan Query Dasar
        $query = Item::with(['category', 'room']); // Load relasi agar ringan

        // 2. Cek Filter Pencarian (Search Bar)
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('code', 'like', '%' . $request->search . '%');
        }

        // 3. Cek Filter Kategori (Dropdown)
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // 4. Ambil Data (Pagination)
        $items = $query->latest()->paginate(10); // Pakai paginate biar halaman tidak berat

        // 5. Hitung Ringkasan (Berdasarkan Filter di atas)
        // Kita clone query agar tidak mengganggu pagination
        $allItems = $query->get();

        $totalItem = $allItems->sum('quantity'); // Total Fisik Barang

        $totalHarga = $allItems->sum(function ($item) {
            return $item->price * $item->quantity; // Rumus: Harga x Jumlah
        });

        // 6. Ambil Data Kategori untuk Dropdown Filter
        $categories = Category::all();

        return view('admin.items.index', compact('items', 'totalItem', 'totalHarga', 'categories'));
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
            'quantity' => 'required|integer|min:1', // Pastikan quantity divalidasi
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
        $data['is_consumable'] = 0; // Set sebagai Barang Tetap

        // Handle Upload Foto
        if ($request->hasFile('image')) {
            $path = $request->file('image')->store('items', 'public');
            $data['image'] = $path;
        }

        Item::create($data);

        return redirect()->route('admin.items.index')->with('success', 'Barang berhasil ditambahkan!');
    }

    // Edit Barang
    public function edit(Item $item)
    {
        $categories = Category::all();
        $rooms = Room::all();
        return view('admin.items.edit', compact('item', 'categories', 'rooms'));
    }

    // Update Barang
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
        $qrCode = QrCode::size(200)->generate(route('admin.items.show', $item->id));
        return view('admin.items.show', compact('item', 'qrCode'));
    }

    // ==========================================
    // FITUR MUTASI (PINDAH RUANGAN)
    // ==========================================

    public function mutate(Request $request, $id)
    {
        $request->validate([
            'room_id' => 'required|exists:rooms,id',
        ]);

        $item = Item::findOrFail($id);
        $oldRoomName = $item->room->name ?? 'Belum Ada Ruangan';
        $newRoom = Room::findOrFail($request->room_id);
        $newRoomName = $newRoom->name;

        // Simpan Riwayat
        $mutation = ItemMutation::create([
            'item_id' => $item->id,
            'origin_room' => $oldRoomName,
            'destination_room' => $newRoomName,
            'mutation_date' => now(),
            'user_id' => Auth::id(),
        ]);

        // Update Lokasi
        $item->update(['room_id' => $request->room_id]);

        return redirect()->route('mutations.print', $mutation->id);
    }

    public function printMutation($id)
    {
        $mutation = ItemMutation::with(['item', 'user'])->findOrFail($id);
        return view('admin.items.print_mutation', compact('mutation'));
    }
}
