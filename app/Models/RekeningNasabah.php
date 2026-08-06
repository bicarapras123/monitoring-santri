<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RekeningNasabah extends Model
{
    use HasFactory;

    protected $table = 'rekening_nasabahs';
    protected $guarded = ['id'];

    // Relasi balik ke Nasabah
    public function nasabah()
    {
        return $this->belongsTo(Nasabah::class);
    }
}