<x-app-layout>

    <!-- Main Content -->
    <div class="p-4 md:p-8 bg-gray-100 min-h-screen">

        <!-- Heading -->
        <div class="mb-8">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                Dashboard
            </h1>

            <p class="text-gray-500 mt-1">
                Selamat datang kembali, {{ Auth::user()->name }}
            </p>
        </div>

        <!-- Statistik -->
        <div class="grid grid-cols-1 sm:grid-cols-2 xl:grid-cols-4 gap-6">

            <!-- Card -->
            <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">
                            Total Santri
                        </p>

                        <h2 class="text-3xl font-bold text-gray-800 mt-2">
                            120
                        </h2>
                    </div>

                    <div class="h-14 w-14 rounded-2xl bg-indigo-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-7 w-7 text-indigo-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17 20h5V4H2v16h5m10 0v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6m10 0H7"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card -->
            <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">
                            Santri Aktif
                        </p>

                        <h2 class="text-3xl font-bold text-gray-800 mt-2">
                            98
                        </h2>
                    </div>

                    <div class="h-14 w-14 rounded-2xl bg-green-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-7 w-7 text-green-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M9 12l2 2 4-4"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card -->
            <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">
                            Pelanggaran
                        </p>

                        <h2 class="text-3xl font-bold text-gray-800 mt-2">
                            5
                        </h2>
                    </div>

                    <div class="h-14 w-14 rounded-2xl bg-red-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-7 w-7 text-red-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 8v4m0 4h.01"/>
                        </svg>
                    </div>
                </div>
            </div>

            <!-- Card -->
            <div class="bg-white rounded-3xl shadow-sm p-6 border border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-500">
                            Pengajar
                        </p>

                        <h2 class="text-3xl font-bold text-gray-800 mt-2">
                            12
                        </h2>
                    </div>

                    <div class="h-14 w-14 rounded-2xl bg-yellow-100 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                            class="h-7 w-7 text-yellow-600"
                            fill="none"
                            viewBox="0 0 24 24"
                            stroke="currentColor">

                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M12 14l9-5-9-5-9 5 9 5z"/>
                        </svg>
                    </div>
                </div>
            </div>

        </div>

        <!-- Content -->
        <div class="grid grid-cols-1 xl:grid-cols-3 gap-6 mt-8">

            <!-- Activity -->
            <div class="xl:col-span-2 bg-white rounded-3xl shadow-sm border border-gray-100 p-6">

                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-bold text-gray-800">
                        Aktivitas Terbaru
                    </h2>

                    <button class="text-sm text-indigo-600 hover:underline">
                        Lihat Semua
                    </button>
                </div>

                <div class="space-y-4">

                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-gray-50">
                        <div class="h-10 w-10 rounded-full bg-indigo-100 flex items-center justify-center text-indigo-600 font-bold">
                            A
                        </div>

                        <div>
                            <h3 class="font-semibold text-gray-800">
                                Ahmad masuk asrama
                            </h3>

                            <p class="text-sm text-gray-500">
                                10 menit yang lalu
                            </p>
                        </div>
                    </div>

                    <div class="flex items-start gap-4 p-4 rounded-2xl bg-gray-50">
                        <div class="h-10 w-10 rounded-full bg-red-100 flex items-center justify-center text-red-600 font-bold">
                            R
                        </div>

                        <div>
                            <h3 class="font-semibold text-gray-800">
                                Rizki mendapat pelanggaran
                            </h3>

                            <p class="text-sm text-gray-500">
                                1 jam yang lalu
                            </p>
                        </div>
                    </div>

                </div>
            </div>

            <!-- Profile -->
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">

                <div class="text-center">

                    <div class="h-24 w-24 rounded-full bg-indigo-100 mx-auto flex items-center justify-center text-3xl font-bold text-indigo-700">
                        {{ strtoupper(substr(Auth::user()->name,0,1)) }}
                    </div>

                    <h2 class="mt-4 text-xl font-bold text-gray-800">
                        {{ Auth::user()->name }}
                    </h2>

                    <p class="text-gray-500">
                        {{ Auth::user()->email }}
                    </p>

                    <a href="{{ route('profile.edit') }}"
                        class="mt-6 inline-flex items-center justify-center px-5 py-3 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl transition">

                        Edit Profile
                    </a>

                </div>

            </div>
        </div>
    </div>

</x-app-layout>