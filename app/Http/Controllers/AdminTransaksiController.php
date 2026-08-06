<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SetoranSampah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminTransaksiController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');

        // 1. Mengambil rekap data yang Dikelompokkan per Nasabah untuk Tabel Utama
        $nasabahs = SetoranSampah::select(
                'setoran_sampahs.nama_lengkap',
                'setoran_sampahs.nomor_telephone',
                DB::raw('COUNT(setoran_sampahs.id) as total_setoran'),
                DB::raw('SUM(setoran_sampahs.total_berat) as akumulasi_berat'),
                DB::raw('SUM(setoran_sampahs.total_berat * COALESCE(jenis_sampah.harga_kg, 0)) as total_keseluruhan_tabungan')
            )
            ->leftJoin('jenis_sampah', 'setoran_sampahs.jenis_sampah', '=', 'jenis_sampah.nama_sampah')
            ->when($search, function($query, $search) {
                return $query->where('setoran_sampahs.nama_lengkap', 'like', "%{$search}%")
                             ->orWhere('setoran_sampahs.nomor_telephone', 'like', "%{$search}%");
            })
            ->groupBy('setoran_sampahs.nama_lengkap', 'setoran_sampahs.nomor_telephone')
            ->orderByRaw('MAX(setoran_sampahs.created_at) DESC')
            ->paginate(10);

        // 2. Mengambil Detail Riwayat Setoran hanya untuk Nasabah yang tampil di halaman ini (Optimasi)
        $namaNasabahs = $nasabahs->pluck('nama_lengkap');

        $semuaSetoran = SetoranSampah::select(
                'setoran_sampahs.*',
                DB::raw('(setoran_sampahs.total_berat * COALESCE(jenis_sampah.harga_kg, 0)) as total_tabungan')
            )
            ->leftJoin('jenis_sampah', 'setoran_sampahs.jenis_sampah', '=', 'jenis_sampah.nama_sampah')
            ->whereIn('setoran_sampahs.nama_lengkap', $namaNasabahs)
            ->orderBy('setoran_sampahs.created_at', 'desc')
            ->get()
            ->groupBy('nama_lengkap'); // Dikelompokkan berdasarkan nama agar mudah dipanggil Alpine.js

        return view('admin.transaksi.index', compact('nasabahs', 'semuaSetoran'));
    }
}