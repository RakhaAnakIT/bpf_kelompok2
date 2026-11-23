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
        Schema::create('notifikasi', function (Blueprint $table) {
            $table->increments('notifikasi_id');
            $table->foreignId('user_id')->constrained('users')->cascadeOnDelete(); // penerima notifikasi
            $table->string('judul', 100);
            $table->string('pesan');
            $table->string('link')->nullable();      // halaman tujuan saat notifikasi diklik
            $table->timestamp('dibaca_pada')->nullable(); // kosong = belum dibaca
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notifikasi');
    }
};
