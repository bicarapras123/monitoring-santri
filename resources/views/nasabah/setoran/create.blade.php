<x-app-layout>
    <div class="py-8 px-4 md:px-6 bg-gray-100 min-h-screen">
        
        <!-- Header Page -->
        <div class="max-w-4xl mx-auto mb-6">
            <h1 class="text-xl md:text-2xl font-bold text-gray-800">
                Form Pengajuan Setoran Sampah
            </h1>
            <p class="text-xs md:text-sm text-gray-500 mt-0.5">
                Pilih data nasabah terdaftar dan tambahkan detail berbagai jenis sampah yang disetor.
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

        <!-- Card Form Container -->
        <div class="max-w-4xl mx-auto bg-white rounded-2xl shadow-sm border border-gray-100 p-5 md:p-6" x-data="setoranForm()">
            <form action="{{ route('setoran.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5">
                @csrf

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    
                    <!-- 1. Pencarian & Pilih Nasabah (Autofill Data) -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Cari / Pilih Nama Nasabah Terdaftar</label>
                        <select name="nasabah_id" x-model="selectedNasabah" @change="autofillNasabah()" required
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm px-3.5 py-2 border bg-white text-xs md:text-sm">
                            <option value="">-- Ketik / Pilih Nama Nasabah --</option>
                            @foreach($nasabahs as $nasabah)
                                <option value="{{ $nasabah->id }}" 
                                    data-nama="{{ $nasabah->nama_lengkap }}"
                                    data-telepon="{{ $nasabah->nomor_telepon }}"
                                    data-alamat="{{ $nasabah->alamat_lengkap }}"
                                    data-rekening="{{ $nasabah->rekening->nomor_rekening ?? '' }}"
                                    data-ewallet="{{ $nasabah->rekening->jenis_ewallet ?? '' }}">
                                    {{ $nasabah->nama_lengkap }} ({{ $nasabah->alamat_lengkap }})
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <!-- 2. Tanggal Setoran -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Tanggal Setoran</label>
                        <input type="date" name="tanggal_setoran" value="{{ date('Y-m-d') }}" required
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm px-3.5 py-2 border bg-white text-xs md:text-sm">
                    </div>

                    <!-- Nomor Telephone (Autofill) -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Nomor Telephone / WhatsApp</label>
                        <input type="text" name="nomor_telephone" x-model="formTelepon" placeholder="Otomatis terisi..." required readonly
                            class="w-full rounded-xl border-gray-300 bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm px-3.5 py-2 border text-xs md:text-sm">
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

                    <!-- Alamat Lengkap (Full Width - Autofill) -->
                    <div class="md:col-span-2">
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">Alamat Lengkap</label>
                        <textarea name="alamat_lengkap" x-model="formAlamat" rows="2" placeholder="Otomatis terisi..." required readonly
                            class="w-full rounded-xl border-gray-300 bg-gray-50 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm px-3.5 py-2 border text-xs md:text-sm"></textarea>
                    </div>

                    <!-- Nomor Rekening / E-Wallet (Autofill) -->
                    <div>
                        <label class="block text-xs font-semibold text-gray-700 mb-1.5">
                            Nomor Rekening / No. HP E-Wallet <span class="text-gray-400 font-normal" x-show="metode === 'Cash / Tunai'">(Opsional)</span>
                        </label>
                        <input type="text" name="nomor_rekening" x-model="formRekening" placeholder="Otomatis terisi..."
                            class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm px-3.5 py-2 border text-xs md:text-sm">
                    </div>

                </div>

                <hr class="border-gray-200 my-4">

                <!-- 2. Bagian Input Banyak Jenis Sampah Sekaligus (Multi-item) -->
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xs md:text-sm font-bold text-gray-800 uppercase tracking-wider">Daftar Sampah yang Disetor</h3>
                        <button type="button" @click="addItem()" class="px-3 py-1.5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-lg text-xs font-semibold shadow-sm transition flex items-center gap-1">
                            + Tambah Jenis Sampah
                        </button>
                    </div>

                    <div class="space-y-3">
                        <template x-for="(item, index) in items" :key="index">
                            <div class="flex flex-col md:flex-row gap-3 items-start md:items-center bg-gray-50 p-3.5 rounded-xl border border-gray-200">
                                
                                <!-- Pilih Jenis Sampah -->
                                <div class="w-full md:flex-1">
                                    <label class="block text-[11px] font-semibold text-gray-600 mb-1">Jenis Sampah</label>
                                    <select :name="`sampah[${index}][jenis_sampah_id]`" x-model="item.jenis_sampah_id" @change="updateItemHarga(index)" required
                                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm px-3 py-1.5 border bg-white text-xs">
                                        <option value="">Pilih Jenis Sampah</option>
                                        @foreach($jenisSampahs as $sampah)
                                            <option value="{{ $sampah->id }}" data-harga="{{ $sampah->harga_kg }}">
                                                {{ $sampah->nama_sampah }} (Rp {{ number_format($sampah->harga_kg, 0, ',', '.') }} / Kg)
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Input Berat -->
                                <div class="w-full md:w-36">
                                    <label class="block text-[11px] font-semibold text-gray-600 mb-1">Berat (Kg)</label>
                                    <input type="number" step="0.01" :name="`sampah[${index}][berat]`" x-model.number="item.berat" @input="calculateSubtotal(index)" placeholder="0.00" required
                                        class="w-full rounded-lg border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm px-3 py-1.5 border text-xs">
                                </div>

                                <!-- Subtotal per Item -->
                                <div class="w-full md:w-40">
                                    <label class="block text-[11px] font-semibold text-gray-600 mb-1">Subtotal Harga</label>
                                    <div class="text-xs font-bold text-gray-700 py-1.5" x-text="'Rp ' + Number(item.subtotal).toLocaleString('id-ID')">Rp 0</div>
                                </div>

                                <!-- Tombol Hapus Baris -->
                                <div class="w-full md:w-auto self-end md:self-center">
                                    <button type="button" @click="removeItem(index)" x-show="items.length > 1" class="px-2.5 py-1.5 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg text-xs font-semibold transition">
                                        Hapus
                                    </button>
                                </div>
                            </div>
                        </template>
                    </div>
                </div>

                <!-- Informasi Estimasi Total Keseluruhan Harga -->
                <div class="bg-indigo-50/60 border border-indigo-100 rounded-xl p-4 flex items-center justify-between">
                    <div>
                        <span class="text-xs font-semibold text-indigo-800 uppercase tracking-wider">Total Keseluruhan Nilai Setoran</span>
                        <p class="text-[11px] text-gray-500 mt-0.5">Akumulasi total harga dari seluruh jenis sampah yang diinput.</p>
                    </div>
                    <div class="text-right">
                        <span class="text-lg md:text-xl font-bold text-emerald-600" x-text="'Rp ' + Number(totalKeseluruhan).toLocaleString('id-ID')">Rp 0</span>
                    </div>
                </div>

                <!-- Upload Foto Sampah (Full Width) -->
                <div>
                    <label class="block text-xs font-semibold text-gray-700 mb-1.5">Upload Foto Bukti Sampah</label>
                    <input type="file" name="foto_sampah" accept="image/*" required
                        class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-lg file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 border border-gray-300 rounded-xl bg-white shadow-sm">
                    <p class="text-[11px] text-gray-400 mt-1">Format yang didukung: JPG, PNG, JPEG (Maks. 2MB)</p>
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

