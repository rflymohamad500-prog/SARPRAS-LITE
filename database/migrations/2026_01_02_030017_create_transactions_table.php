<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->id();
            // Barang apa yang ditransaksikan?
            $table->foreignId('item_id')->constrained()->cascadeOnDelete();
            // Siapa yang mencatat? (Admin/Petugas)
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();

            // Jenis: Masuk (Tambah Stok) atau Keluar (Kurang Stok)
            $table->enum('type', ['masuk', 'keluar']);

            $table->integer('amount'); // Jumlah barang
            $table->date('date'); // Tanggal transaksi
            $table->text('notes')->nullable(); // Catatan (Misal: "Diambil oleh Pak Budi")

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('transactions');
    }
};
