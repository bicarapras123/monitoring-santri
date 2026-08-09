<?php

namespace App\Http\Controllers;

use App\Models\RwLaporanWarga;
use App\Models\SetoranSampah;
use App\Models\LaporanEksternal;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class BankSampahIndukController extends Controller
{
    // Fungsi privat untuk memblokir akses jika bukan role bank_sampah_induk
    private function authorizeBsi()
    {
        if (!Auth::check() || Auth::user()->role !== 'bank_sampah_induk') {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin sebagai Bank Sampah Induk.');
        }
    }

    public function index()
    {
        $this->authorizeBsi(); // Cek keamanan akses Bank Sampah Induk

        // 1. Data laporan warga RW
        $laporansRw = RwLaporanWarga::latest()->get();

        // 2. Data setoran sampah di-join dengan tabel 'jenis_sampah' (sesuai nama tabel database Anda)
        $setorans = SetoranSampah::select(
                'setoran_sampahs.jenis_sampah', 
                DB::raw('SUM(setoran_sampahs.total_berat) as total_berat'),
                DB::raw('MAX(setoran_sampahs.created_at) as created_at'),
                'jenis_sampah_master.upload_image'
            )
            ->leftJoin('jenis_sampah as jenis_sampah_master', 'setoran_sampahs.jenis_sampah', '=', 'jenis_sampah_master.nama_sampah')
            ->groupBy('setoran_sampahs.jenis_sampah', 'jenis_sampah_master.upload_image')
            ->get();
        
        $chartSetorans = $setorans;

        // 3. Data laporan eksternal
        $laporansEksternal = LaporanEksternal::latest()->get();

        return view('bank-sampah-induk.index', compact('laporansRw', 'setorans', 'chartSetorans', 'laporansEksternal'));
    }
}