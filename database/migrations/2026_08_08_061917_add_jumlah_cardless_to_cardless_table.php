<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('cardless', function (Blueprint $table) {
            $table->decimal('jumlah_cardless', 15, 2)->after('nama_lengkap')->default(0);
        });
    }

    public function down(): void
    {
        Schema::table('cardless', function (Blueprint $table) {
            $table->dropColumn('jumlah_cardless');
        });
    }
};