<?php

namespace App\Http\Controllers;

use App\Models\RwLaporanWarga;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RwLaporanController extends Controller
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

        $laporans = RwLaporanWarga::latest()->paginate(10);
        return view('rw.kelola-laporan.index', compact('laporans'));
    }

    public function create()
    {
        $this->authorizeRw(); // Cek keamanan akses RW

        return view('rw.kelola-laporan.create');
    }

    public function store(Request $request)
    {
        $this->authorizeRw(); // Cek keamanan akses RW

        $request->validate([
            'nik' => 'required|string|size:16',
            'nama_lengkap' => 'required|string|max:255',
            'kelurahan' => 'required|string|max:100',
            'kecamatan' => 'required|string|max:100',
            'kode_pos' => 'required|string|max:10',
            'kota' => 'required|string|max:100',
            'nomor_telepon' => 'required|string|max:20',
            'file_upload' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
            'is_data_benar' => 'accepted',
            'is_setuju_ketentuan' => 'accepted',
            'is_disahkan_pengurus' => 'accepted',
        ]);

        $filePath = null;
        if ($request->hasFile('file_upload')) {
            $filePath = $request->file('file_upload')->store('laporan-warga', 'public');
        }

        RwLaporanWarga::create([
            'nik' => $request->nik,
            'nama_lengkap' => $request->nama_lengkap,
            'kelurahan' => $request->kelurahan,
            'kecamatan' => $request->kecamatan,
            'kode_pos' => $request->kode_pos,
            'kota' => $request->kota,
            'nomor_telepon' => $request->nomor_telepon,
            'file_upload' => $filePath,
            'is_data_benar' => $request->has('is_data_benar'),
            'is_setuju_ketentuan' => $request->has('is_setuju_ketentuan'),
            'is_disahkan_pengurus' => $request->has('is_disahkan_pengurus'),
        ]);

        return redirect()->route('rw.kelolaporan.index')->with('success', 'Laporan warga berhasil disahkan dan disimpan.');
    }
}