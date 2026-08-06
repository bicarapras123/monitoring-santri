<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RekeningNasabah;
use Illuminate\Support\Facades\Auth;

class RekeningNasabahController extends Controller
{
    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'jenis_ewallet' => 'required|in:GoPay,DANA',
            'nomor_rekening' => 'required|string|max:50',
            'foto_ktp' => 'required|image|mimes:jpeg,png,jpg|max:2048',
        ]);
    
        $nasabah = Auth::user()->nasabah;
    
        if (!$nasabah) {
            return redirect()->back()->with('error', 'Data diri nasabah belum ditemukan.');
        }
    
        if ($request->hasFile('foto_ktp')) {
            $file = $request->file('foto_ktp');
            $filename = time() . '_' . $file->getClientOriginalName();
            $path = $file->storeAs('ktp', $filename, 'public');
    
            RekeningNasabah::create([
                'nasabah_id' => $nasabah->id,
                'jenis_ewallet' => $request->jenis_ewallet,
                'nomor_rekening' => $request->nomor_rekening,
                'foto_ktp' => $path,
            ]);
        }
    
        return redirect()->back()->with('success', 'Rekening e-wallet dan KTP berhasil disimpan!');
    }

    public function create()
        {
            // Redirect kembali ke dashboard karena form sudah ada di dalam dashboard
            return redirect()->route('dashboard');
        }

}