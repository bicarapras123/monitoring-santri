<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RwLaporanWarga extends Model
{
    use HasFactory;

    protected $table = 'rw_laporan_wargas';

    protected $fillable = [
        'nik',
        'nama_lengkap',
        'kelurahan',
        'kecamatan',
        'kode_pos',
        'kota',
        'nomor_telepon',
        'file_upload', // Tambahkan ini
        'is_data_benar',
        'is_setuju_ketentuan',
        'is_disahkan_pengurus',
    ];

    protected $casts = [
        'is_data_benar' => 'boolean',
        'is_setuju_ketentuan' => 'boolean',
        'is_disahkan_pengurus' => 'boolean',
    ];
}