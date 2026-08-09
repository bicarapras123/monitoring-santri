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
    // Fungsi privat untuk memblokir akses jika bukan role rw
    private function authorizeRw()
    {
        if (!Auth::check() || Auth::user()->role !== 'rw') {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin sebagai RW.');
        }
    }

    public function index()
    {
        $this->authorizeRw(); // Cek keamanan akses RW

        $laporan = LaporanEksternal::where('tujuan', 'RW')->latest()->get();
    
        // Data Statistik Utama
        $totalSampah = SetoranSampah::sum('total_berat');
        $totalTransaksi = Penarikan::count();
        $totalNasabah = \App\Models\Nasabah::count();

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

        // Data Real untuk Grafik Garis (Per Hari - 30 Hari Terakhir)
        $grafikHarian = SetoranSampah::select(
            DB::raw("DAYNAME(created_at) as nama_hari"),
            DB::raw("SUM(total_berat) as total_berat")
        )
        ->where('created_at', '>=', now()->startOfWeek()) // Mulai dari senin minggu ini
        ->where('created_at', '<=', now()->endOfWeek())   // Sampai minggu
        ->groupBy('nama_hari', DB::raw("DAYOFWEEK(created_at)"))
        ->orderBy(DB::raw("FIELD(nama_hari, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')"))
        ->get();
    
        // 1. Data untuk Pie Chart berdasarkan metode_pencairan
        $pieChartData = Penarikan::select(
            'metode_pencairan', 
            DB::raw("SUM(jumlah_penarikan) as total_uang")
        )
        ->groupBy('metode_pencairan')
        ->get();

        // 2. Masukkan '$grafikHarian' dan '$pieChartData' ke dalam compact
        return view('rw.laporan', compact(
            'laporan', 
            'totalSampah', 
            'totalTransaksi', 
            'totalNasabah', 
            'jenisSampah', 
            'grafikData', 
            'grafikHarian',
            'pieChartData'
        ));
    }

    public function cetakPdf()
    {
        $this->authorizeRw(); // Cek keamanan akses RW

        $laporan = LaporanEksternal::where('tujuan', 'RW')->latest()->get();
        
        $totalSampah = SetoranSampah::sum('total_berat');
        $totalTransaksi = Penarikan::count();
        $totalNasabah = \App\Models\Nasabah::count();
        
        $jenisSampah = SetoranSampah::select('jenis_sampah', DB::raw('SUM(total_berat) as berat'))
                                   ->groupBy('jenis_sampah')->get();
    
        // Tambahkan query grafik bulanan
        $grafikData = SetoranSampah::select(
            DB::raw("DATE_FORMAT(created_at, '%Y-%m') as tahun_bulan"),
            DB::raw("DATE_FORMAT(created_at, '%M') as bulan"),
            DB::raw("SUM(total_berat) as total_berat")
        )
        ->groupBy('tahun_bulan', 'bulan')
        ->orderBy('tahun_bulan', 'ASC')
        ->get();
    
        // Tambahkan query grafik harian (Senin - Minggu)
        $grafikHarian = SetoranSampah::select(
            DB::raw("DAYNAME(created_at) as nama_hari"),
            DB::raw("SUM(total_berat) as total_berat")
        )
        ->where('created_at', '>=', now()->startOfWeek())
        ->where('created_at', '<=', now()->endOfWeek())
        ->groupBy('nama_hari', DB::raw("DAYOFWEEK(created_at)"))
        ->orderBy(DB::raw("FIELD(nama_hari, 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday')"))
        ->get();
    
        // Tambahkan query pie chart metode pencairan
        $pieChartData = Penarikan::select(
            'metode_pencairan', 
            DB::raw("SUM(jumlah_penarikan) as total_uang")
        )
        ->groupBy('metode_pencairan')
        ->get();
    
        return view('rw.laporan-pdf', compact(
            'laporan', 
            'totalSampah', 
            'totalTransaksi', 
            'totalNasabah', 
            'jenisSampah', 
            'grafikData', 
            'grafikHarian', 
            'pieChartData'
        ));
    }
}