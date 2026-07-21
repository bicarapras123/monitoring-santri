<header x-data="{ open: false }" class="sticky top-0 z-50 bg-white/90 backdrop-blur border-b border-slate-100">
    <div class="max-w-7xl mx-auto px-6 lg:px-10">
        <div class="flex items-center justify-between h-20">
            
            {{-- LOGO --}}
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 overflow-hidden">
                    <img src="{{ asset('images/logobsr.png') }}" alt="Logo" class="w-full h-full object-contain">
                </div>
                <div>
                    <h1 class="text-base font-bold text-slate-900 tracking-tight leading-tight">BANK SAMPAH RW.04</h1>
                    <p class="text-[10px] text-slate-500 font-medium">SIM Berbasis Website</p>
                </div>
            </div>

            {{-- MENU (Desktop: Tampil, Mobile: Sembunyi) --}}
            <nav class="hidden md:flex items-center bg-slate-100 p-1 rounded-full border border-slate-200">
                <a href="{{ url('/') }}" class="px-5 py-1.5 rounded-full {{ request()->is('/') ? 'bg-white text-emerald-600 font-semibold shadow-sm' : 'text-slate-600 hover:text-slate-900 font-medium' }} text-sm transition">Beranda</a>
                <a href="{{ route('information') }}" class="px-5 py-1.5 rounded-full {{ request()->routeIs('information') ? 'bg-white text-emerald-600 font-semibold shadow-sm' : 'text-slate-600 hover:text-slate-900 font-medium' }} text-sm transition">Informasi</a>
                <a href="#" class="px-5 py-1.5 rounded-full text-slate-600 hover:text-slate-900 font-medium text-sm transition">Penjualan</a>
                <a href="#" class="px-5 py-1.5 rounded-full text-slate-600 hover:text-slate-900 font-medium text-sm transition">Berita</a>
            </nav>

            {{-- BUTTON & HAMBURGER (Desktop: Button, Mobile: Hamburger) --}}
            <div class="flex items-center gap-4">
                <div class="hidden md:flex items-center gap-3">
                    @auth
                        <a href="{{ url('/dashboard') }}" class="px-5 py-2.5 rounded-full bg-emerald-600 text-white text-sm font-semibold hover:bg-emerald-700 transition">Dashboard</a>
                    @else
                        <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-900">Login</a>
                        <a href="{{ route('register') }}" class="px-5 py-2.5 rounded-full bg-slate-900 text-white text-sm font-semibold hover:bg-slate-800 transition">Register</a>
                    @endauth
                </div>

                {{-- Hamburger Button --}}
                <button @click="open = !open" class="md:hidden p-2 text-slate-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/></svg>
                </button>
            </div>
        </div>
    </div>

    {{-- MOBILE MENU DROPDOWN (Alpine.js) --}}
    <div x-show="open" class="md:hidden bg-white border-b border-slate-100 px-6 py-4 space-y-3">
        <a href="{{ url('/') }}" class="block {{ request()->is('/') ? 'text-emerald-600 font-bold' : 'text-slate-700' }} font-medium">Beranda</a>
        <a href="{{ route('information') }}" class="block {{ request()->routeIs('information') ? 'text-emerald-600 font-bold' : 'text-slate-700' }} font-medium">Informasi</a>
        <a href="#" class="block text-slate-700 font-medium">Penjualan</a>
        <a href="#" class="block text-slate-700 font-medium">Berita</a>
        <hr>
        <div class="flex flex-col gap-2">
            @auth
                <a href="{{ url('/dashboard') }}" class="text-emerald-600 font-semibold">Dashboard</a>
            @else
                <a href="{{ route('login') }}" class="text-slate-600 font-semibold">Login</a>
                <a href="{{ route('register') }}" class="text-slate-900 font-semibold">Register</a>
            @endauth
        </div>
    </div>
</header>