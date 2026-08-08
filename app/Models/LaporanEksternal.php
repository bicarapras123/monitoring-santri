<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LaporanEksternal extends Model
{
    use HasFactory;

    // Pastikan nama tabel sesuai dengan yang ada di migrasi Anda
    protected $table = 'laporan_eksternals'; 

    // Izinkan semua kolom untuk diisi (Mass Assignment)
    protected $guarded = ['id'];
}