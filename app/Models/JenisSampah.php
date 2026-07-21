<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JenisSampah extends Model
{
    use HasFactory;

    /**
     * Nama tabel yang terkait dengan model ini (opsional jika sesuai konvensi plural 'jenis_sampahs').
     *
     * @var string
     */
    protected $table = 'jenis_sampah';

    /**
     * Atribut yang dapat diisi secara massal (Mass Assignment).
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'nama_sampah',
        'kategori',
        'harga_kg',
        'deskripsi',
        'upload_image', // <-- Tambahkan di sini
    ];

    /**
     * Casting tipe data untuk atribut tertentu.
     * Memastikan harga_kg otomatis dibaca sebagai angka (float/decimal).
     *
     * @var array<string, string>
     */
    protected $casts = [
        'harga_kg' => 'decimal:2',
    ];
}