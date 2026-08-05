<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        // Mengubah nilai default pada kolom yang sudah ada
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('nasabah')->change();
        });

        // Mengubah data user lama yang masih 'orang_tua' atau kosong agar menjadi 'nasabah'
        DB::table('users')->where('role', 'orang_tua')->orWhereNull('role')->update(['role' => 'nasabah']);
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('role')->default('orang_tua')->change();
        });
    }
};