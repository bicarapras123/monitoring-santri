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

        // 4. Mengambil semua riwayat penarikan milik user yang sedang login (terbaru di atas)
        $riwayatPenarikan = Penarikan::where('user_id', $user->id)->latest()->get();

        return view('dashboard', compact('totalSetoran', 'totalTabungan', 'riwayatPenarikan'));
    }

    // Method untuk menyimpan pengajuan penarikan saldo dari form dashboard
    public function storePenarikan(Request $request)
    {
        $user = auth()->user();
        $namaNasabah = $user->nasabah ? $user->nasabah->nama_lengkap : $user->name;

        // Hitung ulang total pendapatan
        $pendapatanSampah = SetoranSampah::where('setoran_sampahs.nama_lengkap', $namaNasabah)
            ->leftJoin('jenis_sampah', 'setoran_sampahs.jenis_sampah', '=', 'jenis_sampah.nama_sampah')
            ->sum(DB::raw('setoran_sampahs.total_berat * COALESCE(jenis_sampah.harga_kg, 0)'));

        $totalPenarikan = Penarikan::where('user_id', $user->id)
            ->whereIn('status', ['pending', 'disetujui'])
            ->sum('jumlah_penarikan');

        $totalTabungan = $pendapatanSampah - $totalPenarikan;

        // Aturan: Saldo tabungan wajib menyisakan minimal Rp 50.000 (saldo mati/endepan)
        if ($totalTabungan <= 50000) {
            return redirect()->back()->with('error', 'Maaf, saldo tabungan Anda tidak mencukupi. Saldo wajib menyisakan minimal Rp 50.000 sebagai endepan yang tidak boleh kurang.');
        }

        // Saldo maksimal yang benar-benar bisa ditarik
        $saldoBisaDitarik = $totalTabungan - 50000;

        // Validasi inputan form
        $request->validate([
            'jumlah_penarikan' => 'required|numeric|min:1|max:' . $saldoBisaDitarik,
            'metode_pencairan' => 'required|string',
            'bukti_pdf' => 'required|mimes:pdf|max:2048',
        ], [
            'jumlah_penarikan.min' => 'Nominal penarikan minimal adalah Rp 1.',
            'jumlah_penarikan.max' => 'Penarikan melebihi batas. Saldo endepan wajib menyisakan minimal Rp 50.000.',
            'bukti_pdf.required' => 'File PDF bukti cetak dashboard wajib diupload.',
            'bukti_pdf.mimes' => 'File bukti harus berformat PDF.',
            'bukti_pdf.max' => 'Ukuran file PDF maksimal 2MB.',
        ]);

        // Simpan file PDF ke storage/public/bukti_penarikan
        $pathPdf = $request->file('bukti_pdf')->store('bukti_penarikan', 'public');

        // Simpan data ke tabel penarikans
        Penarikan::create([
            'user_id' => $user->id,
            'jumlah_penarikan' => $request->jumlah_penarikan,
            'metode_pencairan' => $request->metode_pencairan,
            'bukti_pdf' => $pathPdf,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Pengajuan penarikan saldo berhasil dikirim dan menunggu verifikasi admin.');
    }
}