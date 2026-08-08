<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Header -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-800">Laporan Bank Sampah RW</h3>
                <p class="text-xs text-gray-500 mt-0.5">Ringkasan operasional dan data terkini.</p>
            </div>

            <!-- Kartu Ringkasan -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-[10px] text-gray-400 font-bold uppercase">Total Sampah</p>
                    <h4 class="text-xl font-bold text-emerald-600">{{ number_format($totalSampah, 1) }} Kg</h4>
                </div>
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-[10px] text-gray-400 font-bold uppercase">Total Transaksi</p>
                    <h4 class="text-xl font-bold text-indigo-600">{{ $totalTransaksi }} Kali</h4>
                </div>
                <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100">
                    <p class="text-[10px] text-gray-400 font-bold uppercase">Total Nasabah</p>
                    <h4 class="text-xl font-bold text-purple-600">{{ $totalNasabah }} Orang</h4>
                </div>
            </div>

            <!-- Grid Grafik & Rincian Jenis Sampah -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <!-- Grafik Batang (Statistik Berat Sampah Per Bulan) -->
                <div class="md:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h4 class="text-sm font-bold text-gray-800 mb-4">Statistik Berat Sampah Per Bulan</h4>
                    <div style="height: 250px;">
                        <canvas id="laporanChart"></canvas>
                    </div>
                </div>

                <!-- Rincian Jenis Sampah -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h4 class="text-sm font-bold text-gray-800 mb-4">Jenis Sampah</h4>
                    <div class="space-y-3">
                        @foreach($jenisSampah as $js)
                        <div class="flex justify-between text-xs">
                            <span class="text-gray-600">{{ $js->jenis_sampah }}</span>
                            <span class="font-bold text-gray-900">{{ number_format($js->berat, 1) }} Kg</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Grafik Pie (Total Uang Masuk Berdasarkan Metode Pencairan) -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h4 class="text-sm font-bold text-gray-800 mb-4">Grafik Total Uang Masuk Berdasarkan Metode Pencairan</h4>
                <div style="max-width: 400px; margin: auto;">
                    <canvas id="pieChartUang"></canvas>
                </div>
            </div>

            <!-- Tabel Rekap Data -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100">
                <div class="p-6">
                    <h4 class="text-sm font-bold text-gray-800 mb-4">Riwayat Rekap Data</h4>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200 text-left text-xs">
                            <thead class="bg-gray-50 text-gray-700 uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-3.5 font-semibold">Jenis Laporan</th>
                                    <th class="px-6 py-3.5 font-semibold">Tujuan</th>
                                    <th class="px-6 py-3.5 font-semibold">Tanggal</th>
                                    <th class="px-6 py-3.5 font-semibold text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white text-gray-600">
                                @forelse($laporan as $item)
                                <tr class="hover:bg-gray-50 transition">
                                    <td class="px-6 py-4 font-semibold text-gray-900">{{ $item->jenis_laporan }}</td>
                                    <td class="px-6 py-4">{{ $item->tujuan }}</td>
                                    <td class="px-6 py-4">{{ $item->created_at->format('d M Y, H:i') }}</td>
                                    <td class="px-6 py-4 text-center">
                                        <a href="{{ asset('storage/'.$item->file_path) }}" target="_blank" 
                                           class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-lg font-bold hover:bg-indigo-100 transition">
                                            Download
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-gray-400">Belum ada laporan yang diunggah.</td>
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
        // 1. Script untuk Grafik Batang (Berat Sampah per Bulan)
        const ctxBar = document.getElementById('laporanChart').getContext('2d');
        const laporanChart = new Chart(ctxBar, {
            type: 'bar', // Bisa diubah jadi 'line' jika ingin grafik garis
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
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // 2. Script untuk Grafik Pie (Total Uang Masuk Berdasarkan Metode Pencairan)
        const ctxPie = document.getElementById('pieChartUang').getContext('2d');
        const pieChartUang = new Chart(ctxPie, {
            type: 'pie',
            data: {
                labels: {!! json_encode($pieChartData->pluck('metode_pencairan')) !!},
                datasets: [{
                    label: 'Total Uang',
                    data: {!! json_encode($pieChartData->pluck('total_uang')) !!},
                    backgroundColor: [
                        'rgba(255, 99, 132, 0.7)',
                        'rgba(54, 162, 235, 0.7)',
                        'rgba(255, 206, 86, 0.7)',
                        'rgba(75, 192, 192, 0.7)'
                    ],
                    borderColor: [
                        'rgba(255, 99, 132, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                    }
                }
            }
        });
    </script>
</x-app-layout>