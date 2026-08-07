<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penarikan extends Model
{
    use HasFactory;

    protected $table = 'penarikans';
    protected $guarded = ['id'];

    // Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); 
    }
}