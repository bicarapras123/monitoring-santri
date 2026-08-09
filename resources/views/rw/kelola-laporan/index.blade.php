<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Kelola Laporan Data Warga</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Daftar verifikasi pengesahan laporan warga RW.</p>
                </div>
                <a href="{{ route('rw.kelolaporan.create') }}" class="px-4 py-2 bg-emerald-600 text-white rounded-xl text-xs font-bold hover:bg-emerald-700 transition">
                    + Tambah Laporan
                </a>
            </div>

            @if(session('success'))
                <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-xl text-xs font-semibold">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-6 overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                        <thead class="bg-gray-50 text-gray-700 uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3 font-semibold">NIK & Nama</th>
                                <th class="px-4 py-3 font-semibold">Alamat Lengkap</th>
                                <th class="px-4 py-3 font-semibold">Kontak</th>
                                <th class="px-4 py-3 font-semibold">Waktu Upload</th> <!-- Kolom Baru -->
                                <th class="px-4 py-3 font-semibold">Dokumen</th>
                                <th class="px-4 py-3 font-semibold text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white text-gray-600">
                            @forelse($laporans as $item)
                            <tr class="hover:bg-gray-50 transition">
                                <td class="px-4 py-3">
                                    <div class="font-bold text-gray-900">{{ $item->nama_lengkap }}</div>
                                    <div class="text-[11px] text-gray-400">NIK: {{ $item->nik }}</div>
                                </td>
                                <td class="px-4 py-3">
                                    Kel. {{ $item->kelurahan }}, Kec. {{ $item->kecamatan }}<br>
                                    {{ $item->kota }} - {{ $item->kode_pos }}
                                </td>
                                <td class="px-4 py-3">{{ $item->nomor_telepon }}</td>
                                <!-- Menampilkan Tanggal dan Waktu -->
                                <td class="px-4 py-3 text-gray-500">
                                    {{ $item->created_at->format('d M Y') }}<br>
                                    <span class="text-[10px]">{{ $item->created_at->format('H:i:s') }}</span>
                                </td>
                                <td class="px-4 py-3">
                                    @if($item->file_upload)
                                        <a href="{{ Storage::url($item->file_upload) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-gray-100 text-gray-700 rounded-lg text-xs font-semibold hover:bg-emerald-50 hover:text-emerald-700 transition">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Lihat
                                        </a>
                                    @else
                                        <span class="text-gray-400 italic">N/A</span>
                                    @endif
                                </td>
                                <td class="px-4 py-3 text-center">
                                    <span class="px-2.5 py-1 bg-emerald-100 text-emerald-700 rounded-full font-bold text-[10px]">
                                        Sah
                                    </span>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="6" class="px-4 py-8 text-center text-gray-400">Belum ada data laporan yang dimasukkan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>