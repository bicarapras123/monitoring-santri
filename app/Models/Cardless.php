<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cardless extends Model
{
    use HasFactory;

    protected $table = 'cardless';

    protected $fillable = [
        'nik',
        'nama_lengkap',
        'jumlah_cardless', // <-- Tambahkan baris ini
        'jenis_kelamin',
        'tempat_tgl_lahir',
        'nomor_telepon',
        'nama_orang_tua',
        'alamat_lengkap',
        'raw_text',
        'file_path',
    ];
}