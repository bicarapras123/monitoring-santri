<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SetoranSampah;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SetoranSampahController extends Controller
{
    // Menampilkan form setoran sampah untuk nasabah
    public function create()
    {
        return view('nasabah.setoran.create');
    }

    // Menyimpan data setoran sampah ke database
    public function store(Request $request)
    {
        $request->validate([
            'nama_lengkap' => 'required|string|max:255',
            'alamat_lengkap' => 'required|string',
            'nomor_telephone' => 'required|string|max:20',
            'nomor_rekening' => 'nullable|string|max:50',
            'jenis_rekening' => 'nullable|string|max:50',
            'jenis_sampah' => 'required|string|max:100',
            'foto_sampah' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'total_berat' => 'required|numeric|min:0.01',
        ]);

        $path = null;
        if ($request->hasFile('foto_sampah')) {
            $path = $request->file('foto_sampah')->store('foto-sampah', 'public');
        }

        SetoranSampah::create([
            'user_id' => Auth::id(),
            'nama_lengkap' => $request->nama_lengkap,
            'alamat_lengkap' => $request->alamat_lengkap,
            'nomor_telephone' => $request->nomor_telephone,
            'nomor_rekening' => $request->nomor_rekening,
            'jenis_rekening' => $request->jenis_rekening,
            'jenis_sampah' => $request->jenis_sampah,
            'foto_sampah' => $path,
            'total_berat' => $request->total_berat,
            'status' => 'pending',
        ]);

        return redirect()->back()->with('success', 'Pengajuan setoran sampah berhasil dikirim dan menunggu konfirmasi admin!');
    }
}