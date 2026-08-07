<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penarikans', function (Blueprint $table) {
            // Menambahkan kolom upload bukti pencairan di samping bukti_pdf
            $table->string('bukti_pencairan')->nullable()->after('bukti_pdf');
        });
    }

    public function down(): void
    {
        Schema::table('penarikans', function (Blueprint $table) {
            $table->dropColumn('bukti_pencairan');
        });
    }
};