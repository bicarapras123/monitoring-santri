<x-app-layout>
    <!-- Main Content -->
    <div class="p-4 md:p-6 bg-gray-50 min-h-screen pb-20">
        
        <!-- Heading & Flash Message -->
        <div class="mb-5 flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-gray-800">Kelola Data Nasabah</h1>
                <p class="text-gray-500 text-xs md:text-sm">Daftar seluruh nasabah yang terdaftar di Bank Sampah RW.04.</p>
            </div>

            <!-- Navigasi Tombol antar halaman -->
            <div class="flex gap-2">
                <a href="{{ route('admin.nasabah.index') }}" class="px-3.5 py-2 bg-indigo-600 text-white rounded-xl text-xs font-semibold shadow-sm transition">
                    Data Semua Nasabah
                </a>
                <a href="{{ route('admin.nasabah.rekening') }}" class="px-3.5 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl text-xs font-semibold hover:bg-gray-50 transition">
                    Data Pengajuan Rekening 
                </a>
            </div>
        </div>

        <!-- Notifikasi Sukses -->
        @if (session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-2xl shadow-sm text-xs md:text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Card Tabel Daftar Data -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
            <div class="flex items-center justify-between mb-4">
                <h2 class="text-base font-bold text-gray-800">Tabel Nasabah Terdaftar</h2>
                <span class="text-xs bg-indigo-50 text-indigo-700 font-semibold px-2.5 py-1 rounded-lg">
                    Total: {{ $nasabahs->count() }} Orang
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-gray-100 text-gray-400 text-[11px] uppercase tracking-wider">
                            <th class="py-2.5 px-3 font-semibold w-10">No</th>
                            <th class="py-2.5 px-3 font-semibold">NIK & Nama Lengkap</th>
                            <th class="py-2.5 px-3 font-semibold">Kontak & Alamat</th>
                            <th class="py-2.5 px-3 font-semibold">Nama Orang Tua</th>
                            <th class="py-2.5 px-3 font-semibold">Info E-Wallet</th>
                        </tr>
                    </thead>
                    <tbody class="text-xs md:text-sm divide-y divide-gray-50">
                        @forelse ($nasabahs as $index => $nasabah)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-3 px-3 text-gray-500 font-medium">{{ $index + 1 }}</td>
                                <td class="py-3 px-3">
                                    <div class="font-bold text-gray-800">{{ $nasabah->nama_lengkap }}</div>
                                    <div class="text-[11px] text-indigo-600 font-medium mt-0.5">NIK: {{ $nasabah->nik }}</div>
                                </td>
                                <td class="py-3 px-3">
                                    <div class="text-gray-800 font-medium">{{ $nasabah->nomor_telepon }}</div>
                                    <div class="text-[11px] text-gray-400 truncate max-w-xs mt-0.5" title="{{ $nasabah->alamat_lengkap }}">
                                        {{ $nasabah->alamat_lengkap }}
                                    </div>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="font-medium text-gray-700">{{ $nasabah->nama_orang_tua }}</span>
                                </td>
                                <td class="py-3 px-3">
                                    @if($nasabah->rekening)
                                        <div class="inline-flex flex-col">
                                            <span class="px-2.5 py-0.5 bg-emerald-50 text-emerald-700 rounded-lg text-[11px] font-semibold w-fit">
                                                {{ $nasabah->rekening->jenis_ewallet }}
                                            </span>
                                            <span class="text-[11px] text-gray-500 mt-0.5 font-mono">{{ $nasabah->rekening->nomor_rekening }}</span>
                                        </div>
                                    @else
                                        <span class="px-2.5 py-0.5 bg-amber-50 text-amber-700 rounded-lg text-[11px] font-semibold">
                                            Belum Set Rekening
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="py-6 text-center text-gray-400 text-xs">
                                    Belum ada data nasabah yang terdaftar.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>