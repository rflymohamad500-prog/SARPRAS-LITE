<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('items', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique(); // Kode Barang
            $table->string('name');

            // Relasi (Foreign Keys)
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();

            // PERBAIKAN DI SINI: Tambahkan ->nullable() agar nullOnDelete() berfungsi
            $table->foreignId('room_id')->nullable()->constrained()->nullOnDelete();

            $table->foreignId('location_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('funding_id')->nullable()->constrained()->nullOnDelete();

            $table->integer('quantity')->default(0); // Stok / Jumlah
            $table->enum('condition', ['baik', 'rusak_ringan', 'rusak_berat', 'hilang'])->default('baik');
            $table->decimal('price', 15, 2)->default(0);
            $table->date('purchase_date')->nullable(); // Tahun pengadaan
            $table->string('image')->nullable();

            // Pembeda Jenis Barang
            $table->boolean('is_consumable')->default(false); // False = Aset Tetap, True = Habis Pakai

            // Khusus Barang Habis Pakai
            $table->string('unit')->nullable(); // Pcs, Rim, Box (Hanya untuk consumable)

            // Khusus Barang Aset Tetap
            $table->text('qr_code_data')->nullable(); // URL atau string QR

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('items');
    }
};
