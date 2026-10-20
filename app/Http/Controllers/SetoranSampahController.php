<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\SetoranSampah;
use App\Models\Nasabah;
use App\Models\JenisSampah;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class SetoranSampahController extends Controller
{
    private function authorizeAdmin()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin sebagai Admin.');
        }
    }

    // Menampilkan form setoran sampah dengan mengirim data dari controller
    public function create()
    {
        $this->authorizeAdmin(); 
    
        // Ubah 'rekeningNasabahs' menjadi 'rekening'
        $nasabahs = Nasabah::with('rekening')->get(); 
        $jenisSampahs = JenisSampah::all();
    
        return view('nasabah.setoran.create', compact('nasabahs', 'jenisSampahs'));
    }

    // Menyimpan data setoran sampah ke database
    public function store(Request $request)
    {
        $this->authorizeAdmin();
    
        $request->validate([
            'nasabah_id' => 'required|exists:nasabahs,id',
            'tanggal_setoran' => 'required|date', // <-- Tambahkan validasi tanggal
            'jenis_rekening' => 'required',
            'nomor_rekening' => 'nullable',
            'foto_sampah' => 'required|image|max:2048',
            'sampah' => 'required|array',
            'sampah.*.jenis_sampah_id' => 'required|exists:jenis_sampah,id',
            'sampah.*.berat' => 'required|numeric|min:0.01',
        ]);
    
        $nasabah = Nasabah::findOrFail($request->nasabah_id);
    
        // 1. Hitung total berat dan rangkum nama jenis sampah dari input multi-item
        $totalBerat = 0;
        $arrJenisSampah = [];
    
        foreach ($request->sampah as $item) {
            $totalBerat += floatval($item['berat']);
            
            // Ambil nama jenis sampah berdasarkan ID jika diperlukan untuk disimpan ke teks
            $jenisSampahObj = \App\Models\JenisSampah::find($item['jenis_sampah_id']);
            if ($jenisSampahObj) {
                $arrJenisSampah[] = $jenisSampahObj->nama_sampah . ' (' . $item['berat'] . ' Kg)';
            }
        }
    
        $stringJenisSampah = implode(', ', $arrJenisSampah);
    
        // 2. Upload foto
        $fotoPath = $request->file('foto_sampah')->store('foto-sampah', 'public');
    
        // 3. Simpan ke database dengan nilai yang valid
        SetoranSampah::create([
            'user_id' => auth()->id(),
            'tanggal_setoran' => $request->tanggal_setoran, // <-- Simpan tanggal dari inputan form
            'nama_lengkap' => $nasabah->nama_lengkap,
            'alamat_lengkap' => $nasabah->alamat_lengkap,
            'nomor_telephone' => $nasabah->nomor_telepon,
            'jenis_rekening' => $request->jenis_rekening,
            'nomor_rekening' => $request->nomor_rekening,
            'jenis_sampah' => $stringJenisSampah, // Tersimpan rapi berupa teks rangkumannya
            'total_berat' => $totalBerat,         // Berupa angka desimal yang valid (contoh: 14.00)
            'foto_sampah' => $fotoPath,
            'status' => 'pending',
        ]);
    
        return redirect()->route('setoran.create')->with('success', 'Pengajuan setoran sampah berhasil dikirim!');
    }
}