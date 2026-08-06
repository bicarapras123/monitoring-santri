<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penarikan extends Model
{
    use HasFactory;

    // Tentukan nama tabel secara eksplisit jika berbeda dari bentuk plural standar
    protected $table = 'penarikans';

    // Izinkan kolom-kolom ini diisi melalui controller
    protected $guarded = ['id'];
}