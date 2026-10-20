<x-app-layout>
    <div class="py-12" x-data="{ 
        openModal: false, 
        selectedPenarikan: {}, 
        buktiUrl: '', 
        currentTime: '',
        initClock() {
            const updateTime = () => {
                const now = new Date();
                this.currentTime = now.toLocaleString('id-ID', {
                    timeZone: 'Asia/Jakarta',
                    day: '2-digit',
                    month: 'short',
                    year: 'numeric',
                    hour: '2-digit',
                    minute: '2-digit',
                    second: '2-digit',
                    hour12: false
                }).replace(/\./g, ':') + ' WIB';
            };
            updateTime();
            setInterval(updateTime, 1000);
        }
    }" x-init="initClock()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    
                    <!-- Header & Informasi -->
                    <div class="flex flex-col md:flex-row justify-between items-center mb-6 gap-4">
                        <div>
                            <h3 class="text-lg font-bold text-gray-800">Kelola Pengajuan Saldo</h3>
                            <p class="text-xs text-gray-500">Daftar pengajuan penarikan saldo dari nasabah beserta verifikasi bukti pencairannya.</p>
                        </div>
                        
                        <div class="flex items-center gap-3">
                            <!-- TOMBOL EXPORT EXCEL -->
                            <button type="button" id="btn-export-excel-penarikan" class="inline-flex items-center gap-1.5 bg-emerald-600 text-white text-xs px-4 py-2 rounded-xl hover:bg-emerald-700 transition shadow-sm cursor-pointer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                Export Excel
                            </button>

                            <!-- Notifikasi Sukses -->
                            @if(session('success'))
                                <div class="bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-2 rounded-xl text-xs font-semibold">
                                    {{ session('success') }}
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Tabel Data Pengajuan Saldo -->
                    <div class="overflow-x-auto border border-gray-100 rounded-2xl">
                        <table id="tabel-penarikan" class="min-w-full divide-y divide-gray-200 text-left text-xs">
                            <thead class="bg-gray-50 text-gray-700 uppercase tracking-wider">
                                <tr>
                                    <th class="px-6 py-3.5 font-semibold">No</th>
                                    <th class="px-6 py-3.5 font-semibold">Nama Nasabah</th>
                                    <th class="px-6 py-3.5 font-semibold">Jumlah Penarikan</th>
                                    <th class="px-6 py-3.5 font-semibold">Jenis Bank</th>
                                    <th class="px-6 py-3.5 font-semibold text-center">Status Rekening</th>
                                    <th class="px-6 py-3.5 font-semibold">Tanggal Pengajuan</th>
                                    <th class="px-6 py-3.5 font-semibold text-center exclude-excel">Bukti Pencairan</th>
                                    <th class="px-6 py-3.5 font-semibold text-center exclude-excel">Aksi</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-200 bg-white text-gray-600">
                                @forelse($penarikans as $index =>$item)
                                @php
                                    $nasabahRelasi =$item->user->nasabah ?? null;
                                    $rekeningModel = $nasabahRelasi ? \App\Models\RekeningNasabah::where('nasabah_id', $nasabahRelasi->id)->first() : null;
                                    $namaNasabahItem =$nasabahRelasi ? $nasabahRelasi->nama_lengkap : ($item->user->name ?? '-');
                                    $totalBeratItem  = \App\Models\SetoranSampah::where('nama_lengkap', $namaNasabahItem)->sum('total_berat');$alamatItem      = $nasabahRelasi ? ($nasabahRelasi->alamat_lengkap ?? '-') : '-';
                                    
                                    $nomorRekeningFinal = $item->nomor_rekening ?? ($rekeningModel ? $rekeningModel->nomor_rekening : '-');$jenisEwalletFinal  = $item->jenis_ewallet ?? ($rekeningModel ? $rekeningModel->jenis_ewallet : ($item->metode_pencairan ?? 'Tunai'));
                                    $teleponItem =$nomorRekeningFinal;
                                    
                                    $itemArray =$item->toArray();
                                    $itemArray['nama_lengkap']    =$namaNasabahItem;
                                    $itemArray['email']           =$item->user->email ?? '-';
                                    $itemArray['nomor_telephone'] =$teleponItem;
                                    $itemArray['alamat_lengkap']  =$alamatItem;
                                    $itemArray['nomor_rekening']  =$nomorRekeningFinal;
                                    $itemArray['jenis_ewallet']   =$jenisEwalletFinal;
                                    $itemArray['total_berat']     =$totalBeratItem;
                                @endphp
                                <tr class="hover:bg-gray-50/50 transition penarikan-row" data-detail="{{ json_encode($itemArray) }}">
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500">{{ $loop->iteration }}</td>
                                    
                                    <td class="px-6 py-4 font-semibold text-gray-900 text-sm whitespace-nowrap">
                                        {{ $namaNasabahItem }}
                                    </td>
                                    <!-- Mengambil data dari kolom jumlah_penarikan -->
                                    <td class="px-6 py-4 whitespace-nowrap font-bold text-indigo-600 text-sm">
                                        Rp {{ number_format($item->jumlah_penarikan ?? 0, 0, ',', '.') }}
                                    </td>
                                    
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-0.5 bg-gray-100 text-gray-600 rounded text-[10px] font-medium">
                                            {{ $jenisEwalletFinal }}
                                        </span>
                                    </td>
                                    <!-- Status -->
                                    <td class="px-6 py-4 whitespace-nowrap text-center relative">
                                        @php
                                            $status = strtolower($item->status ?? 'pending');
                                            $statusClass = 'bg-gray-100 text-gray-700 border-gray-200';
                                            if($status == 'pending') {$statusClass = 'bg-amber-100 text-amber-700 border-amber-200';
                                            } elseif($status == 'disetujui') {$statusClass = 'bg-emerald-100 text-emerald-700 border-emerald-200';
                                            } elseif($status == 'ditolak') {$statusClass = 'bg-red-100 text-red-700 border-red-200';
                                            }
                                        @endphp
                                        
                                        <div class="inline-block relative" x-data="{ dropdownStatusTable: false }">
                                            <button @click="dropdownStatusTable = !dropdownStatusTable" type="button" class="inline-flex items-center gap-1.5 px-2.5 py-1 text-xs font-semibold rounded-full transition shadow-sm border {{ $statusClass }}">
                                                <span>{{ ucfirst($status) }}</span>
                                                <svg class="w-3.5 h-3.5 transition-transform" :class="{ 'rotate-180': dropdownStatusTable }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                                                </svg>
                                            </button>
                                            <!-- Form/Dropdown Pilihan Status di Tabel -->
                                            <div x-show="dropdownStatusTable" @click.away="dropdownStatusTable = false" style="display: none;" class="absolute left-1/2 -translate-x-1/2 mt-1 w-32 bg-white rounded-xl shadow-lg border border-gray-100 py-1 z-50 text-left">
                                                <form action="{{ route('admin.transaksi.update-status', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="pending">
                                                    <button type="submit" class="w-full px-3 py-1.5 text-xs text-amber-700 hover:bg-amber-50 font-medium text-left">Pending</button>
                                                </form>
                                                <form action="{{ route('admin.transaksi.update-status', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="disetujui">
                                                    <button type="submit" class="w-full px-3 py-1.5 text-xs text-emerald-700 hover:bg-emerald-50 font-medium text-left">Disetujui</button>
                                                </form>
                                                <form action="{{ route('admin.transaksi.update-status', $item->id) }}" method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <input type="hidden" name="status" value="ditolak">
                                                    <button type="submit" class="w-full px-3 py-1.5 text-xs text-red-700 hover:bg-red-50 font-medium text-left">Ditolak</button>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                    <!-- Tanggal Pengajuan Realtime (WIB) -->
                                    <td class="px-6 py-4 whitespace-nowrap text-gray-500 font-medium" x-text="currentTime">
                                        {{ $item->created_at ? $item->created_at->format('d M Y, H:i') : '-' }}
                                    </td>
                                    <!-- Kolom Upload & Lihat Bukti Pencairan -->
                                    <td class="px-6 py-4 whitespace-nowrap text-center exclude-excel">
                                        @if($item->bukti_pencairan)
                                            <div class="flex items-center justify-center gap-2 mb-2">
                                                <a href="{{ asset('uploads/bukti_pencairan/' . $item->bukti_pencairan) }}" target="_blank" class="text-indigo-600 hover:underline font-medium text-xs flex items-center gap-1">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Lihat Bukti
                                                </a>
                                            </div>
                                        @else
                                            <span class="text-gray-400 text-[11px] block mb-1">Belum diupload</span>
                                        @endif
                                        <!-- Form Upload / Ganti Bukti -->
                                        <form action="{{ route('admin.transaksi.upload-bukti', $item->id) }}" method="POST" enctype="multipart/form-data" class="flex items-center justify-center gap-1">
                                            @csrf
                                            <input type="file" name="bukti_pencairan" class="text-[10px] w-32 text-gray-500 file:mr-1 file:py-1 file:px-2 file:rounded-lg file:border-0 file:text-[10px] file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 cursor-pointer" required>
                                            <button type="submit" class="px-2.5 py-1 bg-indigo-600 text-white rounded-lg text-[10px] hover:bg-indigo-700 transition font-medium">Upload</button>
                                        </form>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-center exclude-excel">
                                        <button @click="openModal = true; selectedPenarikan = {{ json_encode($itemArray) }}" 
                                            class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 text-indigo-700 rounded-xl hover:bg-indigo-600 hover:text-white transition font-medium shadow-sm">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                            Detail
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="8" class="px-6 py-10 text-center text-gray-400">Belum ada data pengajuan saldo.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- MODAL POP-UP DETAIL LENGKAP PENARIKAN -->
        <div x-show="openModal" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
            <div @click.away="openModal = false" class="bg-white rounded-2xl max-w-xl w-full p-6 shadow-2xl overflow-hidden transform transition-all">
                
                <!-- Area yang akan diprint -->
                <div id="printable-area">
                    <!-- Header Modal -->
                    <div class="flex justify-between items-center border-b border-gray-100 pb-4 mb-4">
                        <div>
                            <h3 class="text-base font-bold text-gray-900">Rincian Pengajuan Saldo</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Informasi lengkap data penarikan nasabah (Barang Bukti)</p>
                        </div>
                    </div>
                    <!-- Konten Detail -->
                    <div class="space-y-3 text-xs text-gray-700">
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <span class="text-gray-400">Waktu Cetak / Cetak Data</span>
                            <span class="font-semibold text-indigo-600" x-text="currentTime"></span>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <span class="text-gray-400">Nama Lengkap</span>
                            <span class="font-semibold text-gray-900" x-text="selectedPenarikan.nama_lengkap ?? '-'"></span>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <span class="text-gray-400">Email</span>
                            <span class="font-medium text-indigo-600" x-text="selectedPenarikan.email ?? '-'"></span>
                        </div>
                        
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <span class="text-gray-400">Metode Pencairan</span>
                            <span class="font-bold text-indigo-700 bg-indigo-50 px-2 py-0.5 rounded" x-text="selectedPenarikan.metode_pencairan ?? '-'"></span>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <span class="text-gray-400">No. Telepon</span>
                            <span class="font-medium text-gray-800" x-text="selectedPenarikan.nomor_telephone ?? '-'"></span>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <span class="text-gray-400">Alamat Lengkap</span>
                            <span class="font-medium text-gray-800 text-right max-w-xs truncate" x-text="selectedPenarikan.alamat_lengkap ?? '-'"></span>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <span class="text-gray-400">Nomor Rekening / E-Wallet</span>
                            <span class="font-semibold text-gray-800" x-text="selectedPenarikan.nomor_rekening ?? '-'"></span>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <span class="text-gray-400">Jenis E-Wallet / Bank</span>
                            <span class="font-medium text-gray-800" x-text="selectedPenarikan.jenis_ewallet ?? 'Tunai'"></span>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <span class="text-gray-400">Total Berat Sampah</span>
                            <span class="font-semibold text-gray-800" x-text="(selectedPenarikan.total_berat ?? '0') + ' Kg'"></span>
                        </div>
                        <div class="flex justify-between border-b border-gray-50 pb-2">
                            <span class="text-gray-400">Jumlah Penarikan</span>
                            <span class="font-bold text-emerald-600 text-sm" x-text="'Rp ' + Number(selectedPenarikan.jumlah_penarikan ?? 0).toLocaleString('id-ID')"></span>
                        </div>
                        <div class="flex justify-between items-center pt-1">
                            <span class="text-gray-400">Status Penarikan</span>
                            <span class="px-2.5 py-1 text-xs font-semibold rounded-full"
                                :class="{
                                    'bg-amber-100 text-amber-700': selectedPenarikan.status === 'pending',
                                    'bg-emerald-100 text-emerald-700': selectedPenarikan.status === 'disetujui',
                                    'bg-red-100 text-red-700': selectedPenarikan.status === 'ditolak'
                                }"
                                x-text="selectedPenarikan.status ? (selectedPenarikan.status.charAt(0).toUpperCase() + selectedPenarikan.status.slice(1)) : 'Pending'">
                            </span>
                        </div>
                    </div>
                </div>

                <!-- Footer Modal & Tombol Print -->
                <div class="mt-6 flex justify-between items-center">
                    <button @click="
                        let printContent = document.getElementById('printable-area').innerHTML;
                        let originalContent = document.body.innerHTML;
                        document.body.innerHTML = printContent;
                        window.print();
                        document.body.innerHTML = originalContent;
                        location.reload();
                    " class="bg-indigo-600 text-white px-4 py-2 rounded-xl text-xs font-medium hover:bg-indigo-700 transition flex items-center gap-1.5 shadow-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print
                    </button>
                    <button @click="openModal = false" class="bg-gray-100 text-gray-700 px-4 py-2 rounded-xl text-xs font-medium hover:bg-gray-200 transition">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Script JavaScript untuk Export Excel Pengajuan Saldo & Rinciannya -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const btnExport = document.getElementById('btn-export-excel-penarikan');
            if (btnExport) {
                btnExport.addEventListener('click', function () {
                    let dataType = 'application/vnd.ms-excel';
                    
                    let excelHTML = `
                        <html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40">
                        <head><meta charset="utf-8"></head>
                        <body>
                            <h2 style="text-align:center;">LAPORAN REKAPITULASI PENGAJUAN SALDO NASABAH</h2>
                            <p style="text-align:center;">Tanggal Export: ${new Date().toLocaleString('id-ID')}</p>
                            <br>
                            
                            <h3>TABEL DAFTAR PENGAJUAN</h3>
                            <table border="1">
                                <tr style="background-color: #f2f2f2; font-weight: bold;">
                                    <th>No</th>
                                    <th>Nama Nasabah</th>
                                    <th>Jumlah Penarikan</th>
                                    <th>Jenis Bank / E-Wallet</th>
                                    <th>Status</th>
                                    <th>Tanggal Pengajuan</th>
                                </tr>
                    `;

                    const rows = document.querySelectorAll('.penarikan-row');
                    let allDetailsHTML = "";

                    rows.forEach((row) => {
                        const cols = row.querySelectorAll('td');
                        const no = cols[0].innerText.trim();
                        const nama = cols[1].innerText.trim();
                        const jumlahPenarikan = cols[2].innerText.trim();
                        const jenisBank = cols[3].innerText.trim();
                        const status = cols[4].innerText.trim();
                        const tanggal = cols[5].innerText.trim();

                        excelHTML += `
                            <tr>
                                <td>${no}</td>
                                <td>${nama}</td>
                                <td>${jumlahPenarikan}</td>
                                <td>${jenisBank}</td>
                                <td>${status}</td>
                                <td>${tanggal}</td>
                            </tr>
                        `;

                        try {
                            const rawDetail = row.getAttribute('data-detail');
                            const detailObj = JSON.parse(rawDetail);

                            if (detailObj) {
                                allDetailsHTML += `
                                    <br>
                                    <h3>RINCIAN PENGAJUAN: ${nama}</h3>
                                    <table border="1">
                                        <tr style="background-color: #e6f2ff; font-weight: bold;">
                                            <th>Atribut Data</th>
                                            <th>Keterangan / Nilai</th>
                                        </tr>
                                        <tr><td><b>Nama Lengkap</b></td><td>${detailObj.nama_lengkap ?? '-'}</td></tr>
                                        <tr><td><b>Email</b></td><td>${detailObj.email ?? '-'}</td></tr>
                                        <tr><td><b>Metode Pencairan</b></td><td>${detailObj.metode_pencairan ?? '-'}</td></tr>
                                        <tr><td><b>No. Telepon</b></td><td>'${detailObj.nomor_telephone ?? '-'}</td></tr>
                                        <tr><td><b>Alamat Lengkap</b></td><td>${detailObj.alamat_lengkap ?? '-'}</td></tr>
                                        <tr><td><b>Nomor Rekening / E-Wallet</b></td><td>'${detailObj.nomor_rekening ?? '-'}</td></tr>
                                        <tr><td><b>Jenis E-Wallet / Bank</b></td><td>${detailObj.jenis_ewallet ?? '-'}</td></tr>
                                        <tr><td><b>Total Berat Sampah</b></td><td>${detailObj.total_berat ?? '0'} Kg</td></tr>
                                        <tr><td><b>Jumlah Penarikan</b></td><td>Rp ${Number(detailObj.jumlah_penarikan ?? 0).toLocaleString('id-ID')}</td></tr>
                                        <tr><td><b>Status Penarikan</b></td><td>${detailObj.status ?? '-'}</td></tr>
                                        <tr><td><b>Tanggal Pengajuan</b></td><td>${detailObj.created_at ?? '-'}</td></tr>
                                    </table><br>
                                `;
                            }
                        } catch (e) {
                            console.error("Gagal parsing detail penarikan:", e);
                        }
                    });

                    excelHTML += `</table><br><br>` + allDetailsHTML + `</body></html>`;

                    let blob = new Blob(['\ufeff', excelHTML], { type: dataType });
                    let filename = 'laporan-pengajuan-saldo-dan-rincian.xls';
                    
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