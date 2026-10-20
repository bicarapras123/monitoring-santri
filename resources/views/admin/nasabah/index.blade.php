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
            <div class="flex flex-col md:flex-row items-start md:items-center justify-between mb-4 gap-3">
                <div>
                    <h2 class="text-base font-bold text-gray-800">Tabel Nasabah Terdaftar</h2>
                    <span class="inline-block mt-1 text-xs bg-indigo-50 text-indigo-700 font-semibold px-2.5 py-1 rounded-lg">
                        Total: {{ $nasabahs->count() }} Orang
                    </span>
                </div>

                <!-- TOMBOL EXPORT EXCEL -->
                <button type="button" id="btn-export-excel-nasabah" class="inline-flex items-center gap-1.5 bg-emerald-600 text-white text-xs px-4 py-2 rounded-xl hover:bg-emerald-700 transition shadow-sm cursor-pointer">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                    </svg>
                    Export Excel
                </button>
            </div>

            <div class="overflow-x-auto">
                <table id="tabel-nasabah" class="w-full text-left border-collapse">
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
                        @forelse ($nasabahs as $index =>$nasabah)
                            <tr class="hover:bg-gray-50/50 transition nasabah-row">
                                <td class="py-3 px-3 text-gray-500 font-medium">{{ $index + 1 }}</td>
                                <td class="py-3 px-3">
                                    <div class="font-bold text-gray-800 nasabah-nama">{{ $nasabah->nama_lengkap }}</div>
                                    <div class="text-[11px] text-indigo-600 font-medium mt-0.5 nasabah-nik">NIK: {{ $nasabah->nik }}</div>
                                </td>
                                <td class="py-3 px-3">
                                    <div class="text-gray-800 font-medium nasabah-telp">{{ $nasabah->nomor_telepon }}</div>
                                    <div class="text-[11px] text-gray-400 truncate max-w-xs mt-0.5 nasabah-alamat" title="{{ $nasabah->alamat_lengkap }}">
                                        {{ $nasabah->alamat_lengkap }}
                                    </div>
                                </td>
                                <td class="py-3 px-3">
                                    <span class="font-medium text-gray-700 nasabah-ortu">{{ $nasabah->nama_orang_tua }}</span>
                                </td>
                                <td class="py-3 px-3">
                                    @if($nasabah->rekening)
                                        <div class="inline-flex flex-col">
                                            <span class="px-2.5 py-0.5 bg-emerald-50 text-emerald-700 rounded-lg text-[11px] font-semibold w-fit nasabah-ewallet">
                                                {{ $nasabah->rekening->jenis_ewallet }}
                                            </span>
                                            <span class="text-[11px] text-gray-500 mt-0.5 font-mono nasabah-norek">{{ $nasabah->rekening->nomor_rekening }}</span>
                                        </div>
                                    @else
                                        <span class="px-2.5 py-0.5 bg-amber-50 text-amber-700 rounded-lg text-[11px] font-semibold nasabah-ewallet">
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

    <!-- Script JavaScript untuk Export Excel Data Nasabah -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btnExport = document.getElementById('btn-export-excel-nasabah');
            if (btnExport) {
                btnExport.addEventListener('click', function () {
                    let dataType = 'application/vnd.ms-excel';
                    
                    let excelHTML = `
                        <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
                        <head><meta charset="utf-8"></head>
                        <body>
                            <h2 style="text-align:center;">LAPORAN DATA NASABAH BANK SAMPAH RW.04</h2>
                            <p style="text-align:center;">Tanggal Export: ${new Date().toLocaleString('id-ID')}</p>
                            <br>
                            <table border="1">
                                <tr style="background-color: #f2f2f2; font-weight: bold;">
                                    <th>No</th>
                                    <th>NIK</th>
                                    <th>Nama Lengkap</th>
                                    <th>No. Telepon</th>
                                    <th>Alamat Lengkap</th>
                                    <th>Nama Orang Tua</th>
                                    <th>Jenis E-Wallet</th>
                                    <th>Nomor Rekening / E-Wallet</th>
                                </tr>
                    `;

                    const rows = document.querySelectorAll('.nasabah-row');
                    rows.forEach((row, idx) => {
                        const no = idx + 1;
                        const nama = row.querySelector('.nasabah-nama')?.innerText.trim() || '-';
                        const nikRaw = row.querySelector('.nasabah-nik')?.innerText.trim() || '';
                        const nik = nikRaw.replace('NIK:', '').trim();
                        const telp = row.querySelector('.nasabah-telp')?.innerText.trim() || '-';
                        const alamat = row.querySelector('.nasabah-alamat')?.innerText.trim() || '-';
                        const ortu = row.querySelector('.nasabah-ortu')?.innerText.trim() || '-';
                        
                        const ewalletEl = row.querySelectorAll('.nasabah-ewallet');
                        const jenisEwallet = ewalletEl[0]?.innerText.trim() || 'Belum Set Rekening';
                        const norek = row.querySelector('.nasabah-norek')?.innerText.trim() || '-';

                        excelHTML += `
                            <tr>
                                <td>${no}</td>
                                <td>'${nik}</td>
                                <td>${nama}</td>
                                <td>'${telp}</td>
                                <td>${alamat}</td>
                                <td>${ortu}</td>
                                <td>${jenisEwallet}</td>
                                <td>'${norek}</td>
                            </tr>
                        `;
                    });

                    excelHTML += `</table></body></html>`;

                    let blob = new Blob(['\ufeff', excelHTML], { type: dataType });
                    let filename = 'laporan-data-nasabah.xls';
                    
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