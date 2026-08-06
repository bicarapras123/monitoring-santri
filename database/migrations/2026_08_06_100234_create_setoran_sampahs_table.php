<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('setoran_sampahs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Relasi ke user jika login
            $table->string('nama_lengkap');
            $table->text('alamat_lengkap');
            $table->string('nomor_telephone');
            $table->string('nomor_rekening')->nullable(); // Tidak wajib diisi (opsional cash/ewallet)
            $table->string('jenis_rekening')->nullable(); // Tidak wajib diisi (opsional cash/ewallet)
            $table->string('jenis_sampah');
            $table->string('foto_sampah');
            $table->decimal('total_berat', 8, 2); // Berat dalam satuan kg (misal: 2.50)
            $table->string('status')->default('pending'); // Status verifikasi admin (pending / verified / selesai)
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('setoran_sampahs');
    }
};