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
        Schema::create('penarikans', function (Blueprint $table) {
            $table->id();
            // Menghubungkan ke tabel users untuk mengetahui siapa yang mengajukan
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // Kolom yang menangkap inputan dari form
            $table->decimal('jumlah_penarikan', 12, 2); // Menangkap name="jumlah_penarikan"
            $table->string('metode_pencairan');          // Menangkap name="metode_pencairan"
            $table->string('bukti_pdf');                 // Menangkap name="bukti_pdf" (menyimpan path file PDF)
            
            // Status pengajuan untuk verifikasi admin
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penarikans');
    }
};