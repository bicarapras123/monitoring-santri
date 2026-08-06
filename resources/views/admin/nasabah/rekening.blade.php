<x-app-layout>
    <div class="p-4 md:p-8 bg-gray-100 min-h-screen">
        
        <!-- Header Page -->
        <div class="mb-8 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
            <div>
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                    Nasabah Pengajuan Rekening / E-Wallet
                </h1>
                <p class="text-gray-500 mt-1">
                    Daftar nasabah yang sudah melengkapi data rekening pencairan dana
                </p>
            </div>
            
            <!-- Navigasi Tombol antar halaman -->
            <div class="flex gap-2">
                <a href="{{ route('admin.nasabah.index') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl text-xs font-semibold hover:bg-gray-50 transition">
                    Data Semua Nasabah
                </a>
                <a href="{{ route('admin.nasabah.rekening') }}" class="px-4 py-2 bg-indigo-600 text-white rounded-xl text-xs font-semibold shadow-sm">
                    Data Pengajuan Rekening
                </a>
            </div>
        </div>

        <!-- Notifikasi Sukses -->
        @if (session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-2xl shadow-sm text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Card Table Container -->
        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-6 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-800">Tabel Rekening Nasabah</h2>
                <span class="text-xs bg-emerald-50 text-emerald-700 px-3 py-1 rounded-full font-semibold">
                    Total: {{ $nasabahs->count() }} Orang
                </span>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="bg-gray-50/70 text-gray-400 uppercase text-[11px] tracking-wider border-b border-gray-100">
                            <th class="py-4 px-6 font-semibold">No</th>
                            <th class="py-4 px-6 font-semibold">Nama Nasabah & NIK</th>
                            <th class="py-4 px-6 font-semibold">Jenis E-Wallet</th>
                            <th class="py-4 px-6 font-semibold">Nomor Rekening</th>
                            <th class="py-4 px-6 font-semibold">Foto KTP</th>
                            <th class="py-4 px-6 font-semibold">Status</th>
                            <th class="py-4 px-6 font-semibold text-center">Aksi Verifikasi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-100 text-sm text-gray-600">
                        @forelse ($nasabahs as $index => $nasabah)
                            <tr class="hover:bg-gray-50/50 transition">
                                <td class="py-4 px-6 font-medium text-gray-500">
                                    {{ $index + 1 }}
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-bold text-gray-800">{{ $nasabah->nama_lengkap }}</div>
                                    <div class="text-xs text-indigo-600 font-medium mt-0.5">NIK: {{ $nasabah->nik }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-lg text-xs font-semibold">
                                        {{ $nasabah->rekening->jenis_ewallet ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 font-mono font-medium text-gray-800">
                                    {{ $nasabah->rekening->nomor_rekening ?? '-' }}
                                </td>
                                <td class="py-4 px-6">
                                    @if($nasabah->rekening && $nasabah->rekening->foto_ktp)
                                        <a href="{{ asset('storage/' . $nasabah->rekening->foto_ktp) }}" target="_blank" class="text-indigo-600 underline text-xs font-semibold">
                                            Lihat KTP
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-xs">Tidak ada</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    @if($nasabah->rekening && $nasabah->rekening->status == 'verified')
                                        <span class="px-2.5 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold">Terverifikasi</span>
                                    @else
                                        <span class="px-2.5 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">Pending</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6 text-center">
                                    @if($nasabah->rekening && $nasabah->rekening->status != 'verified')
                                        <form action="{{ route('admin.nasabah.verify', $nasabah->rekening->id) }}" method="POST">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" onclick="return confirm('Apakah data rekening & KTP nasabah ini sudah sesuai?')" class="px-3 py-1.5 bg-indigo-600 hover:bg-indigo-700 text-white rounded-xl text-xs font-semibold shadow-sm transition">
                                                Verifikasi
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-gray-400 font-medium">Selesai</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="py-8 text-center text-gray-400 text-sm">
                                    Belum ada nasabah yang mengajukan rekening.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</x-app-layout>