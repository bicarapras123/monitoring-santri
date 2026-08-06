<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rekening_nasabahs', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel nasabahs berdasarkan id nasabah
            $table->foreignId('nasabah_id')->constrained('nasabahs')->onDelete('cascade');
            $table->enum('jenis_ewallet', ['GoPay', 'DANA']);
            $table->string('nomor_rekening');
            $table->string('foto_ktp'); // Menyimpan path file upload KTP
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rekening_nasabahs');
    }
};