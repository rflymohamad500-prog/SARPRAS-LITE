<?php

namespace App\Http\Controllers;

use App\Models\Item;
use App\Models\Category;
use App\Models\Room; // <--- PENTING: Jangan lupa import model Room
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // Untuk hapus foto lama

class ConsumableController extends Controller
{
    // 1. Tampilkan Daftar Barang Habis Pakai
    public function index()
    {
        // Ambil barang yang is_consumable = 1 (True)
        $items = Item::where('is_consumable', 1)->with(['category', 'room'])->get();
        return view('admin.consumables.index', compact('items'));
    }

    // 2. Form Tambah
    public function create()
    {
        $categories = Category::all();

        // PERBAIKAN: Ambil data rooms agar error 'Undefined variable $rooms' hilang
        $rooms = Room::all();

        // Generate kode unik: BHP-2025xxxx
        $lastItem = Item::where('is_consumable', 1)->latest()->first();
        $nextId = $lastItem ? $lastItem->id + 1 : 1;
        $kodeOtomatis = 'BHP-' . date('Y') . sprintf('%04d', $nextId);

        // Kirim $rooms ke view
        return view('admin.consumables.create', compact('categories', 'rooms', 'kodeOtomatis'));
    }

    // 3. Simpan Data
    public function store(Request $request)
    {
        $request->validate([
            'code'        => 'required|unique:items,code',
            'name'        => 'required',
            'barcode'     => 'nullable|string|max:50|unique:items,barcode', // Validasi Barcode
            'category_id' => 'required',
            'room_id'     => 'required', // Wajib diisi (Lokasi Penyimpanan)
            'unit'        => 'required', // Wajib isi satuan (Pcs, Rim, Pack)
            'quantity'    => 'required|integer|min:0',
            'image'       => 'nullable|image|max:2048', // Validasi Foto
        ]);

        $data = $request->all();
        $data['is_consumable'] = 1; // Tandai sebagai Habis Pakai

        // Proses Upload Foto
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('items', 'public');
        }

        Item::create($data);

        return redirect()->route('admin.consumables.index')->with('success', 'Barang habis pakai berhasil ditambahkan!');
    }

    // 4. Edit (Form Edit)
    public function edit($id)
    {
        $item = Item::findOrFail($id);
        $categories = Category::all();
        $rooms = Room::all(); // Ambil rooms juga untuk form edit

        return view('admin.consumables.edit', compact('item', 'categories', 'rooms'));
    }

    // 5. Proses Update
    public function update(Request $request, $id)
    {
        $item = Item::findOrFail($id);

        $request->validate([
            // Unique Code kecuali punya barang ini sendiri
            'code'        => 'required|unique:items,code,' . $item->id,
            'name'        => 'required',
            'barcode'     => 'nullable|string|max:50|unique:items,barcode,' . $item->id,
            'category_id' => 'required',
            'room_id'     => 'required',
            'unit'        => 'required',
            'quantity'    => 'required|integer|min:0',
            'image'       => 'nullable|image|max:2048',
        ]);

        $data = $request->all();

        // Cek apakah user upload foto baru?
        if ($request->hasFile('image')) {
            // Hapus foto lama jika ada
            if ($item->image) {
                Storage::disk('public')->delete($item->image);
            }
            // Simpan foto baru
            $data['image'] = $request->file('image')->store('items', 'public');
        } else {
            // Jika tidak upload, pakai foto lama (jangan ditimpa null)
            unset($data['image']);
        }

        $item->update($data);

        return redirect()->route('admin.consumables.index')->with('success', 'Data ATK berhasil diperbarui!');
    }

    // 6. Hapus
    public function destroy($id)
    {
        $item = Item::findOrFail($id);

        // Hapus file foto dari storage agar server tidak penuh
        if ($item->image) {
            Storage::disk('public')->delete($item->image);
        }

        $item->delete();
        return redirect()->route('admin.consumables.index')->with('success', 'Data berhasil dihapus!');
    }
}
