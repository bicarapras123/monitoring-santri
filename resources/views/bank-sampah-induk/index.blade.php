<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Header Halaman -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 flex justify-between items-center">
                <div>
                    <h3 class="text-lg font-bold text-gray-800">Dashboard Pusat - Bank Sampah Induk</h3>
                    <p class="text-xs text-gray-500 mt-0.5">Rekapitulasi terpusat dan menyeluruh dari data laporan warga RW, setoran sampah, dan dokumen eksternal.</p>
                </div>
            </div>

            <!-- Grafik Diagram & Kotak Teks Evaluasi -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                <div class="lg:col-span-2 bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h4 class="text-sm font-bold text-gray-800 mb-4">Statistik Keseluruhan Total Berat Setoran Sampah</h4>
                    <div class="relative h-64">
                        <canvas id="bsiChart"></canvas>
                    </div>
                </div>
                <div class="bg-purple-50 border border-purple-100 p-6 rounded-2xl shadow-sm flex flex-col justify-between">
                    <div>
                        <div class="flex items-center gap-2 mb-3">
                            <span class="p-2 bg-purple-600 text-white rounded-xl shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </span>
                            <h4 class="text-xs font-bold text-purple-900 uppercase tracking-wider">Bahan Evaluasi & Analisis</h4>
                        </div>
                        <p class="text-xs text-purple-800 leading-relaxed mb-3">
                            Berdasarkan rekapitulasi grafik di samping, akumulasi berat setoran dari berbagai jenis material menunjukkan tren volume harian.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Tabel Rekapitulasi dengan Fitur Filter -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-2xl border border-gray-100 p-6">
                <div class="mb-6 flex justify-between items-center">
                    <div>
                        <h4 class="text-sm font-bold text-gray-800">Tabel Rekapitulasi Keseluruhan Data Sistem</h4>
                        <p class="text-xs text-gray-500 mt-0.5">Filter data berdasarkan kategori:</p>
                    </div>
                    <!-- Tombol Filter -->
                    <div class="flex gap-2">
                        <button onclick="filterTable('Semua')" class="px-4 py-2 bg-gray-800 text-white rounded-lg text-[10px] font-bold uppercase shadow-sm">Semua</button>
                        <button onclick="filterTable('Laporan Warga RW')" class="px-4 py-2 bg-emerald-600 text-white rounded-lg text-[10px] font-bold uppercase shadow-sm">RW</button>
                        <button onclick="filterTable('Setoran Sampah')" class="px-4 py-2 bg-blue-600 text-white rounded-lg text-[10px] font-bold uppercase shadow-sm">Setoran</button>
                        <button onclick="filterTable('Laporan Eksternal')" class="px-4 py-2 bg-amber-600 text-white rounded-lg text-[10px] font-bold uppercase shadow-sm">Eksternal</button>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 text-left text-xs" id="dataTable">
                        <thead class="bg-gray-50 text-gray-700 uppercase tracking-wider">
                            <tr>
                                <th class="px-4 py-3 font-semibold">Kategori Sumber</th>
                                <th class="px-4 py-3 font-semibold">Identitas / Subjek</th>
                                <th class="px-4 py-3 font-semibold">Detail Informasi & Keterangan</th>
                                <th class="px-4 py-3 font-semibold text-center">Waktu Real</th>
                                <th class="px-4 py-3 font-semibold text-center">Lampiran</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200 bg-white text-gray-600">
                            <!-- Loop Data -->
                            @foreach($laporansRw as $rw)
                            <tr class="data-row hover:bg-gray-50" data-category="Laporan Warga RW">
                                <td class="px-4 py-3"><span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 font-semibold rounded-full border border-emerald-200">Laporan Warga RW</span></td>
                                <td class="px-4 py-3"><div class="font-bold text-gray-900">{{ $rw->nama_lengkap }}</div><div class="text-[11px] text-gray-400">NIK: {{ $rw->nik }}</div></td>
                                <td class="px-4 py-3">Kel. {{ $rw->kelurahan }}, Kec. {{ $rw->kecamatan }}</td>
                                <td class="px-4 py-3 text-center text-[11px] text-gray-500">{{ $rw->created_at->format('d M Y, H:i') }}</td>
                                <td class="px-4 py-3 text-center"><a href="{{ Storage::url($rw->file_upload ?? $rw->file_path) }}" class="text-purple-600 font-bold hover:underline">File</a></td>
                            </tr>
                            @endforeach

                            @foreach($setorans as $setoran)
                            <tr class="data-row hover:bg-gray-50" data-category="Setoran Sampah">
                                <td class="px-4 py-3"><span class="px-2.5 py-1 bg-blue-50 text-blue-700 font-semibold rounded-full border border-blue-200">Setoran Sampah</span></td>
                                <td class="px-4 py-3"><div class="font-bold text-gray-900">{{ $setoran->jenis_sampah }}</div></td>
                                <td class="px-4 py-3">Total: <span class="font-bold text-blue-600">{{ $setoran->total_berat }} kg</span></td>
                                <td class="px-4 py-3 text-center text-[11px] text-gray-500">{{ \Carbon\Carbon::parse($setoran->created_at)->format('d M Y, H:i') }}</td>
                                <td class="px-4 py-3 text-center"><a href="{{ Storage::url($setoran->upload_image) }}" class="text-purple-600 font-bold hover:underline">Foto</a></td>
                            </tr>
                            @endforeach

                            @foreach($laporansEksternal as $eksternal)
                            <tr class="data-row hover:bg-gray-50" data-category="Laporan Eksternal">
                                <td class="px-4 py-3"><span class="px-2.5 py-1 bg-amber-50 text-amber-700 font-semibold rounded-full border border-amber-200">Laporan Eksternal</span></td>
                                <td class="px-4 py-3"><div class="font-bold text-gray-900">Dokumen #{{ $eksternal->id }}</div></td>
                                <td class="px-4 py-3">Laporan Resmi Pihak Luar</td>
                                <td class="px-4 py-3 text-center text-[11px] text-gray-500">{{ $eksternal->created_at->format('d M Y, H:i') }}</td>
                                <td class="px-4 py-3 text-center"><a href="{{ Storage::url($eksternal->file_path) }}" class="text-purple-600 font-bold hover:underline">Download</a></td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        function filterTable(category) {
            document.querySelectorAll('.data-row').forEach(row => {
                row.style.display = (category === 'Semua' || row.dataset.category === category) ? '' : 'none';
            });
        }

        const ctx = document.getElementById('bsiChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: {!! json_encode($chartSetorans->pluck('jenis_sampah')) !!},
                datasets: [{ label: 'Berat (kg)', data: {!! json_encode($chartSetorans->pluck('total_berat')) !!}, backgroundColor: '#8b5cf6' }]
            }
        });
    </script>
</x-app-layout>