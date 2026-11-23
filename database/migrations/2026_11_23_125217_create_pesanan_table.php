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
        Schema::create('pesanan', function (Blueprint $table) {
            $table->increments('pesanan_id');
            $table->unsignedInteger('barang_id');
            $table->foreignId('pembeli_id')->constrained('users')->cascadeOnDelete();
            $table->foreignId('penjual_id')->constrained('users')->cascadeOnDelete();
            $table->unsignedInteger('harga');                 // harga saat dipesan
            $table->string('lokasi_ketemu', 100);
            $table->dateTime('waktu_ketemu');
            $table->enum('cara_bayar', ['tunai', 'transfer', 'ewallet']);
            $table->text('catatan')->nullable();
            $table->enum('status', ['menunggu', 'dijadwalkan', 'selesai', 'ditolak', 'dibatalkan'])->default('menunggu');
            $table->boolean('selesai_pembeli')->default(false);  // pembeli sudah konfirmasi selesai
            $table->boolean('selesai_penjual')->default(false);  // penjual sudah konfirmasi selesai
            $table->string('alasan_batal')->nullable();
            $table->unsignedBigInteger('dibatalkan_oleh')->nullable();
            $table->timestamps();

            $table->foreign('barang_id')->references('pembeli_penjual_id')->on('pembeli_penjual')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pesanan');
    }
};
