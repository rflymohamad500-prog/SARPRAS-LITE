<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
// PENTING: Panggil Model Data
use App\Models\Item;
use App\Models\Category;
use App\Models\Borrowing;

class AuthenticatedSessionController extends Controller
{
    /**
     * Menampilkan halaman login (dengan data statistik).
     */
    public function create()
    {
        // 1. Ambil Data Ringkasan
        // Gunakan ?? 0 untuk mencegah error jika tabel masih kosong
        $totalAset = Item::where('is_consumable', 0)->count();
        $totalATK  = Item::where('is_consumable', 1)->count();
        $transaksi = Borrowing::count();

        // 2. Ambil Data Grafik (Kategori)
        $kategori = Category::withCount('items')->get();
        $labelGrafik = $kategori->pluck('name');
        $jumlahGrafik = $kategori->pluck('items_count');

        // 3. Kirim ke View Login
        return view('auth.login', compact(
            'totalAset',
            'totalATK',
            'transaksi',
            'labelGrafik',
            'jumlahGrafik'
        ));
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request)
    {
        $request->authenticate();

        $request->session()->regenerate();

        // PERBAIKAN DI SINI:
        // Kita langsung arahkan ke route 'dashboard'
        return redirect()->intended(route('dashboard'));
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request)
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
