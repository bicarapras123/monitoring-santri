<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Card Form Upload -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-6 gap-4 border-b border-gray-100 pb-5">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Pengajuan Cardless (Pencairan Dana Cash)</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Upload dokumen PDF Formulir Pengajuan Pencairan Dana Cash untuk otomatis mengekstrak data diri pemohon dan memotong saldo.</p>
                        </div>
                    </div>

                    @if(session('success'))
                        <div class="mb-6 p-4 bg-emerald-50 text-emerald-700 rounded-xl text-xs font-medium border border-emerald-100 flex items-center gap-2">
                            <span>{{ session('success') }}</span>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="mb-6 p-4 bg-red-50 text-red-600 rounded-xl text-xs font-medium border border-red-100 flex items-center gap-2">
                            <span>{{ $errors->first() }}</span>
                        </div>
                    @endif

                    <!-- Form Upload dengan Input Nominal Saldo di Sampingnya -->
                    <form action="{{ route('admin.transaksi.cardless.parse') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                        @csrf
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-5 max-w-3xl">
                            <!-- Input Nominal Saldo -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-2">Jumlah Saldo Cardless (Rp) <span class="text-red-500">*</span></label>
                                <input type="number" name="jumlah_cardless" value="{{ old('jumlah_cardless') }}" placeholder="Contoh: 50000" required
                                    class="block w-full text-xs border-gray-300 rounded-xl focus:border-indigo-500 focus:ring-indigo-500">
                                <p class="text-[11px] text-gray-400 mt-1.5">Saldo akan langsung divalidasi dan dikurangi.</p>
                            </div>

                            <!-- File PDF -->
                            <div>
                                <label class="block text-xs font-semibold text-gray-700 mb-2">Pilih File PDF Formulir <span class="text-red-500">*</span></label>
                                <input type="file" name="form_pdf" accept="application/pdf" required
                                    class="block w-full text-xs text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 transition border border-gray-200 rounded-xl cursor-pointer bg-gray-50/50">
                                <p class="text-[11px] text-gray-400 mt-1.5">Maksimal ukuran file: 2MB.</p>
                            </div>
                        </div>

                        <div class="pt-2">
                            <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-indigo-600 text-white rounded-xl text-xs font-semibold shadow-sm hover:bg-indigo-700 transition">
                                Proses Cardless & Potong Saldo
                            </button>
                        </div>
                    </form>

                </div>
            </div>

            <!-- Tabel Data Diri Pengajuan Cardless -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <div class="flex flex-col md:flex-row justify-between items-center mb-4 gap-4">
                        <div>
                            <h3 class="text-base font-bold text-gray-800">Daftar Data Diri Pemohon Cardless</h3>
                            <span class="inline-block mt-1 px-2.5 py-1 bg-indigo-50 text-indigo-700 text-xs font-semibold rounded-full">
                                {{ isset($cardlessList) ? count($cardlessList) : 0 }} Pengajuan Tercatat
                            </span>
                        </div>

                        <!-- TOMBOL EXPORT EXCEL -->
                        <button type="button" id="btn-export-excel-cardless" class="inline-flex items-center gap-1.5 bg-emerald-600 text-white text-xs px-4 py-2 rounded-xl hover:bg-emerald-700 transition shadow-sm cursor-pointer">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Export Excel
                        </button>
                    </div>

                    <div class="overflow-x-auto border border-gray-100 rounded-2xl">
                        <table id="tabel-cardless" class="min-w-full divide-y divide-gray-200 text-left text-xs">
                            <thead class="bg-gray-50 text-gray-700 uppercase tracking-wider">
                                <tr>
                                    <th class="px-4 py-3.5 font-semibold w-12">No</th>
                                    <th class="px-4 py-3.5 font-semibold">NIK</th>
                                    <th class="px-4 py-3.5 font-semibold">Nama Lengkap</th>
                                    <th class="px-4 py-3.5 font-semibold">Jumlah Cardless</th>
                                    <th class="px-4 py-3.5 font-semibold">Jenis Kelamin</th>
                                    <th class="px-4 py-3.5 font-semibold">Tempat, Tgl Lahir</th>
                                    <th class="px-4 py-3.5 font-semibold">No. Telepon</th>
                                    <th class="px-4 py-3.5 font-semibold">Nama Orang Tua</th>
                                    <th class="px-4 py-3.5 font-semibold">Alamat Lengkap</th>
                                    <th class="px-4 py-3.5 font-semibold text-center exclude-excel">PDF</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white text-gray-600">
                                @forelse($cardlessList ?? [] as $index =>$item)
                                <tr class="hover:bg-gray-50/50 transition cardless-row">
                                    <td class="px-4 py-4 whitespace-nowrap text-gray-400 font-medium">{{ $index + 1 }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap font-mono text-gray-700">{{ $item->nik }}</td>
                                    <td class="px-4 py-4 font-semibold text-gray-900 whitespace-nowrap">{{ $item->nama_lengkap }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap font-bold text-emerald-600">Rp {{ number_format($item->jumlah_cardless, 0, ',', '.') }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap">{{ $item->jenis_kelamin }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap">{{ $item->tempat_tgl_lahir }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap">{{ $item->nomor_telepon }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap">{{ $item->nama_orang_tua }}</td>
                                    <td class="px-4 py-4 max-w-xs truncate" title="{{ $item->alamat_lengkap }}">{{ $item->alamat_lengkap }}</td>
                                    <td class="px-4 py-4 whitespace-nowrap text-center exclude-excel">
                                        @if($item->file_path)
                                            <a href="{{ asset('storage/' . $item->file_path) }}" target="_blank" 
                                                class="inline-flex items-center gap-1 px-2.5 py-1 bg-indigo-50 text-indigo-700 rounded-lg hover:bg-indigo-600 hover:text-white transition font-medium">
                                                Lihat
                                            </a>
                                        @else
                                            <span class="text-gray-400">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="10" class="px-6 py-10 text-center text-gray-400">Belum ada data pengajuan cardless dari formulir.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>

        </div>
    </div>

    <!-- Script JavaScript untuk Export Excel Cardless -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btnExport = document.getElementById('btn-export-excel-cardless');
            if (btnExport) {
                btnExport.addEventListener('click', function () {
                    let dataType = 'application/vnd.ms-excel';
                    
                    let excelHTML = `
                        <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
                        <head><meta charset="utf-8"></head>
                        <body>
                            <h2 style="text-align:center;">LAPORAN DATA PENGAJUAN CARDLESS (PENCAIRAN DANA CASH)</h2>
                            <p style="text-align:center;">Tanggal Export: ${new Date().toLocaleString('id-ID')}</p>
                            <br>
                            <table border="1">
                                <tr style="background-color: #f2f2f2; font-weight: bold;">
                                    <th>No</th>
                                    <th>NIK</th>
                                    <th>Nama Lengkap</th>
                                    <th>Jumlah Cardless</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Tempat, Tgl Lahir</th>
                                    <th>No. Telepon</th>
                                    <th>Nama Orang Tua</th>
                                    <th>Alamat Lengkap</th>
                                </tr>
                    `;

                    const rows = document.querySelectorAll('.cardless-row');
                    rows.forEach((row) => {
                        const cols = row.querySelectorAll('td');
                        const no = cols[0].innerText.trim();
                        const nik = cols[1].innerText.trim();
                        const nama = cols[2].innerText.trim();
                        const jumlah = cols[3].innerText.trim();
                        const jk = cols[4].innerText.trim();
                        const ttl = cols[5].innerText.trim();
                        const telp = cols[6].innerText.trim();
                        const ortu = cols[7].innerText.trim();
                        const alamat = cols[8].innerText.trim();

                        excelHTML += `
                            <tr>
                                <td>${no}</td>
                                <td>'${nik}</td>
                                <td>${nama}</td>
                                <td>${jumlah}</td>
                                <td>${jk}</td>
                                <td>${ttl}</td>
                                <td>'${telp}</td>
                                <td>${ortu}</td>
                                <td>${alamat}</td>
                            </tr>
                        `;
                    });

                    excelHTML += `</table></body></html>`;

                    let blob = new Blob(['\ufeff', excelHTML], { type: dataType });
                    let filename = 'laporan-pengajuan-cardless.xls';
                    
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