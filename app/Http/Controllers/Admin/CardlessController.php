<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Smalot\PdfParser\Parser;
use App\Models\Cardless;
use App\Models\Nasabah;
use App\Models\Penarikan;
use App\Models\User; // <-- Tambahkan baris ini

class CardlessController extends Controller
{
    public function index()
    {
        $cardlessList = Cardless::latest()->get();
        return view('admin.transaksi.cardless', compact('cardlessList'));
    }

    public function parsePdf(Request $request)
    {
        $request->validate([
            'form_pdf' => 'required|mimes:pdf|max:2048',
            'jumlah_cardless' => 'required|numeric|min:1000',
        ]);

        try {
            $file = $request->file('form_pdf');
            $filePath = $file->store('cardless_forms', 'public');
            
            // Parse PDF
            $parser = new Parser();
            $pdf = $parser->parseFile($file->path());
            $text = $pdf->getText();

            $nik = '-';
            $namaLengkap = '-';
            $jenisKelamin = '-';
            $tempatTglLahir = '-';
            $nomorTelepon = '-';
            $namaOrangTua = '-';
            $alamatLengkap = '-';

            if (preg_match('/\b\d{16}\b/', $text, $matchNik)) {
                $nik = $matchNik[0];
            }
            if (preg_match('/(\+62|08)\d{8,11}/', $text, $matchPhone)) {
                $nomorTelepon = $matchPhone[0];
            }

            $lines = array_filter(explode("\n", $text), 'trim');
            
            foreach ($lines as $line) {
                if (stripos($line, 'Nama Lengkap') !== false) {
                    $parts = explode(':', $line);
                    if (isset($parts[1])) $namaLengkap = trim($parts[1]);
                }
                elseif (stripos($line, 'Jenis Kelamin') !== false) {
                    $parts = explode(':', $line);
                    if (isset($parts[1])) $jenisKelamin = trim($parts[1]);
                }
                elseif (stripos($line, 'Tempat, Tgl Lahir') !== false) {
                    $parts = explode(':', $line);
                    if (isset($parts[1])) $tempatTglLahir = trim($parts[1]);
                }
                elseif (stripos($line, 'Nama Orang Tua') !== false) {
                    $parts = explode(':', $line);
                    if (isset($parts[1])) $namaOrangTua = trim($parts[1]);
                }
                elseif (stripos($line, 'Alamat Lengkap') !== false) {
                    $parts = explode(':', $line);
                    if (isset($parts[1])) $alamatLengkap = trim($parts[1]);
                }
            }

            // 1. Membaca nama nasabah dari tabel nasabahs
            $nasabah = Nasabah::where('nama_lengkap', 'like', '%' . trim($namaLengkap) . '%')->first();

            if (!$nasabah) {
                return back()->withErrors(['form_pdf' => 'Nasabah dengan nama "' . $namaLengkap . '" tidak ditemukan di tabel nasabahs!']);
            }

            $jumlahCardless = $request->input('jumlah_cardless');
            $userId = $nasabah->user_id;

            // Mulai database transaction
            DB::beginTransaction();

            // Simpan data diri ke tabel cardless (beserta jumlah cardless-nya)
            Cardless::create([
                'nik' => $nik,
                'nama_lengkap' => $nasabah->nama_lengkap,
                'jumlah_cardless' => $jumlahCardless,
                'jenis_kelamin' => $jenisKelamin,
                'tempat_tgl_lahir' => $tempatTglLahir,
                'nomor_telepon' => $nomorTelepon,
                'nama_orang_tua' => $namaOrangTua,
                'alamat_lengkap' => $alamatLengkap,
                'raw_text' => $text,
                'file_path' => $filePath,
            ]);

            // 2. Catat nominal langsung ke tabel penarikans pada kolom jumlah_penarikan
            Penarikan::create([
                'user_id' => $userId,
                'nama_lengkap' => $nasabah->nama_lengkap,
                'alamat_lengkap' => $alamatLengkap !== '-' ? $alamatLengkap : ($nasabah->alamat_lengkap ?? '-'),
                'nomor_telephone' => $nomorTelepon,
                'nomor_rekening' => $nomorTelepon !== '-' ? $nomorTelepon : '0000000000',
                'jenis_rekening' => 'Cardless Cash',
                'metode_pencairan' => 'Cash',
                'bukti_pdf' => $filePath,
                'jumlah_penarikan' => $jumlahCardless,
                'status' => 'disetujui',
            ]);

            DB::commit();

            return back()->with('success', 'Pengajuan cardless berhasil! Data dan jumlah penarikan telah otomatis tercatat.');

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['form_pdf' => 'Gagal memproses formulir: ' . $e->getMessage()]);
        }
    }
}