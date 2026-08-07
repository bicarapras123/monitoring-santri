<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Penarikan;

class PenarikanController extends Controller
{
    public function index()
    {
        // Hapus 'rekeningNasabah' dari sini karena data e-wallet sudah langsung tersimpan di tabel penarikans
        $penarikans = Penarikan::with(['user'])->latest()->get(); 

        return view('admin.transaksi.penarikan', compact('penarikans'));
    }

    public function uploadBukti(Request $request, $id)
    {
        $request->validate([
            'bukti_pencairan' => 'required|image|mimes:jpeg,png,jpg,pdf|max:2048',
        ]);

        $penarikan = Penarikan::findOrFail($id);

        if ($request->hasFile('bukti_pencairan')) {
            $file = $request->file('bukti_pencairan');
            $filename = time() . '_' . $file->getClientOriginalName();
            
            $file->move(public_path('uploads/bukti_pencairan'), $filename);
            
            $penarikan->bukti_pencairan = $filename;
            $penarikan->save();
        }

        return redirect()->back()->with('success', 'Bukti pencairan berhasil diupload.');
    }
    public function updateStatus(Request $request, $id)
    {
    $request->validate([
        'status' => 'required|in:pending,disetujui,ditolak',
    ]);

    $penarikan = Penarikan::findOrFail($id);
    $penarikan->status = $request->status;
    $penarikan->save();

    return redirect()->back()->with('success', 'Status penarikan berhasil diperbarui.');
    }
}