<x-app-layout>
    <div class="py-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Header & Kartu Ringkasan (Digabung agar hemat tempat) -->
            <div class="grid grid-cols-1 lg:grid-cols-4 gap-4 items-center">
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 lg:col-span-1 flex flex-col justify-between h-full">
                    <div>
                        <h3 class="text-base font-bold text-gray-800">Laporan Bank Sampah</h3>
                        <p class="text-[11px] text-gray-500 mt-0.5">Ringkasan operasional dan data terkini RW.</p>
                    </div>
                    
                    <!-- TAMBAHKAN TOMBOL PDF DI SINI -->
                    <div class="mt-4 pt-3 border-t border-gray-50">
                        <a href="{{ route('rw.laporan.pdf') }}" target="_blank" class="w-full inline-flex items-center justify-center gap-1.5 px-3 py-2 bg-red-600 text-white rounded-xl font-bold hover:bg-red-700 transition text-xs shadow-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            Download Laporan PDF
                        </a>
                    </div>
                </div>

                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Total Sampah</p>
                        <h4 class="text-lg font-bold text-emerald-600">{{ number_format($totalSampah, 1) }} Kg</h4>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Total Transaksi</p>
                        <h4 class="text-lg font-bold text-indigo-600">{{ $totalTransaksi }} Kali</h4>
                    </div>
                </div>
                <div class="bg-white p-4 rounded-2xl shadow-sm border border-gray-100 flex items-center justify-between">
                    <div>
                        <p class="text-[10px] text-gray-400 font-bold uppercase">Total Nasabah</p>
                        <h4 class="text-lg font-bold text-purple-600">{{ $totalNasabah }} Orang</h4>
                    </div>
                </div>
            </div>

            <!-- Grid Grafik 1: Bulanan & Jenis Sampah -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-2 bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                    <h4 class="text-xs font-bold text-gray-800 mb-3 uppercase tracking-wider">Statistik Berat Sampah Per Bulan</h4>
                    <div style="height: 220px;">
                        <canvas id="laporanChart"></canvas>
                    </div>
                </div>

                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                    <h4 class="text-xs font-bold text-gray-800 mb-3 uppercase tracking-wider">Jenis Sampah</h4>
                    <div class="space-y-2.5 overflow-y-auto max-h-[220px] pr-1">
                        @foreach($jenisSampah as $js)
                        <div class="flex justify-between text-xs border-b border-gray-50 pb-1.5">
                            <span class="text-gray-600">{{ $js->jenis_sampah }}</span>
                            <span class="font-bold text-gray-900">{{ number_format($js->berat, 1) }} Kg</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Grid Grafik 2: Harian (Senin-Minggu) & Metode Pencairan (Berjajar agar Praktis) -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Grafik Harian (Senin - Minggu) -->
                <div class="md:col-span-2 bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
                    <div>
                        <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider">Statistik Harian (Senin s/d Minggu)</h4>
                        <p class="text-[11px] text-gray-500 mt-0.5">Tren setoran sampah mingguan warga.</p>
                    </div>
                    <div style="height: 200px;" class="my-3">
                        <canvas id="dailyChart"></canvas>
                    </div>
                    <div class="p-3 bg-gray-50 rounded-xl text-[11px] text-gray-600">
                        <span class="font-bold text-gray-700">📌 Evaluasi:</span> Pantau hari dengan setoran tertinggi untuk penjadwalan petugas piket RW.
                    </div>
                </div>

                <!-- Grafik Pie Metode Pencairan -->
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex flex-col justify-between">
                    <h4 class="text-xs font-bold text-gray-800 uppercase tracking-wider mb-1">Metode Pencairan Uang</h4>
                    <div style="max-height: 210px; display: flex; justify-content: center; align-items: center;">
                        <canvas id="pieChartUang"></canvas>
                    </div>
                    <p class="text-[10px] text-gray-400 text-center mt-2">Distribusi penarikan saldo warga.</p>
                </div>
            </div>

            <!-- Tabel Rekap Data (Compact) -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-5">
                    <h4 class="text-xs font-bold text-gray-800 mb-3 uppercase tracking-wider">Riwayat Rekap Data</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                            <thead class="bg-gray-50 text-gray-700 uppercase tracking-wider">
                                <tr>
                                    <th class="px-4 py-3 font-semibold">Jenis Laporan</th>
                                    <th class="px-4 py-3 font-semibold">Tujuan</th>
                                    <th class="px-4 py-3 font-semibold">Tanggal</th>
                                    <th class="px-4 py-3 font-semibold text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white text-gray-600">
                                @forelse($laporan as $item)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-4 py-3 font-semibold text-gray-900">{{ $item->jenis_laporan }}</td>
                                    <td class="px-4 py-3">{{ $item->tujuan }}</td>
                                    <td class="px-4 py-3">{{ $item->created_at->format('d M Y, H:i') }}</td>
                                    <td class="px-4 py-3 text-center">
                                        <a href="{{ asset('storage/'.$item->file_path) }}" target="_blank" 
                                           class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-lg font-bold hover:bg-indigo-100 transition text-[11px]">
                                            Download
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-4 py-6 text-center text-gray-400">Belum ada laporan yang diunggah.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js Script -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // 1. Grafik Batang Bulanan
        const ctxBar = document.getElementById('laporanChart').getContext('2d');
        new Chart(ctxBar, {
            type: 'bar',
            data: {
                labels: {!! json_encode($grafikData->pluck('bulan')) !!},
                datasets: [{
                    label: 'Total Berat (Kg)',
                    data: {!! json_encode($grafikData->pluck('total_berat')) !!},
                    backgroundColor: 'rgba(16, 185, 129, 0.7)',
                    borderColor: 'rgba(16, 185, 129, 1)',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } }
            }
        });

        // 2. Grafik Garis Harian (Senin - Minggu)
        const ctxDaily = document.getElementById('dailyChart').getContext('2d');
        new Chart(ctxDaily, {
            type: 'line',
            data: {
                labels: {!! json_encode($grafikHarian->pluck('nama_hari')) !!},
                datasets: [{
                    label: 'Berat (Kg)',
                    data: {!! json_encode($grafikHarian->pluck('total_berat')) !!},
                    backgroundColor: 'rgba(79, 70, 229, 0.1)',
                    borderColor: 'rgba(79, 70, 229, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.3
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                scales: { y: { beginAtZero: true } },
                plugins: { legend: { display: false } }
            }
        });

        // 3. Grafik Pie Metode Pencairan
        const ctxPie = document.getElementById('pieChartUang').getContext('2d');
        new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: {!! json_encode($pieChartData->pluck('metode_pencairan')) !!},
                datasets: [{
                    data: {!! json_encode($pieChartData->pluck('total_uang')) !!},
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 10 } } }
                }
            }
        });
    </script>
</x-app-layout>