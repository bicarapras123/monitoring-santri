<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\JenisSampahController;
use App\Http\Controllers\InformationController;
use App\Models\JenisSampah; // Jangan lupa use Model jika memakai cara closure
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

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    
    Route::resource('jenis-sampah', JenisSampahController::class);
});

require __DIR__.'/auth.php';