<!-- Script Alpine.js untuk Autofill & Multi-Item Dynamic Rows -->
<script>
        function setoranForm() {
            return {
                metode: 'Cash / Tunai',
                selectedNasabah: '',
                formTelepon: '',
                formAlamat: '',
                formRekening: '',
                
                items: [
                    { jenis_sampah_id: '', harga_kg: 0, berat: '', subtotal: 0 }
                ],

                autofillNasabah() {
                    let select = document.querySelector('select[name="nasabah_id"]');
                    let selectedOption = select.options[select.selectedIndex];
                    if (selectedOption && this.selectedNasabah !== '') {
                        this.formTelepon = selectedOption.getAttribute('data-telepon') || '';
                        this.formAlamat = selectedOption.getAttribute('data-alamat') || '';
                        this.formRekening = selectedOption.getAttribute('data-rekening') || '';
                        
                        let ewallet = selectedOption.getAttribute('data-ewallet');
                        if (ewallet) {
                            this.metode = ewallet;
                        }
                    } else {
                        this.formTelepon = '';
                        this.formAlamat = '';
                        this.formRekening = '';
                        this.metode = 'Cash / Tunai';
                    }
                },

                addItem() {
                    this.items.push({ jenis_sampah_id: '', harga_kg: 0, berat: '', subtotal: 0 });
                },

                removeItem(index) {
                    if (this.items.length > 1) {
                        this.items.splice(index, 1);
                    }
                },

                updateItemHarga(index) {
                    let rowElement = document.querySelectorAll('select[name^="sampah"]')[index];
                    let selectedOption = rowElement.options[rowElement.selectedIndex];
                    let harga = selectedOption ? parseFloat(selectedOption.getAttribute('data-harga')) || 0 : 0;
                    
                    this.items[index].harga_kg = harga;
                    this.calculateSubtotal(index);
                },

                calculateSubtotal(index) {
                    let item = this.items[index];
                    let berat = parseFloat(item.berat) || 0;
                    item.subtotal = berat * item.harga_kg;
                },

                get totalKeseluruhan() {
                    return this.items.reduce((sum, item) => sum + (parseFloat(item.subtotal) || 0), 0);
                }
            }
        }
    </script>
</x-app-layout>