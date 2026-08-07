<x-app-layout>
    <div class="py-12" x-data="{ openModal: false, selectedNasabah: '', selectedSaldo: 0, riwayatData: {} }">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Header & Pencarian -->
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Daftar Nasabah & Tabungan</h3>
                            <p class="text-xs text-gray-500">Klik tombol "Detail Riwayat" pada masing-masing nasabah untuk melihat rincian transaksi sampah.</p>
                        </div>
                        
                        <div class="w-full md:w-auto">
                            <form method="GET" action="{{ route('admin.transaksi.index') }}" class="flex gap-2">
                                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama atau no. telepon..." class="text-xs border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-indigo-500 w-full md:w-72">
                                <button type="submit" class="bg-indigo-600 text-white text-xs px-4 py-2 rounded-xl hover:bg-indigo-700 transition">Cari</button>
                            </form>
                        </div>
                    </div>

                    <!-- Tabel Ringkas & Simple -->
                    <div class="overflow-x-auto border border-gray-100 rounded-2xl">
                        <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                            <thead class="bg-gray-50 text-gray-700 uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-3.5 font-semibold">No</th>
                                    <th class="px-6 py-3.5 font-semibold">Nama Lengkap</th>
                                    <th class="px-6 py-3.5 font-semibold">No. Telepon</th>
                                    <th class="px-6 py-3.5 font-semibold text-center">Total Setoran</th>
                                    <th class="px-6 py-3.5 font-semibold">Akumulasi Berat</th>
                                    <th class="px-6 py-3 font-semibold">Total Saldo Tabungan</th>
                                    <th class="px-6 py-3.5 font-semibold text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white text-gray-600">
                                @forelse($nasabahs as $index => $nasabah)
                                @php
                                    // Hitung total penarikan yang berstatus pending/disetujui secara real-time untuk nasabah ini
                                    $penarikanNasabah = \App\Models\Penarikan::where('user_id', $nasabah->user_id ?? $nasabah->id)
                                        ->whereIn('status', ['pending', 'disetujui'])
                                        ->sum('jumlah_penarikan');
                                    
                                    // Saldo bersih secara real-time
                                    $saldoRealtime = ($nasabah->total_keseluruhan_tabungan ?? 0) - $penarikanNasabah;
                                    if ($saldoRealtime < 0) { $saldoRealtime = 0; }
                                @endphp
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $nasabahs->firstItem() + $index }}</td>
                                    <td class="px-6 py-4 font-semibold text-gray-900 text-sm whitespace-nowrap">{{ $nasabah->nama_lengkap }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-600">{{ $nasabah->nomor_telephone }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <span class="px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-full font-semibold">{{ $nasabah->total_setoran }} Kali</span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-800">{{ $nasabah->akumulasi_berat }} Kg</td>
                                    <td class="px-6 py-4 whitespace-nowrap font-bold text-emerald-600 text-sm">
                                        Rp {{ number_format($saldoRealtime, 0, ',', '.') }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center">
                                        <button @click="openModal = true; selectedNasabah = '{{ $nasabah->nama_lengkap }}'; selectedSaldo = {{ $saldoRealtime }}; riwayatData = {{ json_encode($semuaSetoran[$nasabah->nama_lengkap] ?? []) }}" 
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-xl hover:bg-indigo-600 hover:text-white transition font-medium shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Detail Riwayat
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-gray-400">Belum ada data transaksi nasabah.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $nasabahs->links() }}
                    </div>

                </div>
            </div>
        </div>

        <!-- MODAL POP-UP RIWAYAT LENGKAP -->
        <div x-show="openModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
            <div @click.away="openModal = false" class="bg-white rounded-2xl max-w-5xl w-full p-6 shadow-2xl overflow-hidden transform transition-all">
                
                <!-- Header Modal -->
                <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-4">
                    <div>
                        <h3 class="text-base font-bold text-gray-900">Rincian Riwayat Setoran Sampah</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Nasabah: <span class="font-semibold text-indigo-600" x-text="selectedNasabah"></span></p>
                    </div>
                    <button @click="openModal = false" class="text-gray-400 hover:text-gray-600 p-1.5 rounded-lg hover:bg-gray-100 transition">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Isi Tabel Detail Riwayat di dalam Modal -->
                <div class="max-h-96 overflow-x-auto overflow-y-auto border border-gray-100 rounded-xl">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                        <thead class="bg-gray-50 text-gray-700 uppercase">
                            <tr>
                                <th class="px-4 py-3 font-semibold">Jenis Sampah</th>
                                <th class="px-4 py-3 font-semibold">Berat</th>
                                <th class="px-4 py-3 font-semibold">Tabungan Real-Time (Rp)</th>
                                <th class="px-4 py-3 font-semibold">Alamat</th>
                                <th class="px-4 py-3 font-semibold">Metode Pencairan</th>
                                <th class="px-4 py-3 font-semibold">No. Rekening</th>
                                <th class="px-4 py-3 font-semibold text-center">Status</th>
                                <th class="px-4 py-3 font-semibold">Tanggal Setor</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white text-gray-600">
                            <template x-for="item in riwayatData" :key="item.id">
                                <tr>
                                    <td class="px-4 py-3 font-medium text-gray-900 whitespace-nowrap" x-text="item.jenis_sampah"></td>
                                    <td class="px-4 py-3 font-semibold whitespace-nowrap" x-text="item.total_berat + ' Kg'"></td>
                                    
                                    <!-- Menampilkan nominal saldo real-time (bisa diubah sesuai kebutuhan, misal menampilkan sisa saldo real-time nasabah) -->
                                    <td class="px-4 py-3 font-bold text-emerald-600 whitespace-nowrap" x-text="'Rp ' + Number(selectedSaldo).toLocaleString('id-ID')"></td>

                                    <td class="px-4 py-3 max-w-xs truncate" :title="item.alamat_lengkap" x-text="item.alamat_lengkap ?? '-' "></td>
                                    <td class="px-4 py-3 whitespace-nowrap">
                                        <span class="px-2 py-0.5 bg-gray-100 rounded font-medium text-gray-700" x-text="item.jenis_rekening ?? 'Cash / Tunai'"></span>
                                    </td>
                                    <td class="px-4 py-3 whitespace-nowrap" x-text="item.nomor_rekening ?? '-' "></td>
                                    
                                    <!-- Status (Diambil langsung dari tabel penarikans) -->
                                    <td class="px-4 py-3 whitespace-nowrap text-center">
                                        <span class="px-2.5 py-1 text-xs font-semibold rounded-full"
                                            :class="{
                                                'bg-amber-100 text-amber-700': item.status_penarikan === 'pending',
                                                'bg-emerald-100 text-emerald-700': ['disetujui', 'selesai', 'approved'].includes(item.status_penarikan),
                                                'bg-red-100 text-red-700': item.status_penarikan === 'ditolak',
                                                'bg-gray-100 text-gray-700': !['pending', 'disetujui', 'selesai', 'approved', 'ditolak'].includes(item.status_penarikan)
                                            }" 
                                            x-text="item.status_penarikan ? (item.status_penarikan.charAt(0).toUpperCase() + item.status_penarikan.slice(1)) : 'Pending'">
                                        </span>
                                    </td>

                                    <td class="px-4 py-3 whitespace-nowrap" x-text="new Date(item.created_at).toLocaleString('id-ID', {dateStyle: 'medium', timeStyle: 'short'})"></td>
                                </tr>
                            </template>
                        </tbody>
                    </table>
                </div>

                <!-- Footer Modal -->
                <div class="mt-6 flex justify-end">
                    <button @click="openModal = false" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-xl text-xs font-medium hover:bg-gray-200 transition">Tutup</button>
                </div>

            </div>
        </div>

    </div>
</x-app-layout>