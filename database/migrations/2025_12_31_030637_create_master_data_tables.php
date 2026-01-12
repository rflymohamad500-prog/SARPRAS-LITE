<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        // Tabel Kategori
        Schema::create('categories', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Tabel Ruangan
        Schema::create('rooms', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Tabel Lokasi (Misal: Lemari 1, Rak 2)
        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->timestamps();
        });

        // Tabel Sumber Dana
        Schema::create('fundings', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // BOS, Komite, Hibah
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fundings');
        Schema::dropIfExists('locations');
        Schema::dropIfExists('rooms');
        Schema::dropIfExists('categories');
    }
};
