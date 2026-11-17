<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pembeli_penjual', function (Blueprint $table) {
            $table->increments('pembeli_penjual_id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete();
            $table->string('nama_barang', 100);
            $table->string('kategori_barang', 50);
            $table->unsignedInteger('harga');
            $table->enum('kondisi', ['Baru', 'Bekas baik', 'Bekas'])->nullable();
            $table->string('lokasi', 100);
            $table->text('deskripsi')->nullable();
            $table->string('gambar')->nullable();
            $table->enum('status', ['tersedia', 'dipesan', 'terjual'])->default('tersedia');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pembeli_penjual');
    }
};
