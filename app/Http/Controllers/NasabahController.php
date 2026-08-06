<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Nasabah;
use Illuminate\Support\Facades\Auth;

class NasabahController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nik' => 'required|string|size:16|unique:nasabahs,nik',
            'nama_lengkap' => 'required|string|max:255',
            'tempat_lahir' => 'required|string|max:255',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'nomor_telepon' => 'required|string|max:15',
            'nama_orang_tua' => 'required|string|max:255',
            'alamat_lengkap' => 'required|string',
        ]);

        // Simpan data diri dengan menghubungkan ke ID User yang login
        Nasabah::create([
            'user_id' => Auth::id(),
            'nik' => $request->nik,
            'nama_lengkap' => $request->nama_lengkap,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'nomor_telepon' => $request->nomor_telepon,
            'nama_orang_tua' => $request->nama_orang_tua,
            'alamat_lengkap' => $request->alamat_lengkap,
        ]);

        return redirect()->back()->with('success', 'Pendaftaran data diri nasabah berhasil!');
    }
}