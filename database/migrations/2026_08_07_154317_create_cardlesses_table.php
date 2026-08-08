<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('cardless', function (Blueprint $table) {
            $table->id();
            $table->string('nik')->nullable();                  // NIK pemohon[cite: 1]
            $table->string('nama_lengkap')->nullable();         // Nama Lengkap pemohon[cite: 1]
            $table->string('jenis_kelamin')->nullable();        // Jenis Kelamin[cite: 1]
            $table->string('tempat_tgl_lahir')->nullable();     // Tempat, Tgl Lahir[cite: 1]
            $table->string('nomor_telepon')->nullable();        // Nomor Telepon[cite: 1]
            $table->string('nama_orang_tua')->nullable();       // Nama Orang Tua[cite: 1]
            $table->text('alamat_lengkap')->nullable();         // Alamat Lengkap[cite: 1]
            $table->text('raw_text')->nullable();               // Menyimpan teks mentah hasil scan
            $table->string('file_path')->nullable();            // Lokasi file PDF yang di-upload
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cardless');
    }
};