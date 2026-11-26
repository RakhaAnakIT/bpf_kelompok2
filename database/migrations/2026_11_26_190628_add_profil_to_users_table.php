<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Kolom profil di tabel users.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('foto_profil')->nullable()->after('no_hp');      // path foto profil
            $table->boolean('profil_publik')->default(true)->after('foto_profil'); // true = bisa dikunjungi user lain
            $table->timestamp('hapus_akun')->nullable()->after('diblokir_pada');   // diisi saat akun dihapus
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['foto_profil', 'profil_publik', 'hapus_akun']);
        });
    }
};
