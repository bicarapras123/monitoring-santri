<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SetoranSampah;
use App\Models\Penarikan;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Mengambil nama nasabah berdasarkan relasi atau nama akun user yang login
        $namaNasabah = $user->nasabah ? $user->nasabah->nama_lengkap : $user->name;

        // Menghitung total jumlah setoran sampah (kali transaksi)
        $totalSetoran = SetoranSampah::where('nama_lengkap', $namaNasabah)->count();

        // 1. Menghitung total pendapatan dari setoran sampah
        $pendapatanSampah = SetoranSampah::where('setoran_sampahs.nama_lengkap', $namaNasabah)
            ->leftJoin('jenis_sampah', 'setoran_sampahs.jenis_sampah', '=', 'jenis_sampah.nama_sampah')
            ->sum(DB::raw('setoran_sampahs.total_berat * COALESCE(jenis_sampah.harga_kg, 0)'));

        // 2. Menghitung total penarikan yang sedang diproses (pending) atau sudah disetujui
        $totalPenarikan = Penarikan::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'disetujui'])
            ->sum('jumlah_penarikan');

        // 3. Total Saldo Keseluruhan
        $totalTabungan = $pendapatanSampah - $totalPenarikan;
        if ($totalTabungan < 0) {
            $totalTabungan = 0;
        }

        // 4. Mengambil riwayat penarikan (mencakup reguler dan cash) dari tabel penarikans
        $riwayatPenarikan = Penarikan::where('user_id', $user->id)->latest()->get();

        return view('dashboard', compact('totalSetoran', 'totalTabungan', 'riwayatPenarikan'));
    }
    
    // Method untuk menyimpan pengajuan penarikan saldo dari form dashboard
    public function storePenarikan(Request $request)
    {
        $user = auth()->user();
        $namaNasabah = $user->nasabah ? $user->nasabah->nama_lengkap : $user->name;
    
        // Hitung ulang total pendapatan (kode validasi saldo Anda...)
        $pendapatanSampah = SetoranSampah::where('setoran_sampahs.nama_lengkap', $namaNasabah)
            ->leftJoin('jenis_sampah', 'setoran_sampahs.jenis_sampah', '=', 'jenis_sampah.nama_sampah')
            ->sum(DB::raw('setoran_sampahs.total_berat * COALESCE(jenis_sampah.harga_kg, 0)'));
    
        $totalPenarikan = Penarikan::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'disetujui'])
            ->sum('jumlah_penarikan');
    
        $totalTabungan = $pendapatanSampah - $totalPenarikan;
    
        if ($totalTabungan <= 50000) {
            return redirect()->back()->with('error', 'Maaf, saldo tabungan Anda tidak mencukupi...');
        }
    
        $saldoBisaDitarik = $totalTabungan - 50000;
    
        $request->validate([
            'jumlah_penarikan' => 'required|numeric|min:1|max:' . $saldoBisaDitarik,
            'metode_pencairan' => 'required|string',
            'bukti_pdf' => 'required|mimes:pdf|max:2048',
        ]);
    
        // 1. Ambil data rekening/e-wallet langsung dari relasi nasabah yang login
        $rekeningUser = $user->nasabah ? $user->nasabah->rekening : null;
    
        // 2. Simpan file PDF
        $pathPdf = $request->file('bukti_pdf')->store('bukti_penarikan', 'public');
    
        // 3. SIMPAN DATA KE TABEL PENARIKANS LENGKAP DENGAN NOMOR REKENINGNYA
        Penarikan::create([
            'user_id'          => $user->id,
            'jumlah_penarikan' => $request->jumlah_penarikan,
            'metode_pencairan' => $request->metode_pencairan,
            'jenis_ewallet'    => $rekeningUser ? $rekeningUser->jenis_ewallet : null,
            'nomor_rekening'   => $rekeningUser ? $rekeningUser->nomor_rekening : null,
            'bukti_pdf'        => $pathPdf,
            'status'           => 'pending',
        ]);
    
        return redirect()->back()->with('success', 'Pengajuan penarikan saldo berhasil dikirim dan menunggu verifikasi admin.');
    }
}