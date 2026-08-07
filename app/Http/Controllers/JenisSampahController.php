<?php

namespace App\Http\Controllers;

use App\Models\JenisSampah;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth; // <-- Tambahkan import Auth

class JenisSampahController extends Controller
{
    // Fungsi privat untuk memblokir akses jika bukan admin
    private function authorizeAdmin()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin sebagai Admin.');
        }
    }

    public function index()
    {
        $this->authorizeAdmin();

        $jenisSampahs = JenisSampah::latest()->get();
        return view('jenis-sampah.index', compact('jenisSampahs'));
    }

    public function store(Request $request)
    {
        $this->authorizeAdmin();

        $request->validate([
            'nama_sampah'  => 'required|string|max:255',
            'kategori'     => 'required|string|max:100',
            'harga_kg'     => 'required|numeric|min:0',
            'deskripsi'    => 'nullable|string',
            'upload_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->all();
        if ($request->hasFile('upload_image')) {
            $imagePath = $request->file('upload_image')->store('jenis-sampah', 'public');
            $data['upload_image'] = $imagePath;
        }

        JenisSampah::create($data);

        return redirect()->route('jenis-sampah.index')->with('success', 'Jenis sampah berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $this->authorizeAdmin();

        $jenisSampahs = JenisSampah::latest()->get();
        $editData = JenisSampah::findOrFail($id);
        
        return view('jenis-sampah.index', compact('jenisSampahs', 'editData'));
    }

    public function update(Request $request, $id)
    {
        $this->authorizeAdmin();

        $request->validate([
            'nama_sampah'  => 'required|string|max:255',
            'kategori'     => 'required|string|max:100',
            'harga_kg'     => 'required|numeric|min:0',
            'deskripsi'    => 'nullable|string',
            'upload_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $jenisSampah = JenisSampah::findOrFail($id);
        $data = $request->all();

        if ($request->hasFile('upload_image')) {
            if ($jenisSampah->upload_image && Storage::disk('public')->exists($jenisSampah->upload_image)) {
                Storage::disk('public')->delete($jenisSampah->upload_image);
            }
            $data['upload_image'] = $request->file('upload_image')->store('jenis-sampah', 'public');
        }

        $jenisSampah->update($data);

        return redirect()->route('jenis-sampah.index')->with('success', 'Jenis sampah berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $this->authorizeAdmin();

        $jenisSampah = JenisSampah::findOrFail($id);

        if ($jenisSampah->upload_image && Storage::disk('public')->exists($jenisSampah->upload_image)) {
            Storage::disk('public')->delete($jenisSampah->upload_image);
        }

        $jenisSampah->delete();

        return redirect()->route('jenis-sampah.index')->with('success', 'Jenis sampah berhasil dihapus!');
    }
}