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

 {{-- HERO SECTION --}}
<section class="relative overflow-hidden bg-white">
    {{-- Background Blur (Dikecilkan agar tidak terlalu mendominasi) --}}
    <div class="absolute top-0 right-0 w-80 h-80 bg-emerald-50 rounded-full blur-3xl opacity-60 -mr-10 -mt-10"></div>

    <div class="relative max-w-6xl mx-auto px-6 py-12 lg:py-20">
        <div class="grid lg:grid-cols-12 gap-10 items-center">
            
            {{-- LEFT CONTENT (Diberi porsi 7/12) --}}
            <div class="lg:col-span-7">
                <div class="inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-emerald-50 text-emerald-700 font-medium text-xs mb-5 border border-emerald-100">
                    <span class="relative flex h-2 w-2">
                        <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-emerald-400 opacity-75"></span>
                        <span class="relative inline-flex rounded-full h-2 w-2 bg-emerald-500"></span>
                    </span>
                    "Halo Nasabah Bank Sampah Rakyat", Warga RW 04
                </div>

                <h1 class="text-4xl lg:text-5xl font-bold leading-tight text-slate-900 mb-4 tracking-tight">
                    Ubah Sampah <span class="text-emerald-600">Jadi Kebaikan.</span>
                </h1>

                <p class="text-base text-slate-600 leading-relaxed mb-8 max-w-md">
                    Membangun ekosistem lingkungan mandiri bersama Bank Sampah Rakyat RW 04. Kelola sampah Anda dengan cerdas dan transparan.
                </p>

                <div class="flex flex-wrap gap-3">
                    <a href="#" class="px-6 py-3 rounded-full bg-emerald-600 text-white font-semibold text-sm hover:bg-emerald-700 transition">Daftar Sekarang →</a>
                    <a href="#" class="px-6 py-3 rounded-full border border-slate-200 text-slate-700 font-semibold text-sm hover:border-emerald-600 hover:text-emerald-700 transition">Pelajari Alur</a>
                </div>
            </div>

            {{-- RIGHT CONTENT (Diberi porsi 5/12) --}}
            <div class="lg:col-span-5 relative">
                <div class="bg-white p-4 rounded-2xl shadow-xl shadow-emerald-100 border border-slate-100">
                    <img src="{{ asset('images/logobsr.png') }}" alt="Bank Sampah" class="w-full h-auto rounded-xl mb-4">
                    
                    {{-- Info Box Dibuat lebih ringkas --}}
                    <div class="grid grid-cols-2 gap-3">
                        <div class="flex items-center gap-2 p-3 rounded-xl bg-emerald-50">
                            <div class="p-1.5 bg-white rounded-md text-emerald-600">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path d="M4 4a2 2 0 012-2h8a2 2 0 012 2v12a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 2v1h8V6H6zm0 2v1h8V8H6zm0 2v1h8v-1H6zm0 2v1h8v-1H6z"/></svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-emerald-800 font-bold">Ekonomis</p>
                                <p class="text-[9px] text-emerald-600">Jadi Rupiah</p>
                            </div>
                        </div>
                        <div class="flex items-center gap-2 p-3 rounded-xl bg-emerald-50">
                            <div class="p-1.5 bg-white rounded-md text-emerald-600">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-11a1 1 0 10-2 0v2H7a1 1 0 100 2h2v2a1 1 0 102 0v-2h2a1 1 0 100-2h-2V7z" clip-rule="evenodd"/></svg>
                            </div>
                            <div>
                                <p class="text-[10px] text-emerald-800 font-bold">Lingkungan</p>
                                <p class="text-[9px] text-emerald-600">Lebih Asri</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- TENTANG PROGRAM SECTION --}}
