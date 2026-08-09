<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rw_laporan_wargas', function (Blueprint $table) {
            $table->string('file_upload')->nullable()->after('nomor_telepon');
        });
    }

    public function down(): void
    {
        Schema::table('rw_laporan_wargas', function (Blueprint $table) {
            $table->dropColumn('file_upload');
        });
    }
};