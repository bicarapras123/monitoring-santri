<?php

namespace App\Http\Controllers;

use App\Models\Berita;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class BeritaController extends Controller
{
    /**
     * Tampilkan halaman manajemen berita (List + Form Tambah/Edit).
     */
    public function publicIndex(Request $request)
    {
        $query = Berita::query();
    
        if ($request->has('search') && $request->search != '') {
            $query->where('judul', 'like', '%' . $request->search . '%')
                  ->orWhere('konten', 'like', '%' . $request->search . '%');
        }
    
        if ($request->has('kategori') && $request->kategori != 'Semua' && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }
    
        $beritas = $query->latest()->get();
        $kategoriAktif = $request->get('kategori', 'Semua');
        $keyword = $request->get('search', '');
    
        return view('news', compact('beritas', 'kategoriAktif', 'keyword'));
    }

    /**
     * Simpan berita baru ke database.
     */
    public function store(Request $request)
    {
        $request->validate([
            'judul'        => 'required|string|max:255',
            'kategori'     => 'required|in:Pengumuman,Kegiatan,Edukasi,Artikel',
            'konten'       => 'required|string',
            'upload_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = null;
        if ($request->hasFile('upload_image')) {
            $imagePath = $request->file('upload_image')->store('berita-images', 'public');
        }

        Berita::create([
            'judul'        => $request->judul,
            'slug'         => Str::slug($request->judul) . '-' . time(),
            'kategori'     => $request->kategori,
            'konten'       => $request->konten,
            'upload_image' => $imagePath,
        ]);

        return redirect()->route('berita.index')->with('success', 'Berita berhasil ditambahkan!');
    }

    /**
     * Tampilkan form edit (menggunakan redirect query string agar tampil di halaman yang sama).
     */
    public function edit($id)
    {
        return redirect()->route('berita.index', ['edit' => $id]);
    }

    /**
     * Perbarui data berita.
     */
    public function update(Request $request, $id)
    {
        $berita = Berita::findOrFail($id);

        $request->validate([
            'judul'        => 'required|string|max:255',
            'kategori'     => 'required|in:Pengumuman,Kegiatan,Edukasi,Artikel',
            'konten'       => 'required|string',
            'upload_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        $imagePath = $berita->upload_image;
        if ($request->hasFile('upload_image')) {
            // Hapus gambar lama jika ada
            if ($imagePath && Storage::disk('public')->exists($imagePath)) {
                Storage::disk('public')->delete($imagePath);
            }
            $imagePath = $request->file('upload_image')->store('berita-images', 'public');
        }

        $berita->update([
            'judul'        => $request->judul,
            'slug'         => Str::slug($request->judul) . '-' . $berita->id,
            'kategori'     => $request->kategori,
            'konten'       => $request->konten,
            'upload_image' => $imagePath,
        ]);

        return redirect()->route('berita.index')->with('success', 'Berita berhasil diperbarui!');
    }

    /**
     * Hapus data berita.
     */
    public function destroy($id)
    {
        $berita = Berita::findOrFail($id);

        // Hapus file gambar jika ada
        if ($berita->upload_image && Storage::disk('public')->exists($berita->upload_image)) {
            Storage::disk('public')->delete($berita->upload_image);
        }

        $berita->delete();

        return redirect()->route('berita.index')->with('success', 'Berita berhasil dihapus!');
    }
}