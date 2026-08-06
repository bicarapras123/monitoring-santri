<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PencairanController extends Controller
{
    public function cetakCash(Request $request)
    {
        // Pastikan user memiliki data nasabah
        $nasabah = Auth::user()->nasabah;

        if (!$nasabah) {
            return redirect()->back()->with('error', 'Data nasabah tidak ditemukan.');
        }

        // Tampilkan view khusus untuk formulir pencairan cash
        return view('nasabah.cetak-cash', compact('nasabah'));
    }
}