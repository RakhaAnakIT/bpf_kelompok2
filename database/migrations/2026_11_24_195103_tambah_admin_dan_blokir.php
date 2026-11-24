<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * - users.role ditambah nilai 'admin' (tidak bisa dipilih saat daftar)
     * - users.diblokir_pada: diisi saat admin memblokir akun
     * - pesanan.catatan_admin: alasan admin saat membatalkan/menyelesaikan pesanan
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->enum('role', ['penjual', 'pembeli', 'penjual_pembeli', 'admin'])->change();
            $table->timestamp('diblokir_pada')->nullable()->after('role');
        });

        Schema::table('pesanan', function (Blueprint $table) {
            $table->string('catatan_admin')->nullable()->after('dibatalkan_oleh');
        });
    }

    public function down(): void
    {
        Schema::table('pesanan', function (Blueprint $table) {
            $table->dropColumn('catatan_admin');
        });

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('diblokir_pada');
            $table->enum('role', ['penjual', 'pembeli', 'penjual_pembeli'])->change();
        });
    }
};
