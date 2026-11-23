<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Menambah kolom No WhatsApp ke tabel users.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('no_hp', 20)->nullable()->after('prodi');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('no_hp');
        });
    }
};
