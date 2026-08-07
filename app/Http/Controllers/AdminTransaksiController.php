<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\SetoranSampah;
use App\Models\Penarikan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // Pastikan Facade Auth di-import

class AdminTransaksiController extends Controller
{
    // Fungsi privat untuk memblokir akses jika bukan admin
    private function authorizeAdmin()
    {
        if (!Auth::check() || Auth::user()->role !== 'admin') {
            abort(403, 'Akses ditolak. Anda tidak memiliki izin sebagai Admin.');
        }
    }

    public function index(Request $request)
    {
        $this->authorizeAdmin(); // Cek keamanan akses admin

        $search = $request->input('search');

        // 1. Mengambil rekap data yang Dikelompokkan per Nasabah untuk Tabel Utama
        $nasabahs = SetoranSampah::select(
                'setoran_sampahs.nama_lengkap',
                'setoran_sampahs.nomor_telephone',
                DB::raw('COUNT(setoran_sampahs.id) as total_setoran'),
                DB::raw('SUM(setoran_sampahs.total_berat) as akumulasi_berat'),
                DB::raw('SUM(setoran_sampahs.total_berat * COALESCE(jenis_sampah.harga_kg, 0)) as total_keseluruhan_tabungan')
            )
            ->leftJoin('jenis_sampah', 'setoran_sampahs.jenis_sampah', '=', 'jenis_sampah.nama_sampah')
            ->when($search, function($query, $search) {
                return $query->where('setoran_sampahs.nama_lengkap', 'like', "%{$search}%")
                             ->orWhere('setoran_sampahs.nomor_telephone', 'like', "%{$search}%");
            })
            ->groupBy('setoran_sampahs.nama_lengkap', 'setoran_sampahs.nomor_telephone')
            ->orderByRaw('MAX(setoran_sampahs.created_at) DESC')
            ->paginate(10);

        // Kurangi total keseluruhan tabungan dengan total penarikan aktif (pending/disetujui) secara real-time
        foreach ($nasabahs as $nasabah) {
            // Hitung total penarikan berdasarkan nama lengkap nasabah tersebut
            $totalPenarikan = Penarikan::where(function($query) use ($nasabah) {
                    $query->whereHas('user.nasabah', function($q) use ($nasabah) {
                        $q->where('nama_lengkap', $nasabah->nama_lengkap);
                    });
                })
                ->whereIn('status', ['pending', 'disetujui'])
                ->sum('jumlah_penarikan');

            // Saldo akhir = total pendapatan sampah - total penarikan (minimal 0)
            $nasabah->total_keseluruhan_tabungan = max(0, $nasabah->total_keseluruhan_tabungan - $totalPenarikan);
        }

        // 2. Mengambil Detail Riwayat Setoran nasabah
        $namaNasabahs = $nasabahs->pluck('nama_lengkap');

        $setorans = SetoranSampah::select(
                'setoran_sampahs.*',
                DB::raw('(setoran_sampahs.total_berat * COALESCE(jenis_sampah.harga_kg, 0)) as total_tabungan')
            )
            ->leftJoin('jenis_sampah', 'setoran_sampahs.jenis_sampah', '=', 'jenis_sampah.nama_sampah')
            ->whereIn('setoran_sampahs.nama_lengkap', $namaNasabahs)
            ->orderBy('setoran_sampahs.created_at', 'desc')
            ->get();

        // 3. Menyematkan status penarikan dari tabel penarikans ke setiap item setoran
        $semuaSetoran = $setorans->map(function ($item) {
            $penarikan = null;
            
            if (!empty($item->user_id)) {
                $penarikan = Penarikan::where('user_id', $item->user_id)->latest()->first();
            }
            
            if (!$penarikan) {
                $penarikan = Penarikan::whereHas('user.nasabah', function($q) use ($item) {
                    $q->where('nama_lengkap', $item->nama_lengkap);
                })->latest()->first();
            }

            // Ambil status dari tabel penarikans, default 'pending' jika belum ada pengajuan
            $item->status_penarikan = $penarikan ? strtolower($penarikan->status) : 'pending';
            
            return $item;
        })->groupBy('nama_lengkap');

        return view('admin.transaksi.index', compact('nasabahs', 'semuaSetoran'));
    }
}