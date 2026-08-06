<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JenisSampahController;
use App\Http\Controllers\InformationController;
use App\Http\Controllers\NasabahController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\RekeningNasabahController;
use App\Http\Controllers\AdminNasabahController;
use App\Models\JenisSampah;
use Illuminate\Http\Request;

// 1. Halaman Utama (/) menggunakan view 'welcome'
Route::get('/', function (Request $request) {
    $query = JenisSampah::query();
    if ($request->has('search') && $request->search != '') {
        $query->where('nama_sampah', 'like', '%' . $request->search . '%');
    }
    if ($request->has('kategori') && $request->kategori != 'Semua' && $request->kategori != '') {
        $query->where('kategori', $request->kategori);
    }
    $jenisSampahs = $query->latest()->get();
    $kategoriAktif = $request->get('kategori', 'Semua');
    $keyword = $request->get('search', '');

    return view('welcome', compact('jenisSampahs', 'kategoriAktif', 'keyword'));
});

// 2. Halaman Informasi menggunakan InformationController & view 'information'
Route::get('/information', [InformationController::class, 'index'])->name('information');

// 3. Halaman Dashboard (Dipindahkan ke DashboardController)
Route::get('/dashboard', [DashboardController::class, 'index'])
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

// 4. Route yang memerlukan autentikasi login
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::post('/nasabah', [NasabahController::class, 'store'])->name('nasabah.store');
    Route::post('/rekening', [RekeningNasabahController::class, 'store'])->name('rekening.store');
    Route::get('/rekening', [RekeningNasabahController::class, 'create'])->name('rekening.store');
    Route::get('/admin/nasabah', [AdminNasabahController::class, 'index'])->name('admin.nasabah.index');
    Route::get('/admin/nasabah/rekening', [AdminNasabahController::class, 'rekeningAjuan'])->name('admin.nasabah.rekening');
    // TAMBAHKAN ROUTE VERIFIKASI INI:
    Route::patch('/admin/nasabah/rekening/{id}/verify', [AdminNasabahController::class, 'verifyRekening'])->name('admin.nasabah.verify');

    Route::resource('jenis-sampah', JenisSampahController::class);
});

require __DIR__.'/auth.php';