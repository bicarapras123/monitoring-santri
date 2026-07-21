<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body class="bg-white text-slate-800 font-sans overflow-x-hidden">

<x-navbar />

{{-- SECTION: KATALOG & PENCARIAN JENIS SAMPAH DARI DATABASE --}}
<section id="katalog-sampah" class="py-20 bg-slate-50 border-t border-slate-100">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        
        <div class="text-center mb-12">
            <span class="text-emerald-600 font-bold tracking-wider text-xs uppercase">Katalog Sampah Terkumpul</span>
            <h2 class="text-4xl lg:text-5xl font-bold text-slate-900 mt-2 tracking-tight">Galeri Jenis Sampah.</h2>
            <p class="text-slate-500 text-sm mt-2 max-w-lg mx-auto">Lihat daftar sampah yang dapat Anda tabung bersama kami untuk mendukung lingkungan yang lebih bersih.</p>
        </div>

        {{-- Form Pencarian & Kategori --}}
        <div class="max-w-3xl mx-auto mb-12">
            <form action="{{ route('information') }}" method="GET">
                <!-- Bar Pencarian -->
                <div class="bg-white p-2 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-2 mb-4">
                    <input type="text" name="search" value="{{ $keyword ?? '' }}" placeholder="Cari nama sampah (misal: Kardus, Botol)..."
                        class="w-full px-4 py-2.5 text-xs md:text-sm border-none focus:outline-none focus:ring-0 text-slate-700 bg-transparent">
                    
                    <!-- Simpan state kategori saat sedang search -->
                    <input type="hidden" name="kategori" value="{{ $kategoriAktif ?? 'Semua' }}">

                    <button type="submit" class="bg-emerald-600 hover:bg-emerald-700 text-white px-6 py-2.5 rounded-xl font-medium text-xs md:text-sm flex items-center gap-2 transition shadow-sm shrink-0">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Cari
                    </button>
                </div>

                <!-- Tombol Kategori / Filter Dinamis dari Database -->
                <div class="flex items-center justify-center flex-wrap gap-2">
                    @php
                        $currentKat = $kategoriAktif ?? 'Semua';
                    @endphp

                    @foreach($listKategori as $kat)
                        <a href="{{ route('information', ['kategori' => $kat, 'search' => ($keyword ?? '')]) }}"
                            class="px-5 py-2 rounded-xl text-xs font-semibold transition border {{ ($currentKat == $kat) ? 'bg-emerald-600 text-white border-emerald-600 shadow-sm' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-100' }}">
                            {{ $kat }}
                        </a>
                    @endforeach
                </div>
            </form>
        </div>

        {{-- Grid Kartu Katalog dari Database --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($jenisSampahs as $item)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition flex flex-col">
                    <!-- Gambar Sampah -->
                    <div class="h-44 w-full bg-slate-100 relative overflow-hidden">
                        @if($item->upload_image)
                            <img src="{{ asset('storage/' . $item->upload_image) }}" alt="{{ $item->nama_sampah }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400 text-xs italic">
                                Tidak ada gambar
                            </div>
                        @endif
                        <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-slate-800 text-[10px] font-bold px-2.5 py-1 rounded-lg shadow-sm">
                            {{ $item->kategori }}
                        </span>
                    </div>

                    <!-- Informasi Detail -->
                    <div class="p-4 flex flex-col flex-1 justify-between">
                        <div>
                            <h3 class="font-bold text-slate-900 text-sm mb-1">{{ $item->nama_sampah }}</h3>
                            <p class="text-slate-400 text-xs line-clamp-2 mb-3">{{ $item->deskripsi ?? 'Tidak ada deskripsi tersedia.' }}</p>
                        </div>
                        <div class="pt-3 border-t border-slate-50 flex items-center justify-between">
                            <span class="text-[11px] text-slate-400 font-medium">Harga / Kg</span>
                            <span class="font-extrabold text-emerald-600 text-xs md:text-sm">Rp {{ number_format($item->harga_kg, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center">
                    <p class="text-slate-400 text-sm">Belum ada jenis sampah yang ditemukan pada kategori atau pencarian ini.</p>
                </div>
            @endforelse
        </div>

    </div>
</section>

<x-footer />

<script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
<script>
    AOS.init({
        duration: 1000,
        once: true
    });
</script>

</body>
</html>