<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
</head>
<body class="bg-white text-slate-800 font-sans overflow-x-hidden">
    
    {{-- NAVBAR --}}
    <header class="sticky top-0 z-50 bg-white/90 backdrop-blur border-b border-slate-200 shadow-sm">
        <div class="max-w-7xl mx-auto px-6 lg:px-10">
            <div class="flex items-center justify-between h-20">

                {{-- LOGO --}}
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-xl bg-red-600 flex items-center justify-center shadow-lg shadow-red-200">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-7 h-7 text-white">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21l-4.5-2.598V5.598L8.25 3m0 18l4.5-2.598m-4.5 2.598V3m4.5 15.402L17.25 21m0 0l4.5-2.598V5.598L17.25 3m0 18V3m-4.5 15.402V5.598" />
                        </svg>
                    </div>

                    <div>
                        <h1 class="text-xl font-bold text-slate-800">
                            Monitoring Santri
                        </h1>
                        <p class="text-sm text-slate-500">
                            Sistem Informasi Pondok Pesantren
                        </p>
                    </div>
                </div>

                {{-- MENU --}}
                <nav class="hidden md:flex items-center gap-8 font-medium text-slate-600">
                    <a href="#" class="hover:text-blue-700 transition">Beranda</a>
                    <a href="#" class="hover:text-blue-700 transition">Tentang</a>
                    <a href="#" class="hover:text-blue-700 transition">Fitur</a>
                    <a href="#" class="hover:text-blue-700 transition">Kontak</a>
                </nav>

                {{-- BUTTON --}}
                <div class="flex items-center gap-3">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}"
                               class="px-5 py-2.5 rounded-xl bg-blue-700 text-white font-semibold shadow-lg hover:bg-blue-800 transition duration-300">
                                Dashboard
                            </a>
                        @else
                            <a href="{{ route('login') }}"
                               class="px-5 py-2.5 rounded-xl border border-slate-300 hover:border-blue-600 hover:text-blue-700 font-medium transition duration-300">
                                Login
                            </a>

                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                   class="px-5 py-2.5 rounded-xl bg-blue-700 text-white font-semibold shadow-lg hover:bg-blue-800 transition duration-300">
                                    Register
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </header>

    {{-- HERO SECTION --}}
    <section class="relative overflow-hidden">

        {{-- OVERLAY BACKGROUND --}}
        <div class="absolute inset-0 bg-gradient-to-br from-blue-50 via-white to-sky-100"></div>

        <div class="absolute top-0 left-0 w-96 h-96 bg-blue-200 rounded-full blur-3xl opacity-30"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-sky-300 rounded-full blur-3xl opacity-30"></div>

        <div class="relative max-w-7xl mx-auto px-6 lg:px-10 py-24 lg:py-32">
            <div class="grid lg:grid-cols-2 gap-14 items-center">

                {{-- LEFT CONTENT --}}
                <div>
                    <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-blue-100 text-blue-700 font-semibold text-sm mb-6">
                        🚀 Laravel Gratis & Modern
                    </div>

                    <h1 class="text-5xl lg:text-6xl font-bold leading-tight text-slate-900 mb-6">
                        Sistem Monitoring
                        <span class="text-blue-700">Santri & Santriwati</span>
                    </h1>

                    <p class="text-lg text-slate-600 leading-relaxed mb-8 max-w-xl">
                        Website modern untuk memantau perkembangan santri,
                        absensi, hafalan, kedisiplinan, dan laporan pondok
                        pesantren secara realtime.
                    </p>

                    <div class="flex flex-wrap gap-4">
                        <a href="#"
                           class="px-7 py-4 rounded-2xl bg-blue-700 text-white font-semibold shadow-xl shadow-blue-200 hover:bg-blue-800 transition duration-300">
                            Mulai Sekarang
                        </a>

                        <a href="#"
                           class="px-7 py-4 rounded-2xl border border-slate-300 bg-white hover:border-blue-600 hover:text-blue-700 font-semibold transition duration-300">
                            Pelajari Fitur
                        </a>
                    </div>

                    {{-- STATS --}}
                    <div class="grid grid-cols-3 gap-6 mt-12">
                        <div>
                            <h2 class="text-3xl font-bold text-blue-700">100%</h2>
                            <p class="text-slate-500 text-sm mt-1">Responsive</p>
                        </div>

                        <div>
                            <h2 class="text-3xl font-bold text-blue-700">24/7</h2>
                            <p class="text-slate-500 text-sm mt-1">Monitoring</p>
                        </div>

                        <div>
                            <h2 class="text-3xl font-bold text-blue-700">Gratis</h2>
                            <p class="text-slate-500 text-sm mt-1">Laravel</p>
                        </div>
                    </div>
                </div>

                {{-- RIGHT CONTENT --}}
                <div class="relative">

                    {{-- CARD OVERLAY --}}
                    <div class="absolute -top-6 -left-6 w-full h-full bg-blue-200 rounded-[2rem] blur-2xl opacity-30"></div>

                    <div class="relative bg-white rounded-[2rem] p-8 shadow-2xl border border-slate-100">

                        {{-- HEADER CARD --}}
                        <div class="flex items-center justify-between mb-8">
                            <div>
                                <h3 class="text-2xl font-bold text-slate-800">
                                    Dashboard Pondok
                                </h3>
                                <p class="text-slate-500 mt-1">
                                    Monitoring realtime santri
                                </p>
                            </div>

                            <div class="w-16 h-16 rounded-2xl bg-red-600 flex items-center justify-center shadow-lg shadow-red-200">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-9 h-9 text-white">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 21l-4.5-2.598V5.598L8.25 3m0 18l4.5-2.598m-4.5 2.598V3m4.5 15.402L17.25 21m0 0l4.5-2.598V5.598L17.25 3m0 18V3m-4.5 15.402V5.598" />
                                </svg>
                            </div>
                        </div>

                        {{-- FEATURE BOX --}}
                        <div class="space-y-5">
                            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                                <div>
                                    <h4 class="font-bold text-slate-800">Absensi Santri</h4>
                                    <p class="text-sm text-slate-500 mt-1">Pemantauan harian</p>
                                </div>
                                <span class="px-4 py-2 rounded-xl bg-green-100 text-green-700 text-sm font-semibold">
                                    Aktif
                                </span>
                            </div>

                            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                                <div>
                                    <h4 class="font-bold text-slate-800">Laporan Hafalan</h4>
                                    <p class="text-sm text-slate-500 mt-1">Progress mingguan</p>
                                </div>
                                <span class="px-4 py-2 rounded-xl bg-blue-100 text-blue-700 text-sm font-semibold">
                                    Online
                                </span>
                            </div>

                            <div class="p-5 rounded-2xl bg-slate-50 border border-slate-100 flex items-center justify-between">
                                <div>
                                    <h4 class="font-bold text-slate-800">Kedisiplinan</h4>
                                    <p class="text-sm text-slate-500 mt-1">Laporan pembinaan</p>
                                </div>
                                <span class="px-4 py-2 rounded-xl bg-yellow-100 text-yellow-700 text-sm font-semibold">
                                    Monitoring
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- FOOTER --}}
    <footer class="border-t border-slate-200 bg-white py-8">
        <div class="max-w-7xl mx-auto px-6 lg:px-10 flex flex-col lg:flex-row items-center justify-between gap-4">

            <div>
                <h3 class="font-bold text-slate-800">
                    Monitoring Santri Laravel
                </h3>
                <p class="text-slate-500 text-sm mt-1">
                    © {{ date('Y') }} All Rights Reserved
                </p>
            </div>

            <div class="flex items-center gap-4 text-sm text-slate-500">
                <a href="#" class="hover:text-blue-700">Privacy Policy</a>
                <a href="#" class="hover:text-blue-700">Terms</a>
                <a href="#" class="hover:text-blue-700">Support</a>
            </div>
        </div>
    </footer>

</body>
</html>
