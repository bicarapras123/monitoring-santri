<x-app-layout>
<!-- CSS Print Profesional: Sembunyikan Sidebar & Buat Konten Full ke Bawah -->
<style>
    @media print {
        /* 1. Sembunyikan SEMUA elemen navigasi, sidebar, dan header layout */
        aside, nav, header, 
        .navigation, .sidebar, 
        [class*="sidebar"], [class*="navigation"], 
        [class*="drawer"], [class*="menu"],
        .bg-gray-800 { /* Sesuaikan jika background sidebar Anda warna abu-abu */
            display: none !important;
        }

        /* 2. Reset Body agar putih bersih */
        body {
            background: #ffffff !important;
            margin: 0 !important;
            padding: 0 !important;
        }

        /* 3. Paksa kontainer konten agar mengambil 100% lebar kertas */
        .py-12, .max-w-7xl, .mx-auto, .px-6, .lg:px-8 {
            margin: 0 !important;
            padding: 0 !important;
            max-width: 100% !important;
            width: 100% !important;
        }

        /* 4. Sembunyikan tombol, form filter, dan input saat cetak */
        .no-print, form, input, button, select, .filter-section {
            display: none !important;
        }

        /* 5. Optimasi Grid dan Tabel agar mengalir ke bawah (Multi-page) */
        .grid {
            display: block !important;
        }
        
        .grid > div {
            display: block !important;
            width: 100% !important;
            margin-bottom: 20px !important;
            break-inside: avoid;
        }

        table {
            width: 100% !important;
            border-collapse: collapse !important;
            page-break-inside: auto !important;
        }

        tr {
            page-break-inside: avoid !important;
            page-break-after: auto !important;
        }
    }
