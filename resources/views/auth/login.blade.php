<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Login</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600,700" rel="stylesheet" />
</head>

<body class="bg-gradient-to-br from-blue-50 via-white to-sky-100 min-h-screen overflow-hidden">

<div class="min-h-screen flex items-center justify-center px-4 py-5">

    {{-- BACKGROUND OVERLAY --}}
    <div class="absolute top-0 left-0 w-96 h-96 bg-blue-300 opacity-20 blur-3xl rounded-full"></div>
    <div class="absolute bottom-0 right-0 w-96 h-96 bg-sky-300 opacity-20 blur-3xl rounded-full"></div>

    <div class="relative w-full max-w-5xl bg-white rounded-[2rem] shadow-2xl overflow-hidden grid lg:grid-cols-2 border border-slate-100">

        {{-- LEFT CONTENT --}}
        <div class="hidden lg:flex bg-blue-700 relative p-14 text-white flex-col justify-center overflow-hidden">

            <div class="absolute inset-0 bg-gradient-to-br from-blue-700 to-sky-500"></div>

            <div class="relative z-10">

                <div class="mb-8">
                    <div class="w-20 h-20 rounded-3xl bg-white/20 backdrop-blur flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke-width="1.5"
                             stroke="currentColor"
                             class="w-10 h-10 text-white">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  d="M8.25 21l-4.5-2.598V5.598L8.25 3m0 18l4.5-2.598m-4.5 2.598V3m4.5 15.402L17.25 21m0 0l4.5-2.598V5.598L17.25 3m0 18V3m-4.5 15.402V5.598"/>
                        </svg>
                    </div>
                </div>

                <h1 class="text-5xl font-bold leading-tight mb-6">
                    Selamat Datang
                </h1>

                <p class="text-lg text-blue-100 leading-relaxed">
                    Silahkan login untuk mengakses dashboard monitoring
                    santri dan santriwati pondok pesantren secara realtime.
                </p>

                <div class="grid grid-cols-2 gap-5 mt-10">

                    <div class="bg-white/10 backdrop-blur p-5 rounded-2xl border border-white/20">
                        <h3 class="text-3xl font-bold">24/7</h3>
                        <p class="text-sm text-blue-100 mt-1">
                            Monitoring
                        </p>
                    </div>

                    <div class="bg-white/10 backdrop-blur p-5 rounded-2xl border border-white/20">
                        <h3 class="text-3xl font-bold">100%</h3>
                        <p class="text-sm text-blue-100 mt-1">
                            Responsive
                        </p>
                    </div>

                </div>

            </div>
        </div>

        {{-- RIGHT FORM --}}
        <div class="p-8 lg:p-14 flex items-center">

            <div class="w-full">

                <div class="mb-10">
                    <h2 class="text-4xl font-bold text-slate-800">
                        Login Account
                    </h2>

                    <p class="text-slate-500 mt-2">
                        Masukkan email dan password untuk melanjutkan
                    </p>
                </div>

                <!-- Session Status -->
                <x-auth-session-status class="mb-4" :status="session('status')" />

                <form method="POST" action="{{ route('login') }}" class="space-y-6">
                    @csrf

                    <!-- Email -->
                    <div>
                        <x-input-label for="email" :value="__('Email')" />

                        <x-text-input
                            id="email"
                            class="block mt-2 w-full rounded-2xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 py-3 px-4"
                            type="email"
                            name="email"
                            :value="old('email')"
                            required
                            autofocus
                            autocomplete="username"
                        />

                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <!-- Password -->
                    <div>
                        <x-input-label for="password" :value="__('Password')" />

                        <x-text-input
                            id="password"
                            class="block mt-2 w-full rounded-2xl border-slate-300 focus:border-blue-500 focus:ring-blue-500 py-3 px-4"
                            type="password"
                            name="password"
                            required
                            autocomplete="current-password"
                        />

                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <!-- Remember -->
                    <div class="flex items-center justify-between">

                        <label for="remember_me" class="inline-flex items-center">
                            <input id="remember_me"
                                   type="checkbox"
                                   class="rounded border-gray-300 text-blue-600 shadow-sm focus:ring-blue-500"
                                   name="remember">

                            <span class="ms-2 text-sm text-slate-600">
                                Remember me
                            </span>
                        </label>

                        @if (Route::has('password.request'))
                            <a class="text-sm text-blue-600 hover:text-blue-800 font-medium"
                               href="{{ route('password.request') }}">
                                Forgot Password?
                            </a>
                        @endif
                    </div>

                    <!-- BUTTON -->
                    <div class="pt-3">

                        <button type="submit"
                                class="w-full py-4 rounded-2xl bg-blue-700 hover:bg-blue-800 text-white font-semibold shadow-xl shadow-blue-200 transition duration-300">
                            Log in
                        </button>

                    </div>

                    <!-- REGISTER -->
                    @if (Route::has('register'))
                        <div class="text-center pt-3">
                            <p class="text-slate-500">
                                Belum punya akun?

                                <a href="{{ route('register') }}"
                                   class="text-blue-700 font-semibold hover:text-blue-800">
                                    Daftar Sekarang
                                </a>
                            </p>
                        </div>
                    @endif

                </form>

            </div>

        </div>

    </div>

</div>

</body>
</html>