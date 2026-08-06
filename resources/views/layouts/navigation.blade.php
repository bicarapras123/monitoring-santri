<nav x-data="{ open: false }" class="flex h-screen bg-gray-100">
    
    <!-- Sidebar Desktop -->
    <aside class="hidden md:flex md:flex-col w-72 bg-white border-r border-gray-200 shadow-sm">
        
        <!-- Logo -->
        <div class="h-20 flex items-center px-6 border-b border-gray-100">
            <a href="{{ route('dashboard') }}" class="flex items-center gap-3">
                <div class="bg-indigo-600 text-white p-2 rounded-xl shadow">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6"
                        fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            stroke-width="2"
                            d="M3 7l9-4 9 4-9 4-9-4zm0 6l9 4 9-4m-9 4v6"/>
                    </svg>
                </div>

                <div>
                    <h1 class="text-lg font-bold text-gray-800">
                        Bank Sampah RW.04
                    </h1>
                    <p class="text-xs text-gray-500">
                        Sistem Informasi Manajemen Penjualan Sampah
                    </p>
                </div>
            </a>
        </div>

        <!-- User -->
        <div class="px-6 py-5 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <div class="h-12 w-12 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-700 font-bold text-lg">
                    {{ strtoupper(substr(Auth::user()->name,0,1)) }}
                </div>

                <div class="overflow-hidden">
                <h2 class="font-semibold text-gray-800 truncate">
                    {{ Auth::user()->name }}
                </h2>

                <p class="text-sm text-gray-500 truncate">
                    {{ Auth::user()->email }}
                </p>

                <div class="mt-2 inline-flex items-center px-3 py-1 rounded-full text-xs font-semibold
                    @if(Auth::user()->role == 'admin')
                        bg-indigo-100 text-indigo-700
                    @elseif(Auth::user()->role == 'rw')
                        bg-emerald-100 text-emerald-700
                    @elseif(Auth::user()->role == 'bank_sampah_induk')
                        bg-purple-100 text-purple-700
                    @else
                        bg-orange-100 text-orange-700
                    @endif">

                    @if(Auth::user()->role == 'admin')
                        Admin
                    @elseif(Auth::user()->role == 'rw')
                        RW
                    @elseif(Auth::user()->role == 'bank_sampah_induk')
                        Bank Sampah Induk
                    @else
                        Nasabah
                    @endif

                </div>
            </div>
        </div>

        <!-- Menu -->
        <div class="flex-1 overflow-y-auto px-4 py-6 space-y-1">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 px-3">
                Menu Utama
            </p>

            <!-- MENU DASHBOARD: HANYA MUNCUL UNTUK NASABAH & ORANG TUA -->
            @if(in_array(Auth::user()->role, ['nasabah', 'orang_tua']))
            <a href="{{ route('dashboard') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl transition-all duration-200
               {{ request()->routeIs('dashboard')
                    ? 'bg-indigo-600 text-white shadow-lg'
                    : 'text-gray-700 hover:bg-gray-100' }}">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m0-8H5m7 0h7"/>
                </svg>

                <span class="font-medium">
                    Dashboard
                </span>
            </a>
            @endif

            <!-- Menu Khusus Admin (Desktop) -->
            @if(Auth::user()->role == 'admin')
            
            <!-- Dropdown Menu Kelola Nasabah -->
            <div x-data="{ nasabahOpen: {{ request()->routeIs('admin.nasabah.*') ? 'true' : 'false' }} }">
                <!-- Tombol Utama Dropdown -->
                <button @click="nasabahOpen = !nasabahOpen" 
                    class="w-full flex items-center justify-between px-4 py-3 rounded-2xl text-gray-700 hover:bg-gray-100 transition {{ request()->routeIs('admin.nasabah.*') ? 'bg-indigo-50/60 text-indigo-700 font-semibold' : '' }}">
                    
                    <div class="flex items-center gap-3">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-5 w-5 flex-shrink-0"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span class="text-xs font-medium whitespace-nowrap">Kelola Data Nasabah</span>
                    </div>

                    <!-- Icon Panah Dropdown -->
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-200" :class="nasabahOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                <!-- Sub-Menu / Isi Dropdown -->
                <div x-show="nasabahOpen" x-transition class="pl-4 pr-2 py-1.5 space-y-1 mt-1 border-l-2 border-indigo-100 ml-4">
                    <a href="{{ route('admin.nasabah.index') }}" 
                        class="block px-3.5 py-2.5 rounded-xl text-xs font-medium transition {{ request()->routeIs('admin.nasabah.index') ? 'text-indigo-700 bg-indigo-50 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Semua Nasabah
                    </a>
                    <a href="{{ route('admin.nasabah.rekening') }}" 
                        class="block px-3.5 py-2.5 rounded-xl text-xs font-medium transition {{ request()->routeIs('admin.nasabah.rekening') ? 'text-indigo-700 bg-indigo-50 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                        Pengajuan Rekening
                    </a>
                </div>
            </div>

            <!-- Menu Kelola Jenis & Harga Sampah -->
            <div>
                 <a href="{{ route('jenis-sampah.index') }}"
                    class="flex items-center gap-3 px-4 py-3 rounded-2xl text-gray-700 hover:bg-gray-100 transition {{ request()->routeIs('jenis-sampah.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : '' }}">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5 flex-shrink-0"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 17v-2a4 4 0 014-4h4"/>
                    </svg>

                    <span class="text-xs font-medium whitespace-nowrap">Kelola Jenis & Harga Sampah</span>
                </a>
            </div>

            <!-- MENU: Kelola Data Setoran Sampah -->
            <div>
                <a href="{{ route('setoran.create') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl text-gray-700 hover:bg-gray-100 transition {{ request()->routeIs('setoran.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : '' }}">

                    <svg xmlns="http://www.w3.org/2000/svg" 
                        class="h-5 w-5 flex-shrink-0" 
                        fill="none" 
                        viewBox="0 0 24 24" 
                        stroke="currentColor">
                        <path stroke-linecap="round" 
                            stroke-linejoin="round" 
                            stroke-width="2" 
                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                    </svg>

                    <span class="text-xs font-medium whitespace-nowrap">Kelola Data Setoran Sampah</span>
                </a>
            </div>

            <!-- MENU: Kelola Transaksi Nasabah (Terhubung ke route admin.transaksi.index) -->
            <div>
                <a href="{{ route('admin.transaksi.index') }}" 
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl text-gray-700 hover:bg-gray-100 transition {{ request()->routeIs('admin.transaksi.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : '' }}">

                    <svg xmlns="http://www.w3.org/2000/svg" 
                        class="h-5 w-5 flex-shrink-0" 
                        fill="none" 
                        viewBox="0 0 24 24" 
                        stroke="currentColor">
                        <path stroke-linecap="round" 
                            stroke-linejoin="round" 
                            stroke-width="2" 
                            d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>

                    <span class="text-xs font-medium whitespace-nowrap">Kelola Transaksi Nasabah</span>
                </a>
            </div>
            @endif
        </div>

        <!-- Bottom -->
        <div class="p-4 border-t border-gray-100 space-y-1">
            <a href="{{ route('profile.edit') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-2xl text-gray-700 hover:bg-gray-100 transition text-xs font-medium">
                
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-5 w-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M5.121 17.804A13.937 13.937 0 0112 16c2.5 0 4.847.655 6.879 1.804M15 10a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>

                <span>Profile</span>
            </a>

            <form method="POST" action="{{ route('logout') }}">
                @csrf

                <button type="submit"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-2xl text-red-600 hover:bg-red-50 transition text-xs font-medium">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M17 16l4-4m0 0l-4-4m4 4H7"/>
                    </svg>

                    <span>Logout</span>
                </button>
            </form>
        </div>
    </aside>

    <!-- Mobile Wrapper -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- Topbar Mobile -->
        <header class="bg-white shadow-sm border-b border-gray-100 h-16 flex items-center justify-between px-5 md:hidden">
            <button @click="open = !open"
                class="p-2 rounded-lg hover:bg-gray-100">
                <svg xmlns="http://www.w3.org/2000/svg"
                    class="h-6 w-6 text-gray-700"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">
                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            <h1 class="font-bold text-gray-800 text-sm">
                Dashboard
            </h1>

            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center font-bold text-indigo-700 text-xs">
                {{ strtoupper(substr(Auth::user()->name,0,1)) }}
            </div>
        </header>

        <!-- Mobile Sidebar Overlay -->
        <div x-show="open"
            x-transition
            class="fixed inset-0 z-50 md:hidden">

            <div class="absolute inset-0 bg-black/40"
                @click="open = false"></div>

            <aside class="relative w-72 h-full bg-white shadow-xl flex flex-col">
                <div class="p-6 border-b">
                    <h2 class="text-xl font-bold text-gray-800">
                        Bank Sampah RW.04
                    </h2>
                    <p class="text-sm text-gray-500">
                        Sistem Dashboard
                    </p>
                </div>

                <div class="p-4 space-y-1.5 flex-1 overflow-y-auto">
                    @if(in_array(Auth::user()->role, ['nasabah', 'orang_tua']))
                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-2xl bg-indigo-600 text-white font-medium text-xs">
                        Dashboard
                    </a>
                    @endif

                    @if(Auth::user()->role == 'admin')
                    <div x-data="{ mobileNasabahOpen: {{ request()->routeIs('admin.nasabah.*') ? 'true' : 'false' }} }">
                        <button @click="mobileNasabahOpen = !mobileNasabahOpen"
                            class="w-full flex items-center justify-between px-4 py-3 rounded-2xl hover:bg-gray-100 text-gray-700 text-xs font-medium">
                            <span>Kelola Data Nasabah</span>
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 transition-transform duration-200" :class="mobileNasabahOpen ? 'rotate-180' : ''" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                            </svg>
                        </button>

                        <div x-show="mobileNasabahOpen" x-transition class="pl-4 py-1 space-y-1 mt-1 border-l-2 border-indigo-100 ml-4">
                            <a href="{{ route('admin.nasabah.index') }}"
                                class="block px-3.5 py-2.5 rounded-xl text-xs font-medium {{ request()->routeIs('admin.nasabah.index') ? 'text-indigo-700 bg-indigo-50 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                                Semua Nasabah
                            </a>
                            <a href="{{ route('admin.nasabah.rekening') }}"
                                class="block px-3.5 py-2.5 rounded-xl text-xs font-medium {{ request()->routeIs('admin.nasabah.rekening') ? 'text-indigo-700 bg-indigo-50 font-semibold' : 'text-gray-600 hover:bg-gray-100' }}">
                                Pengajuan Rekening
                            </a>
                        </div>
                    </div>

                    <a href="{{ route('jenis-sampah.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-gray-100 text-gray-700 text-xs font-medium">
                        Kelola Jenis & Harga Sampah
                    </a>

                    <a href="{{ route('setoran.create') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-gray-100 text-gray-700 text-xs font-medium {{ request()->routeIs('setoran.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : '' }}">
                        Kelola Data Setoran Sampah
                    </a>

                    <a href="{{ route('admin.transaksi.index') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-gray-100 text-gray-700 text-xs font-medium {{ request()->routeIs('admin.transaksi.*') ? 'bg-indigo-50 text-indigo-700 font-semibold' : '' }}">
                        Kelola Transaksi Nasabah
                    </a>
                    @endif

                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-gray-100 text-gray-700 text-xs font-medium">
                        Profile
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit"
                            class="w-full text-left flex items-center gap-3 px-4 py-3 rounded-2xl hover:bg-red-50 text-red-600 text-xs font-medium">
                            Logout
                        </button>
                    </form>
                </div>
            </aside>
        </div>

    </div>
</nav>