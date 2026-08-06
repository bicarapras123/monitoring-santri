<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('rekening_nasabahs', function (Blueprint $table) {
            // Kolom status verifikasi: 'pending', 'verified', 'rejected'
            if (!Schema::hasColumn('rekening_nasabahs', 'status')) {
                $table->string('status')->default('pending')->after('foto_ktp');
            }
        });
    }

    public function down(): void
    {
        Schema::table('rekening_nasabahs', function (Blueprint $table) {
            $table->dropColumn('status');
        });
    }
};