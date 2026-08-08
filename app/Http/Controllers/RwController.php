<?php

namespace App\Http\Controllers;

use App\Models\LaporanEksternal;
use App\Models\Penarikan;
use App\Models\SetoranSampah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class RwController extends Controller
{
    public function index()
    {
        // ... (cek role)
        $laporan = LaporanEksternal::where('tujuan', 'RW')->latest()->get();
    
        // Data Statistik Utama
        $totalSampah = SetoranSampah::sum('total_berat');
        $totalTransaksi = Penarikan::count();
        $totalNasabah = \App\Models\User::where('role', 'nasabah')->count();
        
        $jenisSampah = SetoranSampah::select('jenis_sampah', DB::raw('SUM(total_berat) as berat'))
                                                 ->groupBy('jenis_sampah')->get();
    
        // Data Real untuk Grafik (Per Bulan)
        $grafikData = SetoranSampah::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as tahun_bulan"),
            DB::raw("DATE_FORMAT(created_at, '%M') as bulan"),
            DB::raw("SUM(total_berat) as total_berat")
        )
        ->groupBy('tahun_bulan', 'bulan')
        ->orderBy('tahun_bulan', 'ASC')
        ->get();
    
        // 1. TAMBAHKAN INI: Data untuk Pie Chart berdasarkan metode_pencairan
        $pieChartData = Penarikan::select(
            'metode_pencairan', 
            DB::raw("SUM(jumlah_penarikan) as total_uang")
        )
        ->groupBy('metode_pencairan')
        ->get();

        // 2. PASTIKAN '$pieChartData' DIMASUKKAN KE DALAM COMPACT
        return view('rw.laporan', compact(
            'laporan', 
            'totalSampah', 
            'totalTransaksi', 
            'totalNasabah', 
            'jenisSampah', 
            'grafikData', 
            'pieChartData'
        ));
    }
}