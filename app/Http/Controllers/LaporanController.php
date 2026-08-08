<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penarikan;
use App\Models\SetoranSampah;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth; // <-- Pastikan Auth di-import

class LaporanController extends Controller
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
        $this->authorizeAdmin(); // Pengecekan keamanan akses admin

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $kategori = $request->input('kategori');

        $penarikanQuery = Penarikan::with(['user.nasabah']);

        if ($startDate && $endDate) {
            $penarikanQuery->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        if ($kategori) {
            $penarikanQuery->where('metode_pencairan', $kategori);
        }

        $riwayatTransaksi = $penarikanQuery->latest()->get();

        $totalNilaiPenarikan = (clone $penarikanQuery)->sum('jumlah_penarikan');
        $totalPenarikanDisetujui = (clone $penarikanQuery)->where('status', 'disetujui')->sum('jumlah_penarikan');
        $totalPenarikanPending = (clone $penarikanQuery)->where('status', 'pending')->sum('jumlah_penarikan');
        $totalPenarikanDitolak = (clone $penarikanQuery)->where('status', 'ditolak')->sum('jumlah_penarikan');

        $jumlahTotalTransaksi = (clone $penarikanQuery)->count();
        $jumlahDisetujui = (clone $penarikanQuery)->where('status', 'disetujui')->count();
        $jumlahPending = (clone $penarikanQuery)->where('status', 'pending')->count();
        $jumlahDitolak = (clone $penarikanQuery)->where('status', 'ditolak')->count();

        $rataRataPenarikan = $jumlahTotalTransaksi > 0 ? $totalNilaiPenarikan / $jumlahTotalTransaksi : 0;

        $setoranQuery = SetoranSampah::query();
        if ($startDate && $endDate) {
            $setoranQuery->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }
        
        $totalBeratSampah = $setoranQuery->sum('total_berat');
        $totalFrekuensiSetoran = $setoranQuery->count();

        // Data untuk tabel Riwayat Setoran (baru ditambahkan)
        $riwayatSetoran = $setoranQuery->latest()->get();

        $topNasabah = SetoranSampah::select('nama_lengkap', DB::raw('SUM(total_berat) as total_berat'), DB::raw('COUNT(*) as total_transaksi'))
            ->groupBy('nama_lengkap')
            ->orderByDesc('total_berat')
            ->limit(5)
            ->get();

        $komposisiSampah = SetoranSampah::select('jenis_sampah', DB::raw('SUM(total_berat) as total_berat'))
            ->groupBy('jenis_sampah')
            ->orderByDesc('total_berat')
            ->limit(5)
            ->get();

        $rekapMetode = Penarikan::select('metode_pencairan as jenis_ewallet', DB::raw('COUNT(*) as total_pengajuan'), DB::raw('SUM(jumlah_penarikan) as total_nominal'))
            ->when($startDate && $endDate, function($q) use ($startDate, $endDate) {
                $q->whereBetween('created_at', [Carbon::parse($startDate)->startOfDay(), Carbon::parse($endDate)->endOfDay()]);
            })
            ->groupBy('metode_pencairan')
            ->get();

        return view('admin.laporan.index', compact(
            'riwayatTransaksi',
            'riwayatSetoran',
            'totalNilaiPenarikan',
            'totalPenarikanDisetujui',
            'totalPenarikanPending',
            'totalPenarikanDitolak',
            'jumlahTotalTransaksi',
            'jumlahDisetujui',
            'jumlahPending',
            'jumlahDitolak',
            'rataRataPenarikan',
            'totalBeratSampah',
            'totalFrekuensiSetoran',
            'topNasabah',
            'komposisiSampah',
            'rekapMetode',
            'startDate',
            'endDate',
            'kategori'
        ));
    }

    // Fitur Ekspor PDF (Memicu jendela cetak browser / Save as PDF)
    public function exportPdf(Request $request)
    {
        $this->authorizeAdmin(); // Pengecekan keamanan akses admin

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $kategori = $request->input('kategori');

        $penarikanQuery = Penarikan::with(['user.nasabah']);

        if ($startDate && $endDate) {
            $penarikanQuery->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        if ($kategori) {
            $penarikanQuery->where('metode_pencairan', $kategori);
        }

        $riwayatTransaksi = $penarikanQuery->latest()->get();
        $totalNilaiPenarikan = (clone $penarikanQuery)->sum('jumlah_penarikan');

        // Mengembalikan tampilan cetak khusus PDF
        return view('admin.laporan.pdf', compact('riwayatTransaksi', 'totalNilaiPenarikan', 'startDate', 'endDate', 'kategori'));
    }

    // Fitur Ekspor Excel / CSV
    public function exportExcel(Request $request)
    {
        $this->authorizeAdmin(); // Pengecekan keamanan akses admin

        $startDate = $request->input('start_date');
        $endDate = $request->input('end_date');
        $kategori = $request->input('kategori');

        $penarikanQuery = Penarikan::with(['user.nasabah']);

        if ($startDate && $endDate) {
            $penarikanQuery->whereBetween('created_at', [
                Carbon::parse($startDate)->startOfDay(),
                Carbon::parse($endDate)->endOfDay()
            ]);
        }

        if ($kategori) {
            $penarikanQuery->where('metode_pencairan', $kategori);
        }

        $data = $penarikanQuery->latest()->get();

        $filename = "laporan-penarikan-" . date('Y-m-d') . ".csv";
        
        $headers = [
            "Content-type"        => "text/csv",
            "Content-Disposition" => "attachment; filename=$filename",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use($data) {
            $file = fopen('php://output', 'w');
            
            // Tambahkan BOM agar karakter UTF-8 terbaca dengan baik di Microsoft Excel
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));

            // Header kolom CSV
            fputcsv($file, ['No', 'Tanggal Pengajuan', 'Nama Nasabah', 'Metode Pencairan', 'Jumlah Penarikan', 'Status']);

            foreach ($data as $index => $item) {
                fputcsv($file, [
                    $index + 1,
                    $item->created_at,
                    $item->user->nasabah->nama_lengkap ?? $item->user->name ?? '-',
                    $item->metode_pencairan,
                    $item->jumlah_penarikan,
                    $item->status
                ]);
            }
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}