</style>


    <div class="py-12">
        <!-- Notifikasi Sukses -->
            @if (session('success'))
                <div id="notif-sukses" class="bg-emerald-50 border-l-4 border-emerald-500 p-4 mb-6 rounded-r-2xl shadow-sm">
                    <div class="flex">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-emerald-500" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-bold text-emerald-800">Berhasil!</p>
                            <p class="text-xs text-emerald-600">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
                
                <!-- Script untuk menghilang otomatis setelah 5 detik -->
                <script>
                    setTimeout(function() {
                        document.getElementById('notif-sukses').style.display = 'none';
                    }, 5000);
                </script>
            @endif
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- 1. Header Halaman & Tombol Ekspor -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center gap-4 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Kelola Laporan Bank Sampah</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Pusat analisis data, rekapitulasi penarikan saldo, dan unduh laporan transaksi.</p>
                </div>
                
                <div class="flex items-center gap-2 no-print">
                <button onclick="cetakDashboard()" class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-gray-800 text-white hover:bg-gray-900 rounded-xl text-xs font-semibold transition">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"/>
                    </svg>
                    Cetak Dashboard
                </button>
                    
                    <a href="{{ route('admin.laporan.pdf', ['start_date' => $startDate, 'end_date' => $endDate, 'kategori' => $kategori]) }}" 
                       class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-red-50 text-red-700 hover:bg-red-100 rounded-xl text-xs font-semibold transition">
                        PDF
                    </a>
                    <a href="{{ route('admin.laporan.excel', ['start_date' => $startDate, 'end_date' => $endDate, 'kategori' => $kategori]) }}" 
                       class="inline-flex items-center gap-1.5 px-3.5 py-2 bg-emerald-50 text-emerald-700 hover:bg-emerald-100 rounded-xl text-xs font-semibold transition">
                        Excel
                    </a>
                </div>
            </div>

                <!-- 2. Filter Periode & Kategori -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <form method="GET" action="{{ route('admin.laporan.index') }}" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-4 items-end">
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Dari Tanggal</label>
                        <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="w-full text-xs border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Sampai Tanggal</label>
                        <input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="w-full text-xs border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Metode Transaksi</label>
                        <select name="kategori" class="w-full text-xs border-gray-200 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
                            <option value="">Semua Metode</option>
                            <option value="Dana" {{ ($kategori ?? '') == 'Dana' ? 'selected' : '' }}>Dana</option>
                            <option value="GoPay" {{ ($kategori ?? '') == 'GoPay' ? 'selected' : '' }}>GoPay</option>
                            <option value="Cash" {{ ($kategori ?? '') == 'Cash' ? 'selected' : '' }}>Cash</option>
                        </select>
                    </div>
                    <div class="flex gap-2">
                        <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2 px-4 rounded-xl text-xs transition">Filter</button>
                        <a href="{{ route('admin.laporan.index') }}" class="bg-gray-100 hover:bg-gray-200 text-gray-700 font-medium py-2 px-4 rounded-xl text-xs transition flex items-center justify-center">Reset</a>
                    </div>
                </form>
            </div>

            <!-- 3. Ringkasan Eksekutif (Dashboard Cards Kompleks) -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-xs text-gray-400 font-medium">Total Nilai Penarikan</p>
                    <h4 class="text-base font-bold text-gray-900 mt-1">Rp {{ number_format($totalNilaiPenarikan ?? 0, 0, ',', '.') }}</h4>
                    <div class="mt-2 text-[11px] text-gray-500 flex justify-between">
                        <span>Disetujui: <b class="text-emerald-600">Rp {{ number_format($totalPenarikanDisetujui ?? 0, 0, ',', '.') }}</b></span>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-xs text-gray-400 font-medium">Total Pengajuan</p>
                    <h4 class="text-base font-bold text-gray-900 mt-1">{{ $jumlahTotalTransaksi ?? 0 }} Transaksi</h4>
                    <div class="mt-2 text-[11px] text-gray-500 flex gap-2">
                        <span class="bg-emerald-50 text-emerald-700 px-1.5 py-0.5 rounded">Sukses: {{ $jumlahDisetujui ?? 0 }}</span>
                        <span class="bg-amber-50 text-amber-700 px-1.5 py-0.5 rounded">Pending: {{ $jumlahPending ?? 0 }}</span>
                        <span class="bg-red-50 text-red-700 px-1.5 py-0.5 rounded">Ditolak: {{ $jumlahDitolak ?? 0 }}</span>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-xs text-gray-400 font-medium">Rata-rata Penarikan</p>
                    <h4 class="text-base font-bold text-gray-900 mt-1">Rp {{ number_format($rataRataPenarikan ?? 0, 0, ',', '.') }}</h4>
                    <p class="mt-2 text-[11px] text-gray-400">Per sekali transaksi penarikan</p>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-xs text-gray-400 font-medium">Total Berat Sampah Masuk</p>
                    <h4 class="text-base font-bold text-gray-900 mt-1">{{ number_format($totalBeratSampah ?? 0, 1, ',', '.') }} Kg</h4>
                    <p class="mt-2 text-[11px] text-gray-400">Dari {{ $totalFrekuensiSetoran ?? 0 }} kali setoran</p>
                </div>
            </div>

            <!-- 4. Analisis Performa & Rekap Metode Pencairan -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h4 class="text-sm font-bold text-gray-800 mb-4">Top 5 Nasabah Teraktif</h4>
                    <div class="space-y-3 text-xs text-gray-600">
                        @forelse($topNasabah as $nasabah)
                        <div class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0">
                            <span class="font-semibold text-gray-800">{{ $loop->iteration }}. {{ $nasabah->nama_lengkap }}</span>
                            <span class="text-indigo-600 font-bold">{{ $nasabah->total_berat }} Kg</span>
                        </div>
                        @empty
                        <p class="text-gray-400 text-center py-4">Belum ada data nasabah.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h4 class="text-sm font-bold text-gray-800 mb-4">Komposisi Sampah Tertinggi</h4>
                    <div class="space-y-3 text-xs text-gray-600">
                        @forelse($komposisiSampah as $sampah)
                        <div class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0">
                            <span class="font-semibold text-gray-800">{{ $sampah->jenis_sampah }}</span>
                            <span class="text-emerald-600 font-bold">{{ $sampah->total_berat }} Kg</span>
                        </div>
                        @empty
                        <p class="text-gray-400 text-center py-4">Belum ada data komposisi sampah.</p>
                        @endforelse
                    </div>
                </div>

                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h4 class="text-sm font-bold text-gray-800 mb-4">Rekap Metode Pencairan</h4>
                    <div class="space-y-3 text-xs text-gray-600">
                        @forelse($rekapMetode as $rekap)
                        <div class="flex justify-between items-center py-2 border-b border-gray-50 last:border-0">
                            <div>
                                <span class="font-semibold text-gray-800 block">{{ $rekap->jenis_ewallet ?? 'Lainnya' }}</span>
                                <span class="text-[10px] text-gray-400">{{ $rekap->total_pengajuan }} Transaksi</span>
                            </div>
                            <span class="text-emerald-600 font-bold">Rp {{ number_format($rekap->total_nominal, 0, ',', '.') }}</span>
                        </div>
                        @empty
                        <p class="text-gray-400 text-center py-4">Belum ada data metode pencairan.</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- 5. Tabel Detail Laporan Riwayat Penarikan Saldo -->
            <div x-data="{ search: '' }" class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-6">
                    <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
                        <h4 class="text-sm font-bold text-gray-800">Riwayat Detail Penarikan Saldo</h4>
                        <!-- Search Box -->
                        <input x-model="search" type="text" placeholder="Cari nama nasabah..." 
                            class="text-xs border-gray-200 rounded-xl px-4 py-2 w-full md:w-64 focus:ring-indigo-500">
                    </div>

                    <div class="overflow-x-auto border border-gray-100 rounded-2xl">
                        <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                            <thead class="bg-gray-50 text-gray-700 uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-3.5 font-semibold">No</th>
                                    <th class="px-6 py-3.5 font-semibold">Tanggal Pengajuan</th>
                                    <th class="px-6 py-3.5 font-semibold">Nama Nasabah</th>
                                    <th class="px-6 py-3.5 font-semibold">Total Berat Setor</th>
                                    <th class="px-6 py-3.5 font-semibold">Metode / Bank</th>
                                    <th class="px-6 py-3.5 font-semibold">Jumlah Penarikan (Rp)</th>
                                    <th class="px-6 py-3.5 font-semibold text-center">Status</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white text-gray-600">
                                @forelse($riwayatTransaksi as $index => $item)
                                {{-- Alpine.js filter search --}}
                                <tr x-show="search === '' || '{{ strtolower($item->user->nasabah->nama_lengkap ?? '') }}'.includes(search.toLowerCase())" 
                                    class="hover:bg-gray-50/50 transition">
                                    <td class="px-6 py-4 text-gray-500">{{ $loop->iteration }}</td>
                                    <td class="px-6 py-4">{{ $item->created_at ? $item->created_at->format('d M Y, H:i') : '-' }}</td>
                                    <td class="px-6 py-4 font-semibold text-gray-900">{{ $item->user->nasabah->nama_lengkap ?? $item->user->name ?? '-' }}</td>
                                    
                                    <!-- Kolom Berat Sampah yang Aman dari Null -->
                                    <td class="px-6 py-4 font-bold text-indigo-600">
                                        {{ number_format($item->user->nasabah?->setoranSampah?->sum('total_berat') ?? 0, 1, ',', '.') }} Kg
                                    </td>
                                    
                                    <td class="px-6 py-4">{{ $item->metode_pencairan ?? '-' }}</td>
                                    <td class="px-6 py-4 font-bold text-emerald-600">Rp {{ number_format($item->jumlah_penarikan ?? 0, 0, ',', '.') }}</td>
                                    <td class="px-6 py-4 text-center">
                                        @php $status = strtolower($item->status ?? 'pending'); @endphp
                                        <span class="px-2.5 py-1 text-[10px] font-semibold rounded-full
                                            @if($status == 'pending') bg-amber-100 text-amber-700
                                            @elseif($status == 'disetujui') bg-emerald-100 text-emerald-700
                                            @elseif($status == 'ditolak') bg-red-100 text-red-700
                                            @endif">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="px-6 py-10 text-center text-gray-400">Belum ada data laporan penarikan saldo.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>


            <!-- 6. Fitur Upload Laporan Eksternal -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 no-print mt-6">
                
                <!-- Upload Laporan Dashboard -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h4 class="text-sm font-bold text-gray-800 mb-2">Upload Laporan Dashboard</h4>
                    <p class="text-[10px] text-gray-500 mb-4">Upload file hasil cetak dashboard.</p>
                    <form action="{{ route('admin.laporan.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <input type="hidden" name="jenis_laporan" value="Dashboard">
                        <select name="tujuan" class="w-full text-xs rounded-xl border-gray-200">
                            <option value="RW">Ke RW</option>
                        </select>
                        <input type="file" name="file" class="text-xs w-full">
                        <button type="submit" class="w-full bg-indigo-600 text-white py-2 rounded-xl text-xs font-bold hover:bg-indigo-700">Kirim Dashboard</button>
                    </form>
                </div>

                <!-- Upload Laporan Rekap PDF -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h4 class="text-sm font-bold text-gray-800 mb-2">Upload Rekap Laporan PDF</h4>
                    <p class="text-[10px] text-gray-500 mb-4">Upload file PDF rekap bulanan.</p>
                    <form action="{{ route('admin.laporan.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                        @csrf
                        <input type="hidden" name="jenis_laporan" value="Rekap PDF">
                        <select name="tujuan" class="w-full text-xs rounded-xl border-gray-200">
                            <option value="RW">Ke RW</option>
                        </select>
                        <input type="file" name="file" class="text-xs w-full">
                        <button type="submit" class="w-full bg-emerald-600 text-white py-2 rounded-xl text-xs font-bold hover:bg-emerald-700">Kirim Rekap PDF</button>
                    </form>
                </div>
            </div>

        </div>
    </div>

    <script>
    function cetakDashboard() {
        // Ambil elemen utama konten kanan (biasanya di dalam tag py-12 atau max-w-7xl)
        const kontenUtama = document.querySelector('.max-w-7xl').innerHTML;
        
        // Simpan isi asli halaman web
        const isiAsli = document.body.innerHTML;
        
        // Timpa isi body sementara hanya dengan konten kanan yang mau dicetak
        document.body.innerHTML = `
            <div style="padding: 20px; font-family: 'Calibri', sans-serif;">
                <h2 style="text-align: center; margin-bottom: 20px; font-weight: bold;">LAPORAN DASHBOARD BANK SAMPAH RW.04</h2>
                ${kontenUtama}
            </div>
        `;
        
        // Hilangkan elemen yang tidak perlu ikut tercetak (seperti tombol filter/search di dalam salinan)
        document.querySelectorAll('.no-print, form, input[type="text"], button').forEach(el => el.style.display = 'none');
        
        // Panggil fungsi print browser
        window.print();
        
        // Kembalikan halaman ke semula setelah selesai print
        document.body.innerHTML = isiAsli;
        location.reload(); // Refresh agar fungsi Alpine.js & tombol kembali normal
    }
</script>


</x-app-layout>
