<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('borrowings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('item_id')->constrained()->onDelete('cascade'); // Barang apa
            $table->string('borrower_name'); // Nama Peminjam
            $table->integer('amount')->default(1); // Jumlah pinjam
            $table->date('borrow_date'); // Tanggal Pinjam
            $table->date('return_date_plan'); // Rencana Tanggal Kembali
            $table->date('actual_return_date')->nullable(); // Tanggal Asli Kembali (Diisi saat dikembalikan)
            $table->enum('status', ['borrowed', 'returned'])->default('borrowed'); // Status
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('borrowings');
    }
};