<section class="py-16 lg:py-24 bg-white">
    <div class="max-w-6xl mx-auto px-2">
        <div class="grid lg:grid-cols-2 gap-12 lg:gap-20 items-center">
            
            {{-- LEFT CONTENT --}}
            <div>
                <span class="text-emerald-600 font-bold tracking-wider text-sm uppercase">Tentang Program</span>
                <h2 class="text-4xl lg:text-5xl font-bold text-slate-900 mt-3 mb-6 leading-tight">
                    Mengapa Harus Bank Sampah?
                </h2>
                <p class="text-slate-600 mb-8 leading-relaxed">
                    Kami hadir bukan hanya sebagai tempat penampungan, tapi sebagai solusi edukasi dan ekonomi sirkular bagi masyarakat.
                </p>
                <a href="#" class="inline-flex items-center gap-2 px-6 py-3 rounded-full border border-slate-200 text-slate-700 font-semibold hover:border-emerald-600 hover:text-emerald-600 transition">
                    Pelajari Selengkapnya
                </a>
            </div>

            {{-- RIGHT CONTENT (CARDS) --}}
            <div class="grid md:grid-cols-2 gap-6">
                {{-- Card 1 --}}
                <div class="p-8 rounded-3xl bg-emerald-50 border border-emerald-100">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-emerald-600 mb-6 shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Apa itu Bank Sampah?</h3>
                    <p class="text-sm text-slate-600 leading-relaxed">Sistem manajemen sampah kolektif yang mendorong masyarakat untuk memilah sampah dari rumah dan menukarnya dengan tabungan.</p>
                </div>

                {{-- Card 2 --}}
                <div class="p-8 rounded-3xl bg-sky-50 border border-sky-100">
                    <div class="w-12 h-12 bg-white rounded-xl flex items-center justify-center text-sky-600 mb-6 shadow-sm">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c1.657 0 3 .895 3 2s-1.343 2-3 2m0 0c-1.657 0-3-.895-3-2s1.343-2 3-2zm0 0c1.657 0 3 1.343 3 3v4.5c0 .829-.672 1.5-1.5 1.5H10.5c-.829 0-1.5-.672-1.5-1.5V11c0-1.657 1.343-3 3-3z"/></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Manfaat Nyata</h3>
                    <ul class="space-y-3">
                        <li class="flex items-center gap-2 text-sm text-slate-600">
                            <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Lingkungan Bersih
                        </li>
                        <li class="flex items-center gap-2 text-sm text-slate-600">
                            <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Nilai Ekonomis
                        </li>
                        <li class="flex items-center gap-2 text-sm text-slate-600">
                            <svg class="w-5 h-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/></svg>
                            Edukasi Keluarga
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- SECTION: ALUR MENABUNG (Tampilan Premium) --}}
<section class="py-20 bg-slate-50">
    <div class="max-w-7xl mx-auto px-6 lg:px-10 text-center">
        <span class="text-emerald-600 font-bold tracking-wider text-xs uppercase">Mudah & Cepat</span>
        <h2 class="text-4xl lg:text-5xl font-bold text-slate-900 mt-3 mb-16 tracking-tight">Alur Menabung Sampah</h2>

        <div class="grid md:grid-cols-4 gap-8 relative">
            {{-- Garis Penghubung --}}
            <div class="hidden md:block absolute top-16 left-24 right-24 h-0.5 bg-emerald-100 -z-0"></div>

            {{-- Langkah 1: Registrasi --}}
            <div data-aos="fade-up" data-aos-delay="100" class="relative z-10 flex flex-col items-center group">
                <div class="w-24 h-24 bg-white border-4 border-slate-50 rounded-full flex items-center justify-center text-emerald-600 mb-6 shadow-xl shadow-emerald-50 transition-transform group-hover:scale-105">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Registrasi</h3>
                <p class="text-sm text-slate-500 max-w-[180px]">Daftar akun nasabah melalui website atau admin.</p>
            </div>

            {{-- Langkah 2: Pilah Sampah --}}
            <div data-aos="fade-up" data-aos-delay="200" class="relative z-10 flex flex-col items-center group">
                <div class="w-24 h-24 bg-white border-4 border-slate-50 rounded-full flex items-center justify-center text-emerald-600 mb-6 shadow-xl shadow-emerald-50 transition-transform group-hover:scale-105">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Pilah Sampah</h3>
                <p class="text-sm text-slate-500 max-w-[180px]">Pisahkan sampah organik dan anorganik dari rumah.</p>
            </div>

            {{-- Langkah 3: Setor & Timbang --}}
            <div data-aos="fade-up" data-aos-delay="300" class="relative z-10 flex flex-col items-center group">
                <div class="w-24 h-24 bg-white border-4 border-slate-50 rounded-full flex items-center justify-center text-emerald-600 mb-6 shadow-xl shadow-emerald-50 transition-transform group-hover:scale-105">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l3-9a.5.5 0 011 0l3 9m-6 0l3 9a.5.5 0 001 0l3-9M3 6l3-1m0 0l3 9m-6 0h12"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Setor & Timbang</h3>
                <p class="text-sm text-slate-500 max-w-[180px]">Bawa ke bank sampah untuk ditimbang petugas.</p>
            </div>

            {{-- Langkah 4: Terima Saldo --}}
            <div data-aos="fade-up" data-aos-delay="400" class="relative z-10 flex flex-col items-center group">
                <div class="w-24 h-24 bg-white border-4 border-slate-50 rounded-full flex items-center justify-center text-emerald-600 mb-6 shadow-xl shadow-emerald-50 transition-transform group-hover:scale-105">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2m0 0c1.657 0 3 .895 3 2s-1.343 2-3 2m-6 3h12M7 10h1m12 0h1M7 14h1m12 0h1M3 3h18M5 3v18M19 3v18"/></svg>
                </div>
                <h3 class="text-lg font-bold text-slate-900 mb-2">Terima Saldo</h3>
                <p class="text-sm text-slate-500 max-w-[180px]">Uang hasil sampah langsung masuk ke saldo akun.</p>
            </div>
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
