<x-app-layout>
    <div class="py-8 px-4 md:px-6 bg-gray-100 min-h-screen">
        
        <!-- Header Page -->
        <div class="max-w-4xl mx-auto mb-6">
            <h1 class="text-xl md:text-2xl font-bold text-gray-800">
                Form Pengajuan Setoran Sampah
            </h1>
            <p class="text-xs md:text-sm text-gray-500 mt-0.5">
                Isi data diri dan detail sampah yang ingin Anda setor atau jual ke Bank Sampah
            </p>
        </div>

        <!-- Notifikasi Sukses / Error -->
        <div class="max-w-4xl mx-auto">
            @if (session('success'))
                <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-2.5 rounded-xl shadow-sm text-xs md:text-sm">
                    {{ session('success') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-4 bg-red-50 border border-red-200 text-red-700 px-4 py-2.5 rounded-xl shadow-sm text-xs md:text-sm">
                    <ul class="list-disc list-inside">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
        </div>

        <!-- Card Form Container (Dibatasi max-w-4xl agar tidak terlalu lebar/besar) -->
        <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 p-5 md:p-6" x-data="setoranForm()">
            <form action="{{ route('setoran.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    <!-- Nama Lengkap -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', Auth::user()->name ?? '') }}" placeholder="Masukkan nama lengkap" required
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm px-3.5 py-2 border text-xs md:text-sm">
                    </div>

                    <!-- Nomor Telephone -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Nomor Telephone / WhatsApp</label>
                        <input type="text" name="nomor_telephone" value="{{ old('nomor_telephone') }}" placeholder="Contoh: 081234567890" required
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm px-3.5 py-2 border text-xs md:text-sm">
                    </div>

                    <!-- Alamat Lengkap (Full Width) -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Alamat Lengkap</label>
                        <textarea name="alamat_lengkap" rows="2" placeholder="Masukkan alamat lengkap penjemputan/pengantaran sampah" required
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm px-3.5 py-2 border text-xs md:text-sm">{{ old('alamat_lengkap') }}</textarea>
                    </div>

                    <!-- Jenis Rekening / Metode Pembayaran -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Metode Pencairan <span class="text-gray-400 font-normal">(Pilih Cash jika tunai)</span>
                        </label>
                        <select name="jenis_rekening" x-model="metode"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm px-3.5 py-2 border bg-white text-xs md:text-sm">
                            <option value="Cash / Tunai">Cash / Tunai di Tempat</option>
                            <option value="GoPay">GoPay</option>
                            <option value="DANA">DANA</option>
                        </select>
                    </div>

                    <!-- Nomor Rekening / E-Wallet -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Nomor Rekening / No. HP E-Wallet <span class="text-gray-400 font-normal" x-show="metode === 'Cash / Tunai'">(Opsional)</span>
                        </label>
                        <input type="text" name="nomor_rekening" value="{{ old('nomor_rekening') }}" placeholder="Contoh: 081234567890 atau No Rekening"
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm px-3.5 py-2 border text-xs md:text-sm">
                    </div>

                    <!-- Jenis Sampah -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Jenis Sampah</label>
                        <select name="jenis_sampah" x-model="selectedSampah" @change="updateHarga()" required
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm px-3.5 py-2 border bg-white text-xs md:text-sm">
                            <option value="">Pilih Jenis Sampah</option>
                            @foreach(\App\Models\JenisSampah::all() as $item)
                                <option value="{{ $item->nama_sampah }}" data-harga="{{ $item->harga_kg }}">
                                    {{ $item->nama_sampah }} (Rp {{ number_format($item->harga_kg, 0, ',', '.') }} / Kg)
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Total Berat Sampah -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Total Estimasi Berat (Kg)</label>
                        <input type="number" step="0.01" name="total_berat" x-model.number="berat" placeholder="Contoh: 2.5" required
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm px-3.5 py-2 border text-xs md:text-sm">
                    </div>

                    <!-- Informasi Estimasi Total Harga -->
                    <div class="md:col-span-2 bg-indigo-50/60 border border-indigo-100 rounded-xl p-3.5 flex items-center justify-between">
                        <div>
                            <span class="text-[11px] font-semibold text-indigo-800 uppercase tracking-wider">Estimasi Nilai Tabungan Anda</span>
                            <p class="text-[11px] text-gray-500 mt-0.5">Dihitung otomatis berdasarkan harga per kilogram jenis sampah.</p>
                        </div>
                        <div class="text-right">
                            <span class="text-base md:text-lg font-bold text-emerald-600" x-text="'Rp ' + Number(totalEstimasi).toLocaleString('id-ID')">Rp 0</span>
                        </div>
                    </div>

                    <!-- Upload Foto Sampah (Full Width) -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Upload Foto Sampah</label>
                        <input type="file" name="foto_sampah" accept="image/*" required
                            class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 border border-gray-300 rounded-xl bg-white shadow-sm">
                        <p class="text-[11px] text-gray-400 mt-1">Format yang didukung: JPG, PNG, JPEG (Maks. 2MB)</p>
                    </div>

                </div>

                <!-- Tombol Submit -->
                <div class="flex justify-end pt-3 border-t border-gray-100">
                    <button type="submit" class="px-5 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-xs md:text-sm shadow-sm transition">
                        Kirim Pengajuan Setoran Sampah
                    </button>
                </div>

            </form>
        </div>

    </div>

    <!-- Script Alpine.js untuk Kalkulasi Otomatis -->
    <script>
        function setoranForm() {
            return {
                metode: 'Cash / Tunai',
                selectedSampah: '',
                hargaPerKg: 0,
                berat: '',
                updateHarga() {
                    let select = document.querySelector('select[name="jenis_sampah"]');
                    let selectedOption = select.options[select.selectedIndex];
                    this.hargaPerKg = selectedOption ? parseFloat(selectedOption.getAttribute('data-harga')) || 0 : 0;
                },
                get totalEstimasi() {
                    let b = parseFloat(this.berat) || 0;
                    return b * this.hargaPerKg;
                }
            }
        }
    </script>
</x-app-layout>