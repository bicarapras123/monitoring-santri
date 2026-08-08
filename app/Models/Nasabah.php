<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Nasabah extends Model
{
    use HasFactory;

    // Menegaskan bahwa tabel di database bernama 'nasabahs'
    protected $table = 'nasabahs';

    // Kolom yang diizinkan untuk mass assignment
    protected $guarded = ['id'];

    public function rekening()
{
    return $this->hasOne(RekeningNasabah::class, 'nasabah_id');
}

public function setoranSampah()
    {
        // Asumsi: Nasabah terhubung ke setoran lewat 'nama_lengkap' atau 'user_id'
        // Karena di gambar Anda kolomnya adalah 'nama_lengkap', gunakan ini:
        return $this->hasMany(SetoranSampah::class, 'nama_lengkap', 'nama_lengkap');
    }

}