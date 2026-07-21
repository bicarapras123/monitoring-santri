<?php

namespace App\Http\Controllers;

use App\Models\JenisSampah;
use Illuminate\Http\Request;

class InformationController extends Controller
{
    public function index(Request $request)
    {
        $query = JenisSampah::query();

        // Fitur Pencarian berdasarkan nama sampah
        if ($request->has('search') && $request->search != '') {
            $query->where('nama_sampah', 'like', '%' . $request->search . '%');
        }

        // Fitur Filter Kategori (jika dipilih dan bukan 'Semua')
        if ($request->has('kategori') && $request->kategori != 'Semua' && $request->kategori != '') {
            $query->where('kategori', $request->kategori);
        }

        $jenisSampahs = $query->latest()->get();

        // AMBIL DATA KATEGORI UNIK DARI DATABASE
        $listKategori = JenisSampah::select('kategori')->distinct()->pluck('kategori')->toArray();
        array_unshift($listKategori, 'Semua');

        $kategoriAktif = $request->get('kategori', 'Semua');
        $keyword = $request->get('search', '');

        // PASTIKAN $listKategori IKUT DI-COMPACT KE VIEW
        return view('information', compact('jenisSampahs', 'listKategori', 'kategoriAktif', 'keyword'));
    }
}