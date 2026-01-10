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
        Schema::create('catatan_sampahs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pengguna_id');
            $table->unsignedBigInteger('rumah_tangga_id');
            $table->unsignedBigInteger('kecamatan_id');
            $table->string('jenis_terdeteksi')->nullable();
            $table->string('jenis_manual')->nullable();
            $table->decimal('volume_terdeteksi_liter', 8, 2)->nullable();
            $table->decimal('volume_manual_liter', 8, 2)->nullable();
            $table->decimal('volume_final_liter', 8, 2)->nullable();
            $table->decimal('berat_kg', 8, 2)->nullable();
            $table->string('foto_path')->nullable();
            $table->timestamp('waktu_setoran')->nullable();
            $table->boolean('is_divalidasi')->default(false);
            $table->unsignedBigInteger('divalidasi_oleh')->nullable();
            $table->integer('points_diberikan')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('catatan_sampahs');
    }
};
