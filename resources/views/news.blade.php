<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Berita - Bank Sampah RW.04</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
</head>
<body class="bg-white text-slate-800 font-sans overflow-x-hidden">

<x-navbar />

{{-- SECTION: DAFTAR BERITA & PENCARIAN DARI DATABASE --}}
<section id="halaman-berita" class="py-20 bg-slate-50 border-t border-slate-100 min-h-screen">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        
        <!-- Header Halaman -->
        <div class="text-center mb-12" data-aos="fade-up">
            <span class="text-emerald-600 font-bold tracking-wider text-xs uppercase">Informasi & Kegiatan Terkini</span>
            <h2 class="text-4xl lg:text-5xl font-bold text-slate-900 mt-2 tracking-tight">Berita & Pengumuman</h2>
            <p class="text-slate-500 text-sm mt-2 max-w-lg mx-auto">Ikuti berbagai informasi terbaru seputar kegiatan bank sampah, edukasi lingkungan, dan pengumuman penting.</p>
        </div>

        {{-- Form Pencarian & Kategori --}}
        <div class="max-w-3xl mx-auto mb-12" data-aos="fade-up" data-aos-delay="100">
            <form action="{{ route('news.index') }}" method="GET">
                <!-- Bar Pencarian -->
                <div class="bg-white p-2 rounded-2xl shadow-sm border border-slate-200 flex items-center gap-2 mb-4">
                    <input type="text" name="search" value="{{ $keyword ?? '' }}" placeholder="Cari judul berita atau isi..."
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

                <!-- Tombol Kategori / Filter Dinamis Berita -->
                <div class="flex items-center justify-center flex-wrap gap-2">
                    @php
                        $currentKat = $kategoriAktif ?? 'Semua';
                        $listKategori = ['Semua', 'Pengumuman', 'Kegiatan', 'Edukasi', 'Artikel'];
                    @endphp

                    @foreach($listKategori as $kat)
                        <a href="{{ route('news.index', ['kategori' => $kat, 'search' => ($keyword ?? '')]) }}"
                            class="px-5 py-2 rounded-xl text-xs font-semibold transition border {{ ($currentKat == $kat) ? 'bg-emerald-600 text-white border-emerald-600 shadow-sm' : 'bg-white text-slate-600 border-slate-200 hover:bg-slate-100' }}">
                            {{ $kat }}
                        </a>
                    @endforeach
                </div>
            </form>
        </div>

        {{-- Grid Kartu Berita dari Database --}}
        <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
            @forelse($beritas as $item)
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden hover:shadow-md transition flex flex-col" data-aos="fade-up">
                    <!-- Gambar / Banner Berita -->
                    <div class="h-44 w-full bg-slate-100 relative overflow-hidden">
                        @if($item->upload_image)
                            <img src="{{ asset('storage/' . $item->upload_image) }}" alt="{{ $item->judul }}" class="w-full h-full object-cover">
                        @else
                            <div class="w-full h-full flex items-center justify-center text-slate-400 text-xs italic">
                                Tidak ada gambar
                            </div>
                        @endif
                        <span class="absolute top-3 right-3 bg-white/90 backdrop-blur-sm text-slate-800 text-[10px] font-bold px-2.5 py-1 rounded-lg shadow-sm">
                            {{ $item->kategori }}
                        </span>
                    </div>

                    <!-- Informasi Detail Berita -->
                    <div class="p-4 flex flex-col flex-1 justify-between">
                        <div>
                            <span class="text-[11px] text-slate-400 block mb-1">{{ $item->created_at->format('d M Y') }}</span>
                            <h3 class="font-bold text-slate-900 text-sm mb-1 line-clamp-2">{{ $item->judul }}</h3>
                            <p class="text-slate-400 text-xs line-clamp-3 mb-3">{{ $item->konten }}</p>
                        </div>
                        <div class="pt-3 border-t border-slate-50 flex items-center justify-between">
                            <span class="text-[11px] text-emerald-600 font-semibold hover:underline cursor-pointer">Dipublikasi oleh Ranti Oriza</span>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full py-16 text-center">
                    <p class="text-slate-400 text-sm">Belum ada berita atau informasi yang ditemukan pada kategori atau pencarian ini.</p>
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