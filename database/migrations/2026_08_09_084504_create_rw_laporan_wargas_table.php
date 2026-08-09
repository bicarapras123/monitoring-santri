<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rw_laporan_wargas', function (Blueprint $table) {
            $table->id();
            $table->string('nik', 16)->unique();
            $table->string('nama_lengkap');
            $table->string('kelurahan');
            $table->string('kecamatan');
            $table->string('kode_pos', 10);
            $table->string('kota');
            $table->string('nomor_telepon');
            // Kolom checkbox pengesahan
            $table->boolean('is_data_benar')->default(false);
            $table->boolean('is_setuju_ketentuan')->default(false);
            $table->boolean('is_disahkan_pengurus')->default(false);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rw_laporan_wargas');
    }
};