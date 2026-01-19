<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Models\Item;
use App\Http\Controllers\ItemController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BorrowingController;
use App\Http\Controllers\ConsumableController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\LabelController;
use App\Http\Controllers\DashboardController; // <--- PASTIKAN INI ADA

Route::get('/', function () {
    return view('welcome');
});

// PENGATUR LALU LINTAS
Route::get('/dashboard', function () {
    if (Auth::user()->role === 'admin') {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('petugas.dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {

    // === ADMIN ===
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {

        // --- BAGIAN INI YANG HARUS DIPERBAIKI ---
        // JANGAN PAKAI function() { ... } LAGI
        // GUNAKAN [DashboardController::class, 'index']
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        // ----------------------------------------

        Route::resource('categories', CategoryController::class);
        Route::resource('rooms', RoomController::class);
        Route::resource('users', UserController::class);
    });

    // === OPERASIONAL (ADMIN & PETUGAS) ===
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('items', ItemController::class);
        Route::resource('consumables', ConsumableController::class);

        Route::get('/borrowings', [BorrowingController::class, 'index'])->name('borrowings.index');
        Route::get('/borrowings/create', [BorrowingController::class, 'create'])->name('borrowings.create');
        Route::post('/borrowings', [BorrowingController::class, 'store'])->name('borrowings.store');
        Route::put('/borrowings/{id}/return', [BorrowingController::class, 'returnItem'])->name('borrowings.return');
        Route::delete('/borrowings/bulk-delete', [BorrowingController::class, 'bulkDestroy'])->name('borrowings.bulk_destroy');
        Route::get('/transactions', [TransactionController::class, 'index'])->name('transactions.index');
        Route::get('/transactions/in', [TransactionController::class, 'createIn'])->name('transactions.create_in');
        Route::post('/transactions/in', [TransactionController::class, 'storeIn'])->name('transactions.store_in');
        Route::get('/transactions/out', [TransactionController::class, 'createOut'])->name('transactions.create_out');
        Route::post('/transactions/out', [TransactionController::class, 'storeOut'])->name('transactions.store_out');

        Route::get('/reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('/reports/asset', [ReportController::class, 'asset'])->name('reports.asset');
        Route::get('/reports/consumable', [ReportController::class, 'consumable'])->name('reports.consumable');
        Route::get('/reports/transaction', [ReportController::class, 'transaction'])->name('reports.transaction');
        Route::get('/reports/borrowing', [ReportController::class, 'borrowing'])->name('reports.borrowing');

        Route::get('/labels', [LabelController::class, 'index'])->name('labels.index');
        Route::post('/labels/print', [LabelController::class, 'print'])->name('labels.print');
        Route::put('/items/{id}/mutate', [ItemController::class, 'mutate'])->name('items.mutate');
    });

    // Route Spesial Mutasi
    Route::middleware('auth')->prefix('admin')->group(function () {
        Route::get('/mutations/{id}/print', [ItemController::class, 'printMutation'])->name('mutations.print');
    });

    // === PETUGAS ===
    Route::middleware('role:petugas')->prefix('petugas')->name('petugas.')->group(function () {
        // PETUGAS JUGA PAKAI CONTROLLER YANG SAMA
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    });

    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__ . '/auth.php';
