<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="min-h-screen bg-gradient-to-br from-slate-50 via-white to-slate-100 flex items-center justify-center p-4 overflow-hidden">

    {{-- Background Blur --}}
    <div class="absolute top-0 left-0 w-72 h-72 bg-red-200 rounded-full blur-3xl opacity-30"></div>
    <div class="absolute bottom-0 right-0 w-72 h-72 bg-slate-300 rounded-full blur-3xl opacity-30"></div>

    {{-- Main Container --}}
    <div class="relative w-full max-w-5xl bg-white/90 backdrop-blur-xl rounded-3xl shadow-2xl overflow-hidden border border-white/40 grid lg:grid-cols-2">

        {{-- LEFT CONTENT --}}
        <div class="hidden lg:flex flex-col justify-between bg-gradient-to-br from-red-500 to-red-600 p-10 text-white relative overflow-hidden">

            {{-- Overlay --}}
            <div class="absolute -top-10 -right-10 w-52 h-52 bg-white/10 rounded-full blur-3xl"></div>
            <div class="absolute bottom-0 left-0 w-52 h-52 bg-black/10 rounded-full blur-3xl"></div>

            <div class="relative z-10">

                {{-- Logo --}}
                <div class="w-16 h-16 rounded-2xl bg-white/20 backdrop-blur-lg flex items-center justify-center shadow-lg mb-6">
                    <svg xmlns="http://www.w3.org/2000/svg"
                        class="w-8 h-8 text-white"
                        fill="currentColor"
                        viewBox="0 0 24 24">
                        <path d="M23.642 5.437 12.53.042a1.176 1.176 0 0 0-1.06 0L.358 5.437A.59.59 0 0 0 0 5.97v12.06c0 .228.132.437.338.533l11.112 5.395c.17.082.37.082.54 0l11.112-5.395a.59.59 0 0 0 .338-.533V5.97a.59.59 0 0 0-.338-.533Z"/>
                    </svg>
                </div>

                {{-- Heading --}}
                <h1 class="text-4xl font-bold leading-tight mb-4">
                    Sistem Monitoring Santri
                </h1>

                <p class="text-white/90 leading-relaxed text-sm lg:text-base">
                    Platform modern untuk memantau perkembangan dan aktivitas santri secara digital, cepat, dan efisien.
                </p>

            </div>

            {{-- Bottom Info --}}
            <div class="relative z-10 mt-10 flex gap-3">

                <div class="bg-white/10 backdrop-blur-lg rounded-2xl px-4 py-3 flex-1">
                    <h3 class="font-bold text-lg">100%</h3>
                    <p class="text-xs text-white/80">Gratis</p>
                </div>

                <div class="bg-white/10 backdrop-blur-lg rounded-2xl px-4 py-3 flex-1">
                    <h3 class="font-bold text-lg">Modern</h3>
                    <p class="text-xs text-white/80">Responsive UI</p>
                </div>

            </div>
        </div>

        {{-- RIGHT FORM --}}
        <div class="p-6 sm:p-8 lg:p-10 flex items-center">

            <div class="w-full">

                {{-- Mobile Header --}}
                <div class="lg:hidden text-center mb-6">
                    <div class="w-14 h-14 mx-auto rounded-2xl bg-red-500 flex items-center justify-center shadow-lg mb-4">
                        <span class="text-white text-xl font-bold">L</span>
                    </div>

                    <h2 class="text-2xl font-bold text-slate-800">
                        Register
                    </h2>

                    <p class="text-slate-500 text-sm mt-2">
                        Buat akun baru untuk melanjutkan.
                    </p>
                </div>

                {{-- Desktop Header --}}
                <div class="hidden lg:block mb-8">
                    <h2 class="text-3xl font-bold text-slate-800 mb-2">
                        Buat Akun Baru
                    </h2>

                    <p class="text-slate-500">
                        Lengkapi form di bawah untuk mulai menggunakan sistem.
                    </p>
                </div>

                {{-- FORM --}}
                <form method="POST" action="{{ route('register') }}" class="space-y-4">
                    @csrf

                    {{-- Name --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Nama Lengkap
                        </label>

                        <input
                            id="name"
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            required
                            autofocus
                            autocomplete="name"
                            placeholder="Masukkan nama lengkap"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-red-500 focus:ring-4 focus:ring-red-100 outline-none transition"
                        >

                        @error('name')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Email
                        </label>

                        <input
                            id="email"
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            required
                            autocomplete="username"
                            placeholder="Masukkan email"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-red-500 focus:ring-4 focus:ring-red-100 outline-none transition"
                        >

                        @error('email')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Password
                        </label>

                        <input
                            id="password"
                            type="password"
                            name="password"
                            required
                            autocomplete="new-password"
                            placeholder="Masukkan password"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-red-500 focus:ring-4 focus:ring-red-100 outline-none transition"
                        >

                        @error('password')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Confirm Password --}}
                    <div>
                        <label class="block text-sm font-semibold text-slate-700 mb-2">
                            Konfirmasi Password
                        </label>

                        <input
                            id="password_confirmation"
                            type="password"
                            name="password_confirmation"
                            required
                            autocomplete="new-password"
                            placeholder="Ulangi password"
                            class="w-full rounded-2xl border border-slate-200 bg-white px-4 py-3 text-sm focus:border-red-500 focus:ring-4 focus:ring-red-100 outline-none transition"
                        >

                        @error('password_confirmation')
                            <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Footer --}}
                    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 pt-2">

                        <a href="{{ route('login') }}"
                            class="text-sm text-slate-600 hover:text-red-500 transition text-center sm:text-left">
                            Sudah punya akun?
                        </a>

                        <button
                            type="submit"
                            class="w-full sm:w-auto bg-red-500 hover:bg-red-600 text-white font-semibold px-6 py-3 rounded-2xl shadow-lg shadow-red-200 transition duration-300"
                        >
                            Register Sekarang
                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</body>
</html>