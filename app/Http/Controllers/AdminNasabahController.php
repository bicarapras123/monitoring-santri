<?php

namespace App\Http\Controllers;

use App\Models\Nasabah;
use Illuminate\Http\Request;
use App\Models\RekeningNasabah;
use Illuminate\Support\Facades\Auth;

class AdminNasabahController extends Controller
{
    // Fungsi privat untuk memblokir akses jika bukan admin
    private function authorizeAdmin()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin sebagai Admin.');
        }
    }

    // Halaman 1: Semua Nasabah yang Terdaftar di Sistem
    public function index()
    {
        $this->authorizeAdmin(); // Pengecekan keamanan

        $nasabahs = Nasabah::with(['rekening'])->latest()->get();

        return view('admin.nasabah.index', compact('nasabahs'));
    }

    // Halaman 2: Hanya Nasabah yang SUDAH Mengajukan Rekening/E-Wallet
    public function rekeningAjuan()
    {
        $this->authorizeAdmin(); // Pengecekan keamanan

        $nasabahs = Nasabah::with(['rekening'])->has('rekening')->latest()->get();

        return view('admin.nasabah.rekening', compact('nasabahs'));
    }

    public function verifyRekening($id)
    {
        $this->authorizeAdmin(); // Pengecekan keamanan

        $rekening = RekeningNasabah::findOrFail($id);
        
        $rekening->update([
            'status' => 'verified'
        ]);

        return redirect()->back()->with('success', 'Pengajuan rekening nasabah berhasil diverifikasi!');
    }
}