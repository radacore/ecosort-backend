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
        // Tambahkan foreign key constraints sekarang setelah tabel penggunas dibuat
        Schema::table('catatan_sampahs', function (Blueprint $table) {
            $table->foreign('pengguna_id')->references('id')->on('penggunas')->onDelete('cascade');
            $table->foreign('divalidasi_oleh')->references('id')->on('penggunas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('catatan_sampahs', function (Blueprint $table) {
            $table->dropForeign(['pengguna_id']);
            $table->dropForeign(['divalidasi_oleh']);
        });
        
        // Kembalikan ke constraint asli jika perlu
        Schema::table('catatan_sampahs', function (Blueprint $table) {
            $table->foreign('pengguna_id')->references('id')->on('penggunas')->onDelete('cascade');
            $table->foreign('divalidasi_oleh')->references('id')->on('penggunas')->onDelete('set null');
        });
    }
};
