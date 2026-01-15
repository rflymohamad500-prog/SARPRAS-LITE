<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Category;
use App\Models\Room;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ConsumableController extends Controller
{
    // TAMPILKAN DAFTAR BARANG HABIS PAKAI
    public function index(Request $request)
    {
        // 1. Query Dasar: Hanya Barang Habis Pakai (is_consumable = 1)
        $query = Item::where('is_consumable', 1)->with(['category', 'room']);

        // 2. Fitur Pencarian (Search Bar) - Opsional jika ingin ditambahkan nanti
        if ($request->has('search')) {
            $query->where('name', 'LIKE', '%' . $request->search . '%');
        }

        // 3. Ambil Data (Urutkan Terbaru)
        // PENTING: Nama variabel harus $consumables agar cocok dengan View
        $consumables = $query->latest()->get();

        // 4. Kirim ke View
        return view('admin.consumables.index', compact('consumables'));
    }

    // FORM TAMBAH
    public function create()
    {
        $categories = Category::all();
        $rooms = Room::all();

        // Kode Otomatis BHP (Barang Habis Pakai)
        $lastItem = Item::where('is_consumable', 1)->latest()->first();
        $nextId = $lastItem ? $lastItem->id + 1 : 1;
        $kodeOtomatis = 'BHP-' . date('Y') . sprintf('%04d', $nextId);

        return view('admin.consumables.create', compact('categories', 'rooms', 'kodeOtomatis'));
    }

    // SIMPAN DATA BARU
    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:items,code',
            'name' => 'required',
            'category_id' => 'required',
            'room_id' => 'required',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();
        $data['is_consumable'] = 1; // Pastikan tersimpan sebagai Habis Pakai

        // Upload Foto
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        Item::create($data);

        return redirect()->route('admin.consumables.index')->with('success', 'Barang habis pakai berhasil ditambahkan!');
    }

    // FORM EDIT
    public function edit($id)
    {
        $item = Item::findOrFail($id);
        $categories = Category::all();
        $rooms = Room::all();

        return view('admin.consumables.edit', compact('item', 'categories', 'rooms'));
    }

    // UPDATE DATA
    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $request->validate([
            'code' => 'required|unique:items,code,' . $item->id,
            'name' => 'required',
            'category_id' => 'required',
            'room_id' => 'required',
            'quantity' => 'required|integer|min:0',
            'unit' => 'required',
            'image' => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        // Cek Foto Baru
        if ($request->hasFile('image')) {
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        $item->update($data);

        return redirect()->route('admin.consumables.index')->with('success', 'Data berhasil diperbarui!');
    }

    // HAPUS DATA
    public function destroy($id)
    {
        $item = Item::findOrFail($id);

        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();

        return redirect()->route('admin.consumables.index')->with('success', 'Barang berhasil dihapus!');
    }
}
