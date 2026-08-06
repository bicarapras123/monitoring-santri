<x-app-layout>
    @if(in_array(Auth::user()->role, ['nasabah', 'orang_tua']))

        <!-- Main Content dengan paksaan scroll aktif -->
        <div class="p-4 md:p-8 bg-gray-100 h-full overflow-y-auto" style="max-height: calc(100vh - 4rem); min-height: 100vh;" x-data="{ activeTab: 'info', showModalRiwayat: false }">

            <!-- Heading & Tombol Aksi (Cetak & Riwayat) -->
            <div class="flex flex-col md:flex-row justify-between items-start md:items-center mb-8 gap-4">
                <div>
                    <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                        Dashboard Nasabah
                    </h1>
                    <p class="text-gray-500 mt-1">
                        Selamat datang kembali, {{ Auth::user()->name }}
                    </p>
                </div>
                
                <div class="flex items-center gap-3 no-print">
                    <!-- Tombol Buka Pop-Up Riwayat Penarikan -->
                    <button @click="showModalRiwayat = true" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white hover:bg-gray-50 text-gray-700 border border-gray-200 font-semibold rounded-2xl text-xs md:text-sm shadow-sm transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 text-indigo-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01" />
                        </svg>
                        Riwayat Penarikan
                    </button>

                    <!-- Tombol Cetak -->
                    <button onclick="window.print()" class="inline-flex items-center gap-2 px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-2xl text-xs md:text-sm shadow-sm transition">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Cetak Dashboard
                    </button>
                </div>
            </div>

            <!-- Notification Alert Success/Error -->
            @if (session('success'))
                <div class="mb-6 bg-green-50 border-l-4 border-green-500 p-4 rounded-2xl shadow-sm text-green-700 text-sm font-medium">
                    {{ session('success') }}
                </div>
            @endif

            @if (session('error'))
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-2xl shadow-sm text-red-700 text-sm font-medium">
                    {{ session('error') }}
                </div>
            @endif

            @if ($errors->any())
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-2xl shadow-sm text-sm">
                    <p class="font-bold mb-1">Gagal menyimpan data! Mohon periksa inputan berikut:</p>
                    <ul class="list-disc list-inside space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            {{-- KONDISI 1: Jika nasabah belum terdaftar --}}
            @if (!Auth::user()->nasabah)
                
                <!-- Warning Banner -->
                <div class="mb-6 bg-yellow-50 border-l-4 border-yellow-400 p-4 rounded-2xl shadow-sm">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-yellow-400" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm text-yellow-700 font-medium">
                                Akun Anda belum terdaftar sebagai nasabah. Mohon lengkapi data diri di bawah ini untuk melanjutkan.
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Form Pendaftaran Data Diri Nasabah -->
                <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-8 mb-12">
                    <h2 class="text-xl font-bold text-gray-800 mb-6 pb-4 border-b border-gray-100">
                        Formulir Pendaftaran Data Diri Nasabah
                    </h2>

                    <form action="{{ route('nasabah.store') }}" method="POST" class="space-y-6">
                        @csrf

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">NIK (Nomor Induk Kependudukan)</label>
                                <input type="text" name="nik" value="{{ old('nik') }}" placeholder="Masukkan 16 digit NIK" required
                                    class="w-full rounded-2xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm px-4 py-3 border">
                                @error('nik')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Lengkap</label>
                                <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', Auth::user()->name) }}" placeholder="Sesuai KTP" required
                                    class="w-full rounded-2xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm px-4 py-3 border">
                                @error('nama_lengkap')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Tempat, Tanggal Lahir</label>
                                <div class="grid grid-cols-2 gap-3">
                                    <input type="text" name="tempat_lahir" value="{{ old('tempat_lahir') }}" placeholder="Kota Kelahiran" required
                                        class="w-full rounded-2xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm px-4 py-3 border">
                                    <input type="date" name="tanggal_lahir" value="{{ old('tanggal_lahir') }}" required
                                        class="w-full rounded-2xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm px-4 py-3 border">
                                </div>
                                @error('tempat_lahir')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                                @error('tanggal_lahir')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Jenis Kelamin</label>
                                <select name="jenis_kelamin" required
                                    class="w-full rounded-2xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm px-4 py-3 border bg-white">
                                    <option value="">Pilih Jenis Kelamin</option>
                                    <option value="Laki-laki" {{ old('jenis_kelamin') == 'Laki-laki' ? 'selected' : '' }}>Laki-laki</option>
                                    <option value="Perempuan" {{ old('jenis_kelamin') == 'Perempuan' ? 'selected' : '' }}>Perempuan</option>
                                </select>
                                @error('jenis_kelamin')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Telepon / WhatsApp</label>
                                <input type="text" name="nomor_telepon" value="{{ old('nomor_telepon') }}" placeholder="Contoh: 081234567890" required
                                    class="w-full rounded-2xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm px-4 py-3 border">
                                @error('nomor_telepon')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div>
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Orang Tua (Ibu Kandung / Ayah)</label>
                                <input type="text" name="nama_orang_tua" value="{{ old('nama_orang_tua') }}" placeholder="Masukkan nama orang tua" required
                                    class="w-full rounded-2xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm px-4 py-3 border">
                                @error('nama_orang_tua')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>

                            <div class="md:col-span-2">
                                <label class="block text-sm font-semibold text-gray-700 mb-2">Alamat Lengkap</label>
                                <textarea name="alamat_lengkap" rows="3" placeholder="Jalan, RT/RW, Kelurahan, Kecamatan, Kota/Kabupaten" required
                                    class="w-full rounded-2xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm px-4 py-3 border">{{ old('alamat_lengkap') }}</textarea>
                                @error('alamat_lengkap')
                                    <span class="text-red-500 text-xs mt-1 block">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>

                        <div class="flex justify-end pt-4">
                            <button type="submit" 
                                class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-2xl transition shadow-sm">
                                Simpan & Daftarkan Data Diri
                            </button>
                        </div>
                    </form>
                </div>

            @else

                {{-- KONDISI 2: Jika nasabah sudah terdaftar --}}

                <!-- CARD STATISTIK / TOTAL TABUNGAN & TOTAL SETORAN -->
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <!-- Card Total Saldo Tabungan -->
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-gray-400">SALDO ANDA</p>
                            <h3 class="text-2xl md:text-3xl font-extrabold text-emerald-600 mt-1">
                                Rp {{ number_format($totalTabungan ?? 0, 0, ',', '.') }}
                            </h3>
                        </div>
                        <div class="h-14 w-14 bg-emerald-50 rounded-2xl flex items-center justify-center text-emerald-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Card Total Setoran Sampah -->
                    <div class="bg-white rounded-3xl p-6 shadow-sm border border-gray-100 flex items-center justify-between">
                        <div>
                            <p class="text-xs font-bold uppercase tracking-wider text-gray-400">Total Setoran Sampah</p>
                            <h3 class="text-2xl md:text-3xl font-extrabold text-indigo-600 mt-1">
                                {{ $totalSetoran ?? 0 }} <span class="text-sm font-medium text-gray-500">Kali</span>
                            </h3>
                        </div>
                        <div class="h-14 w-14 bg-indigo-50 rounded-2xl flex items-center justify-center text-indigo-600">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-7 w-7" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Navigasi Tab (Disembunyikan saat cetak agar rapi) -->
                <div class="no-print flex space-x-4 mb-6 border-b border-gray-200 pb-4">
                    <button @click="activeTab = 'info'" 
                        :class="activeTab === 'info' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200'"
                        class="px-5 py-2.5 rounded-2xl font-semibold text-sm transition">
                        Opsi 1: Info Data & E-Wallet
                    </button>
                    <button @click="activeTab = 'cash'" 
                        :class="activeTab === 'cash' ? 'bg-indigo-600 text-white shadow-sm' : 'bg-white text-gray-600 hover:bg-gray-50 border border-gray-200'"
                        class="px-5 py-2.5 rounded-2xl font-semibold text-sm transition">
                        Opsi 2: Pencairan Dana Cash (Tunai)
                    </button>
                </div>

                <!-- SECTION 1: Informasi Data Diri & Form Tarik Saldo -->
                <div x-show="activeTab === 'info'" class="grid grid-cols-1 xl:grid-cols-3 gap-6 mb-12">
                    <div class="xl:col-span-2 space-y-6">
                        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                            <h2 class="text-lg font-bold text-gray-800 mb-4">Informasi Data Diri Nasabah :</h2>
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600">
                                <div><strong>NIK:</strong> {{ Auth::user()->nasabah->nik }}</div>
                                <div><strong>Nama Lengkap:</strong> {{ Auth::user()->nasabah->nama_lengkap }}</div>
                                <div><strong>Jenis Kelamin:</strong> {{ Auth::user()->nasabah->jenis_kelamin }}</div>
                                <div><strong>Tempat, Tgl Lahir:</strong> {{ Auth::user()->nasabah->tempat_lahir }}, {{ Auth::user()->nasabah->tanggal_lahir }}</div>
                                <div><strong>Nomor Telepon:</strong> {{ Auth::user()->nasabah->nomor_telepon }}</div>
                                <div><strong>Nama Orang Tua:</strong> {{ Auth::user()->nasabah->nama_orang_tua }}</div>
                                <div class="md:col-span-2"><strong>Alamat Lengkap:</strong> {{ Auth::user()->nasabah->alamat_lengkap }}</div>
                            </div>
                        </div>

                        <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6">
                            <div class="flex items-center justify-between mb-4">
                                <h2 class="text-lg font-bold text-gray-800">Data Rekening Anda :</h2>
                                
                                @if(Auth::user()->nasabah && Auth::user()->nasabah->rekening)
                                    @if(Auth::user()->nasabah->rekening->status == 'verified')
                                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold shadow-sm">
                                            Terverifikasi
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold shadow-sm">
                                            Menunggu Verifikasi (Pending)
                                        </span>
                                    @endif
                                @endif
                            </div>
                            
                            @if(Auth::user()->nasabah && Auth::user()->nasabah->rekening)
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600 bg-indigo-50/50 p-4 rounded-2xl border border-indigo-100 mb-4">
                                    <div><strong>Jenis E-Wallet:</strong> {{ Auth::user()->nasabah->rekening->jenis_ewallet }}</div>
                                    <div><strong>Nomor Rekening/E-Wallet:</strong> {{ Auth::user()->nasabah->rekening->nomor_rekening }}</div>
                                    <div class="md:col-span-2">
                                        <strong class="block mb-2">Foto KTP:</strong> 
                                        @if(Auth::user()->nasabah->rekening->foto_ktp)
                                            <div class="mt-1">
                                                <a href="{{ asset('storage/' . Auth::user()->nasabah->rekening->foto_ktp) }}" target="_blank" class="inline-block group">
                                                    <img src="{{ asset('storage/' . Auth::user()->nasabah->rekening->foto_ktp) }}" alt="Foto KTP" 
                                                        class="h-32 w-auto object-cover rounded-xl border border-gray-200 shadow-sm group-hover:opacity-90 transition">
                                                </a>
                                                <p class="text-[11px] text-gray-400 mt-1">Klik gambar untuk memperbesar</p>
                                            </div>
                                        @else
                                            <span class="text-gray-400">Tidak ada file</span>
                                        @endif
                                    </div>
                                </div>
                            @else
                                <!-- Form input jika belum ada rekening -->
                                <form action="{{ route('rekening.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4 pt-2">
                                    @csrf
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-1">Pilih Jenis E-Wallet</label>
                                            <select name="jenis_ewallet" required
                                                class="w-full rounded-2xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm px-4 py-2 border bg-white text-sm">
                                                <option value="">Pilih E-Wallet</option>
                                                <option value="GoPay">GoPay</option>
                                                <option value="DANA">DANA</option>
                                            </select>
                                        </div>
                                        <div>
                                            <label class="block text-sm font-semibold text-gray-700 mb-1">Nomor Rekening / E-Wallet</label>
                                            <input type="text" name="nomor_rekening" placeholder="Contoh: 081234567890" required
                                                class="w-full rounded-2xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm px-4 py-2 border text-sm">
                                        </div>
                                        <div class="md:col-span-2">
                                            <label class="block text-sm font-semibold text-gray-700 mb-1">Upload Foto KTP</label>
                                            <input type="file" name="foto_ktp" accept="image/*" required
                                                class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 border border-gray-300 rounded-2xl bg-white">
                                        </div>
                                    </div>
                                    <div class="flex justify-end pt-2">
                                        <button type="submit" class="px-4 py-2 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-xs transition">
                                            Simpan Rekening E-Wallet
                                        </button>
                                    </div>
                                </form>
                            @endif
                        </div>
                    </div>

                    <!-- Kolom Kanan: Cek Saldo & Form / Peringatan Tarik Saldo -->
                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 h-fit">
                        <div class="border-b border-gray-100 pb-4 mb-4">
                            <h3 class="text-base font-bold text-gray-800">Tarik Saldo Tabungan</h3>
                            <p class="text-xs text-gray-500 mt-0.5">Ajukan pencairan dana tabungan sampah Anda.</p>
                        </div>

                        <div class="bg-emerald-50 border border-emerald-100 rounded-2xl p-4 mb-5 text-center">
                            <span class="text-xs font-semibold text-emerald-800 uppercase tracking-wider">Saldo Tersedia</span>
                            <h4 class="text-xl font-extrabold text-emerald-600 mt-1">
                                Rp {{ number_format($totalTabungan ?? 0, 0, ',', '.') }}
                            </h4>
                        </div>

                        {{-- Validasi Blade: Jika Saldo kurang dari atau sama dengan 50.000 --}}
                        @if(($totalTabungan ?? 0) <= 50000)
                            <div class="bg-amber-50 border-l-4 border-amber-400 p-4 rounded-2xl">
                                <div class="flex items-start">
                                    <div class="flex-shrink-0">
                                        <svg class="h-5 w-5 text-amber-400" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                        </svg>
                                    </div>
                                    <div class="ml-3">
                                        <p class="text-xs text-amber-800 leading-relaxed font-semibold">
                                            Saldo tabungan Anda belum mencukupi untuk melakukan penarikan. Saldo wajib menyisakan minimal <strong>Rp 50.000</strong> sebagai endepan yang tidak boleh kurang.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        @else
                            <!-- Form Tarik Saldo (Muncul Jika Saldo > 50.000) -->
                            <form action="{{ route('penarikan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                                @csrf
                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Jumlah Penarikan (Rp)</label>
                                    <input type="number" name="jumlah_penarikan" placeholder="Contoh: 50000" min="1" max="{{ $totalTabungan - 50000 }}" required
                                        class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm px-3.5 py-2 border text-xs">
                                    <p class="text-[11px] text-gray-400 mt-1">Nominal bebas, namun wajib menyisakan saldo endepan Rp 50.000 di tabungan.</p>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Metode Pencairan</label>
                                    <select name="metode_pencairan" required
                                        class="w-full rounded-xl border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 shadow-sm px-3.5 py-2 border bg-white text-xs">
                                        <option value="">Pilih Metode</option>
                                        <option value="Transfer E-Wallet">Transfer ke E-Wallet Terdaftar</option>
                                    </select>
                                </div>

                                <div>
                                    <label class="block text-xs font-semibold text-gray-700 mb-1">Upload PDF Bukti Cetak Dashboard</label>
                                    <input type="file" name="bukti_pdf" accept="application/pdf" required
                                        class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-600 file:text-white hover:file:bg-indigo-700 border border-gray-300 rounded-xl bg-white">
                                    <p class="text-[11px] text-gray-400 mt-1">Format file harus .pdf sebagai barang bukti saldo Anda.</p>
                                </div>

                                <button type="submit" class="w-full py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold rounded-xl text-xs transition shadow-sm">
                                    Ajukan Penarikan Saldo
                                </button>
                            </form>
                        @endif
                    </div>
                </div>

                <!-- SECTION 2: Pencairan Dana Cash (Tunai) -->
                <div x-show="activeTab === 'cash'" class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-8 mb-12" style="display: none;">
                    <h2 class="text-lg font-bold text-gray-800 mb-2">Pencairan Dana Secara Cash (Tunai)</h2>
                    <p class="text-sm text-gray-500 mb-6">Silakan periksa kembali data diri Anda di bawah ini sebelum mencetak formulir pengajuan pencairan tunai.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600 bg-gray-50 p-6 rounded-2xl border border-gray-200 mb-6">
                        <div><strong>NIK:</strong> {{ Auth::user()->nasabah->nik }}</div>
                        <div><strong>Nama Lengkap:</strong> {{ Auth::user()->nasabah->nama_lengkap }}</div>
                        <div><strong>Jenis Kelamin:</strong> {{ Auth::user()->nasabah->jenis_kelamin }}</div>
                        <div><strong>Tempat, Tgl Lahir:</strong> {{ Auth::user()->nasabah->tempat_lahir }}, {{ Auth::user()->nasabah->tanggal_lahir }}</div>
                        <div><strong>Nomor Telepon:</strong> {{ Auth::user()->nasabah->nomor_telepon }}</div>
                        <div><strong>Nama Orang Tua:</strong> {{ Auth::user()->nasabah->nama_orang_tua }}</div>
                        <div class="md:col-span-2"><strong>Alamat Lengkap:</strong> {{ Auth::user()->nasabah->alamat_lengkap }}</div>
                    </div>

                    <!-- Mengarah ke route khusus cetak formulir pencairan cash (ubah action sesuai route backend Anda) -->
                    <form action="{{ route('pencairan.cash.cetak') }}" method="GET" target="_blank" class="space-y-6">
                        <div class="flex items-start gap-3 bg-yellow-50 p-4 rounded-2xl border border-yellow-200">
                            <input type="checkbox" id="konfirmasi_data" name="konfirmasi_data" required
                                class="mt-1 h-4 w-4 rounded border-gray-300 text-indigo-600 focus:ring-indigo-500">
                            <label for="konfirmasi_data" class="text-xs text-yellow-800 leading-relaxed font-medium">
                                Saya menyatakan dengan sesungguhnya bahwa seluruh data diri, NIK, alamat, serta informasi yang tercantum di atas adalah benar dan sesuai dengan keadaan yang sebenarnya. Segala bentuk ketidaksesuaian atau penyalahgunaan data sepenuhnya menjadi tanggung jawab saya.
                            </label>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" 
                                class="px-6 py-3 bg-gray-800 hover:bg-gray-900 text-white font-semibold rounded-2xl transition text-sm flex items-center gap-2 shadow-sm">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Cetak Form Pengajuan Cash
                            </button>
                        </div>
                    </form>
                </div>

                <!-- MODAL POP-UP RIWAYAT PENARIKAN -->
                <div x-show="showModalRiwayat" style="display: none;" class="fixed inset-0 z-50 overflow-y-auto bg-black/50 backdrop-blur-sm flex items-center justify-center p-4">
                    <div @click.away="showModalRiwayat = false" class="bg-white rounded-3xl shadow-xl max-w-3xl w-full p-6 md:p-8 overflow-hidden transform transition-all">
                        
                        <!-- Header Modal -->
                        <div class="flex justify-between items-center pb-4 border-b border-gray-100 mb-6">
                            <div>
                                <h3 class="text-lg font-bold text-gray-800">Semua Riwayat Transaksi Penarikan</h3>
                                <p class="text-xs text-gray-500 mt-0.5">Daftar lengkap riwayat pengajuan penarikan dana Anda.</p>
                            </div>
                            <button @click="showModalRiwayat = false" class="h-8 w-8 bg-gray-100 hover:bg-gray-200 rounded-full flex items-center justify-center text-gray-600 transition">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>

                        <!-- Content Tabel Riwayat -->
                        <div class="max-h-[60vh] overflow-y-auto pr-1">
                            @if(isset($riwayatPenarikan) && $riwayatPenarikan->count() > 0)
                                <div class="space-y-3">
                                    @foreach($riwayatPenarikan as $item)
                                        <div class="flex flex-col md:flex-row justify-between items-start md:items-center p-4 rounded-2xl border border-gray-100 bg-gray-50/50 gap-3">
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <span class="font-bold text-gray-800 text-sm">Rp {{ number_format($item->jumlah_penarikan, 0, ',', '.') }}</span>
                                                </div>
                                                <p class="text-xs text-gray-500 mt-1">
                                                    Metode: {{ $item->metode_pencairan }} &bull; Tanggal: {{ $item->created_at->format('d M Y, H:i') }}
                                                </p>
                                            </div>

                                            @if($item->bukti_pdf)
                                                <a href="{{ asset('storage/' . $item->bukti_pdf) }}" target="_blank" class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-xl text-xs font-semibold transition">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                                    </svg>
                                                    Lihat Bukti PDF
                                                </a>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-12">
                                    <div class="h-12 w-12 bg-gray-100 text-gray-400 rounded-2xl flex items-center justify-center mx-auto mb-3">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4" />
                                        </svg>
                                    </div>
                                    <p class="text-sm font-semibold text-gray-600">Belum ada riwayat transaksi penarikan</p>
                                    <p class="text-xs text-gray-400 mt-1">Semua riwayat pengajuan penarikan dana Anda akan muncul di sini.</p>
                                </div>
                            @endif
                        </div>

                        <!-- Footer Modal -->
                        <div class="flex justify-end pt-6 mt-6 border-t border-gray-100">
                            <button @click="showModalRiwayat = false" class="px-5 py-2.5 bg-gray-100 hover:bg-gray-200 text-gray-700 font-semibold rounded-2xl text-xs transition">
                                Tutup
                            </button>
                        </div>

                    </div>
                </div>

            @endif

        </div>

    @else
        <!-- TAMPILAN JIKA DI AKSES OLEH ROLE LAIN -->
        <div class="flex flex-col items-center justify-center h-[80vh] text-center p-6">
            <div class="bg-red-100 text-red-600 p-4 rounded-full mb-4">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-gray-800">Akses Ditolak</h2>
            <p class="text-gray-500 mt-2 max-w-md">
                Saat Ini Anda Bukan Nasabah Bank Sampah RW.04, Halaman ini hanya bisa di akses oleh Nasabah silahkan pilih fitur halaman yang tersedia!
            </p>
        </div>
    @endif

    <!-- CSS khusus untuk menyembunyikan elemen saat dicetak -->
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
            body {
                background: white !important;
            }
        }
    </style>
</x-app-layout>