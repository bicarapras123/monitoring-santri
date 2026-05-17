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
                        Monitoring
                    </h1>
                    <p class="text-xs text-gray-500">
                        Sistem Santri
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
                    @if(Auth::user()->role == 'pengelola')
                        bg-indigo-100 text-indigo-700
                    @elseif(Auth::user()->role == 'pengajar')
                        bg-emerald-100 text-emerald-700
                    @else
                        bg-orange-100 text-orange-700
                    @endif">

                    @if(Auth::user()->role == 'pengelola')
                        Pengelola
                    @elseif(Auth::user()->role == 'pengajar')
                        Pengajar
                    @else
                        Orang Tua
                    @endif

                </div>
            </div>
        </div>

        <!-- Menu -->
        <div class="flex-1 overflow-y-auto px-4 py-6">
            <p class="text-xs font-semibold text-gray-400 uppercase tracking-wider mb-3 px-3">
                Menu Utama
            </p>

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

            <!-- Tambah Menu -->
            <div class="mt-2">
                <a href="#"
                   class="flex items-center gap-3 px-4 py-3 rounded-2xl text-gray-700 hover:bg-gray-100 transition">

                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="h-5 w-5"
                        fill="none"
                        viewBox="0 0 24 24"
                        stroke="currentColor">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M9 17v-2a4 4 0 014-4h4"/>
                    </svg>

                    <span>Data Santri</span>
                </a>
            </div>
        </div>

        <!-- Bottom -->
        <div class="p-4 border-t border-gray-100">
            <a href="{{ route('profile.edit') }}"
               class="flex items-center gap-3 px-4 py-3 rounded-xl text-gray-700 hover:bg-gray-100 transition">
                
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

            <form method="POST" action="{{ route('logout') }}" class="mt-2">
                @csrf

                <button type="submit"
                    class="w-full flex items-center gap-3 px-4 py-3 rounded-xl text-red-600 hover:bg-red-50 transition">

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

    <!-- Mobile -->
    <div class="flex-1 flex flex-col overflow-hidden">

        <!-- Topbar -->
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

            <h1 class="font-bold text-gray-800">
                Dashboard
            </h1>

            <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center font-bold text-indigo-700">
                {{ strtoupper(substr(Auth::user()->name,0,1)) }}
            </div>
        </header>

        <!-- Mobile Sidebar -->
        <div x-show="open"
            x-transition
            class="fixed inset-0 z-50 md:hidden">

            <div class="absolute inset-0 bg-black/40"
                @click="open = false"></div>

            <aside class="relative w-72 h-full bg-white shadow-xl flex flex-col">

                <div class="p-6 border-b">
                    <h2 class="text-xl font-bold text-gray-800">
                        Monitoring Santri
                    </h2>

                    <p class="text-sm text-gray-500">
                        Sistem Dashboard
                    </p>
                </div>

                <div class="p-4 space-y-2">

                    <a href="{{ route('dashboard') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl bg-indigo-600 text-white">

                        Dashboard
                    </a>

                    <a href="#"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-100 text-gray-700">

                        Data Santri
                    </a>

                    <a href="{{ route('profile.edit') }}"
                        class="flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-gray-100 text-gray-700">

                        Profile
                    </a>

                    <form method="POST" action="{{ route('logout') }}">
                        @csrf

                        <button type="submit"
                            class="w-full text-left flex items-center gap-3 px-4 py-3 rounded-xl hover:bg-red-50 text-red-600">

                            Logout
                        </button>
                    </form>

                </div>
            </aside>
        </div>

        <!-- Content -->
        <main class="flex-1 overflow-y-auto"></main>