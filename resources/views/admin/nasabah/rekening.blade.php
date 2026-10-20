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
            <div class="p-6 border-b border-gray-100 flex flex-col md:flex-row items-start md:items-center justify-between gap-3">
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Tabel Rekening Nasabah</h2>
                    <span class="inline-block mt-1 text-xs bg-emerald-50 text-emerald-700 px-3 py-1 rounded-full font-semibold">
                        Total: {{ $nasabahs->count() }} Orang
                    </span>
                </div>

                <!-- TOMBOL EXPORT EXCEL -->
                <button type="button" id="btn-export-excel-rekening" class="inline-flex items-center gap-1.5 bg-emerald-600 text-white text-xs px-4 py-2 rounded-xl hover:bg-emerald-700 transition shadow-sm cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export Excel
                </button>
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
                        @forelse ($nasabahs as $index =>$nasabah)
                            @php
                                $ktpUrl = ($nasabah->rekening && $nasabah->rekening->foto_ktp) ? asset('storage/' . $nasabah->rekening->foto_ktp) : '';
                            @endphp
                            <tr class="hover:bg-gray-50/50 transition rekening-row" data-ktp="{{ $ktpUrl }}">
                                <td class="py-4 px-6 font-medium text-gray-500">
                                    {{ $index + 1 }}
                                </td>
                                <td class="py-4 px-6">
                                    <div class="font-bold text-gray-800 rekening-nama">{{ $nasabah->nama_lengkap }}</div>
                                    <div class="text-xs text-indigo-600 font-medium mt-0.5 rekening-nik">NIK: {{ $nasabah->nik }}</div>
                                </td>
                                <td class="py-4 px-6">
                                    <span class="px-2.5 py-1 bg-emerald-50 text-emerald-700 rounded-lg text-xs font-semibold rekening-ewallet">
                                        {{ $nasabah->rekening->jenis_ewallet ?? '-' }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 font-mono font-medium text-gray-800 rekening-norek">
                                    {{ $nasabah->rekening->nomor_rekening ?? '-' }}
                                </td>
                                <td class="py-4 px-6 rekening-ktp">
                                    @if($nasabah->rekening &&$nasabah->rekening->foto_ktp)
                                        <a href="{{ $ktpUrl }}" target="_blank" class="text-indigo-600 underline text-xs font-semibold">
                                            Lihat KTP
                                        </a>
                                    @else
                                        <span class="text-gray-400 text-xs">Tidak ada</span>
                                    @endif
                                </td>
                                <td class="py-4 px-6">
                                    @php
                                        $statusRekening = ($nasabah->rekening &&$nasabah->rekening->status == 'verified') ? 'Terverifikasi' : 'Pending';
                                    @endphp
                                    <span class="rekening-status px-2.5 py-1 {{ $statusRekening == 'Terverifikasi' ? 'bg-green-100 text-green-700' : 'bg-amber-100 text-amber-700' }} rounded-full text-xs font-bold">
                                        {{ $statusRekening }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-center rekening-aksi">
                                    @if($nasabah->rekening &&$nasabah->rekening->status != 'verified')
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

    <!-- Script JavaScript untuk Export Excel Rekening Nasabah dengan Link KTP Aktif -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btnExport = document.getElementById('btn-export-excel-rekening');
            if (btnExport) {
                btnExport.addEventListener('click', function () {
                    let dataType = 'application/vnd.ms-excel';
                    
                    let excelHTML = `
                        <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
                        <head><meta charset="utf-8"></head>
                        <body>
                            <h2 style="text-align:center;">LAPORAN DATA PENGAJUAN REKENING & E-WALLET NASABAH</h2>
                            <p style="text-align:center;">Tanggal Export: ${new Date().toLocaleString('id-ID')}</p>
                            <br>
                            <table border="1">
                                <tr style="background-color: #f2f2f2; font-weight: bold;">
                                    <th>No</th>
                                    <th>Nama Nasabah</th>
                                    <th>NIK</th>
                                    <th>Jenis E-Wallet</th>
                                    <th>Nomor Rekening</th>
                                    <th>Foto KTP</th>
                                    <th>Status Verifikasi</th>
                                    <th>Keterangan Aksi</th>
                                </tr>
                    `;

                    const rows = document.querySelectorAll('.rekening-row');
                    rows.forEach((row, idx) => {
                        const no = idx + 1;
                        const nama = row.querySelector('.rekening-nama')?.innerText.trim() || '-';
                        const nikRaw = row.querySelector('.rekening-nik')?.innerText.trim() || '';
                        const nik = nikRaw.replace('NIK:', '').trim();
                        const jenisEwallet = row.querySelector('.rekening-ewallet')?.innerText.trim() || '-';
                        const norek = row.querySelector('.rekening-norek')?.innerText.trim() || '-';
                        const ktpUrl = row.getAttribute('data-ktp') || '';
                        const status = row.querySelector('.rekening-status')?.innerText.trim() || '-';
                        const aksiText = row.querySelector('.rekening-aksi')?.innerText.trim() || '-';

                        // Jika ada link KTP, buat formula HYPERLINK Excel agar bisa diklik langsung
                        let ktpCellContent = 'Tidak ada';
                        if (ktpUrl) {
                            ktpCellContent = `=HYPERLINK("${ktpUrl}", "Lihat KTP")`;
                        }

                        excelHTML += `
                            <tr>
                                <td>${no}</td>
                                <td>${nama}</td>
                                <td>'${nik}</td>
                                <td>${jenisEwallet}</td>
                                <td>'${norek}</td>
                                <td>${ktpCellContent}</td>
                                <td>${status}</td>
                                <td>${aksiText}</td>
                            </tr>
                        `;
                    });

                    excelHTML += `</table></body></html>`;

                    let blob = new Blob(['\ufeff', excelHTML], { type: dataType });
                    let filename = 'laporan-pengajuan-rekening-nasabah-lengkap.xls';
                    
                    let downloadLink = document.createElement("a");
                    downloadLink.href = URL.createObjectURL(blob);
                    downloadLink.download = filename;
                    document.body.appendChild(downloadLink);
                    downloadLink.click();
                    document.body.removeChild(downloadLink);
                });
            }
        });
    </script>
</x-app-layout>