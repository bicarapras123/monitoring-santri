<?php

namespace App\Http\Controllers;

use App\Models\JenisSampah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage; // <-- Tambahkan ini untuk mengelola file

class JenisSampahController extends Controller
{
    public function index()
    {
        $jenisSampahs = JenisSampah::latest()->get();
        return view('jenis-sampah.index', compact('jenisSampahs'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama_sampah'  => 'required|string|max:255',
            'kategori'     => 'required|string|max:100',
            'harga_kg'     => 'required|numeric|min:0',
            'deskripsi'    => 'nullable|string',
            'upload_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // <-- Tambahan validasi gambar
        ]);

        // <-- Tambahan logika upload gambar
        $data = $request->all();
        if ($request->hasFile('upload_image')) {
            $imagePath = $request->file('upload_image')->store('jenis-sampah', 'public');
            $data['upload_image'] = $imagePath;
        }

        JenisSampah::create($data); // Menggunakan $data yang sudah disisipkan path gambar

        return redirect()->route('jenis-sampah.index')->with('success', 'Jenis sampah berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $jenisSampahs = JenisSampah::latest()->get();
        $editData = JenisSampah::findOrFail($id);
        
        return view('jenis-sampah.index', compact('jenisSampahs', 'editData'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama_sampah'  => 'required|string|max:255',
            'kategori'     => 'required|string|max:100',
            'harga_kg'     => 'required|numeric|min:0',
            'deskripsi'    => 'nullable|string',
            'upload_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048', // <-- Tambahan validasi gambar
        ]);

        $jenisSampah = JenisSampah::findOrFail($id);
        $data = $request->all();

        // <-- Tambahan logika update & ganti gambar baru
        if ($request->hasFile('upload_image')) {
            // Hapus gambar lama jika ada
            if ($jenisSampah->upload_image && Storage::disk('public')->exists($jenisSampah->upload_image)) {
                Storage::disk('public')->delete($jenisSampah->upload_image);
            }
            // Simpan gambar baru
            $data['upload_image'] = $request->file('upload_image')->store('jenis-sampah', 'public');
        }

        $jenisSampah->update($data);

        return redirect()->route('jenis-sampah.index')->with('success', 'Jenis sampah berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $jenisSampah = JenisSampah::findOrFail($id);

        // <-- Tambahan logika hapus file gambar dari storage saat data dihapus
        if ($jenisSampah->upload_image && Storage::disk('public')->exists($jenisSampah->upload_image)) {
            Storage::disk('public')->delete($jenisSampah->upload_image);
        }

        $jenisSampah->delete();

        return redirect()->route('jenis-sampah.index')->with('success', 'Jenis sampah berhasil dihapus!');
    }
}