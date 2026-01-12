<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Models\Borrowing;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\ConsumableController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LabelController;

// 1. Halaman Depan
Route::get('/', function () {
    return view('welcome');
});

// 2. POLISI LALU LINTAS (Redirect Dashboard sesuai Role)
Route::get('/dashboard', function () {
    if (Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('petugas.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// 3. GROUP AUTH (Harus Login)
Route::middleware('auth')->group(function () {

    // ====================================================
    // A. AREA KHUSUS ADMIN (Middleware role:admin)
    // ====================================================
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

        // DASHBOARD ADMIN
        Route::get('/dashboard', function () {
            $totalAset      = Item::where('is_consumable', 0)->count();
            $totalATK       = Item::where('is_consumable', 1)->count();
            $sedangDipinjam = Borrowing::where('status', 'borrowed')->count();
            $stokKritis     = Item::where('quantity', '<=', 5)->where('quantity', '>', 0)->count();

            $recentBorrowings = Borrowing::with(['item', 'item.room'])
                ->orderBy('created_at', 'desc')->take(5)->get();

            $lowStockItems = Item::where('quantity', '<=', 5)
                ->where('quantity', '>', 0)->orderBy('quantity', 'asc')->take(5)->get();

            return view('admin.dashboard', compact(
                'totalAset',
                'totalATK',
                'sedangDipinjam',
                'stokKritis',
                'recentBorrowings',
                'lowStockItems'
            ));
        })->name('dashboard');

        // Master Data (Hanya Admin)
        Route::resource('categories', CategoryController::class);
        Route::resource('rooms', RoomController::class);
        Route::resource('users', UserController::class);
    });

    // ====================================================
    // B. AREA OPERASIONAL (Admin & Petugas)
    // ====================================================
    Route::prefix('admin')->name('admin.')->group(function () {

        // Inventaris & ATK
        Route::resource('items', ItemController::class);
        Route::resource('consumables', ConsumableController::class);

        // Peminjaman
        Route::get('/borrowings', [BorrowingController::class, 'index'])->name('borrowings.index');
        Route::get('/borrowings/create', [BorrowingController::class, 'create'])->name('borrowings.create');
        Route::post('/borrowings', [BorrowingController::class, 'store'])->name('borrowings.store');
        Route::put('/borrowings/{id}/return', [BorrowingController::class, 'returnItem'])->name('borrowings.return');

        // Transaksi (Masuk/Keluar)
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/in', [TransactionController::class, 'createIn'])->name('transactions.create_in');
        Route::post('/transactions/in', [TransactionController::class, 'storeIn'])->name('transactions.store_in');
        Route::get('/transactions/out', [TransactionController::class, 'createOut'])->name('transactions.create_out');
        Route::post('/transactions/out', [TransactionController::class, 'storeOut'])->name('transactions.store_out');

        // Laporan
        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/asset', [ReportController::class, 'asset'])->name('reports.asset');
        Route::get('/reports/consumable', [ReportController::class, 'consumable'])->name('reports.consumable');
        Route::get('/reports/transaction', [ReportController::class, 'transaction'])->name('reports.transaction');
        Route::get('/reports/borrowing', [ReportController::class, 'borrowing'])->name('reports.borrowing');

        // Cetak Label
        Route::get('/labels', [LabelController::class, 'index'])->name('labels.index');
        Route::post('/labels/print', [LabelController::class, 'print'])->name('labels.print');

        // --- KHUSUS MUTASI (Action PUT) ---
        // Route ini menjadi: admin.items.mutate
        Route::put('/items/{id}/mutate', [ItemController::class, 'mutate'])->name('items.mutate');
    });

    // ====================================================
    // C. ROUTE SPESIAL (Tanpa Prefix Nama 'admin.')
    // ====================================================
    // Kita taruh disini agar namanya MURNI 'mutations.print' sesuai Controller
    Route::middleware('auth')->prefix('admin')->group(function () {
        Route::get('/mutations/{id}/print', [ItemController::class, 'printMutation'])->name('mutations.print');
    });

    // ====================================================
    // D. DASHBOARD PETUGAS
    // ====================================================
    Route::middleware('role:petugas')->prefix('petugas')->name('petugas.')->group(function () {
        Route::get('/dashboard', function () {
            // Logic Dashboard Petugas (Sama dengan Admin)
            $totalAset      = Item::where('is_consumable', 0)->count();
            $totalATK       = Item::where('is_consumable', 1)->count();
            $sedangDipinjam = Borrowing::where('status', 'borrowed')->count();
            $stokKritis     = Item::where('quantity', '<=', 5)->where('quantity', '>', 0)->count();

            $recentBorrowings = Borrowing::with(['item', 'item.room'])
                ->orderBy('created_at', 'desc')->take(5)->get();

            $lowStockItems = Item::where('quantity', '<=', 5)
                ->where('quantity', '>', 0)->orderBy('quantity', 'asc')->take(5)->get();

            return view('admin.dashboard', compact(
                'totalAset',
                'totalATK',
                'sedangDipinjam',
                'stokKritis',
                'recentBorrowings',
                'lowStockItems'
            ));
        })->name('dashboard');
    });

    // --- PROFILE ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
