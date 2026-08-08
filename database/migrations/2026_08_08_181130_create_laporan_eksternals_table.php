<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('laporan_eksternals', function (Blueprint $table) {
            $table->id();
            $table->string('jenis_laporan'); // 'Dashboard' atau 'Rekap PDF'
            $table->string('tujuan'); // 'RW' atau 'Bank Sampah Induk'
            $table->string('file_path');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('laporan_eksternals');
    }
};
