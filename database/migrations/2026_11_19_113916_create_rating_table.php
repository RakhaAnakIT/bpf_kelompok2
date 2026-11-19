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
        Schema::create('rating', function (Blueprint $table) {
            $table->increments('rating_id');
            $table->foreignId('pemberi_id')->constrained('users')->cascadeOnDelete();  // yang memberi rating
            $table->foreignId('penerima_id')->constrained('users')->cascadeOnDelete(); // yang dinilai
            $table->enum('peran_penerima', ['penjual', 'pembeli']);  // peran penerima saat dinilai
            $table->unsignedTinyInteger('bintang');           // 1 sampai 5
            $table->text('ulasan')->nullable();
            $table->unsignedInteger('pesanan_id')->nullable(); // diisi setelah fitur pesanan dibuat
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('rating');
    }
};
