<?php

namespace App\Http\Controllers;

use App\Models\Nasabah;
use Illuminate\Http\Request;
use App\Models\RekeningNasabah; // <- Pastikan Model ini di-import

class AdminNasabahController extends Controller
{
    // Halaman 1: Semua Nasabah yang Terdaftar di Sistem
    public function index()
    {
        // Hanya panggil relasi 'rekening' saja
        $nasabahs = Nasabah::with(['rekening'])->latest()->get();

        return view('admin.nasabah.index', compact('nasabahs'));
    }

    // Halaman 2: Hanya Nasabah yang SUDAH Mengajukan Rekening/E-Wallet
    public function rekeningAjuan()
    {
        // Hanya panggil relasi 'rekening' saja
        $nasabahs = Nasabah::with(['rekening'])->has('rekening')->latest()->get();

        return view('admin.nasabah.rekening', compact('nasabahs'));
    }

    public function verifyRekening($id)
    {
        $rekening = RekeningNasabah::findOrFail($id);
        
        $rekening->update([
            'status' => 'verified'
        ]);

        return redirect()->back()->with('success', 'Pengajuan rekening nasabah berhasil diverifikasi!');
    }

}