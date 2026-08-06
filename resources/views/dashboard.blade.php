<x-app-layout>
    @if(in_array(Auth::user()->role, ['nasabah', 'orang_tua']))

        <!-- Main Content dengan paksaan scroll aktif -->
        <div class="p-4 md:p-8 bg-gray-100 h-full overflow-y-auto" style="max-height: calc(100vh - 4rem); min-height: 100vh;" x-data="{ activeTab: 'info' }">

            <!-- Heading -->
            <div class="mb-8">
                <h1 class="text-2xl md:text-3xl font-bold text-gray-800">
                    Dashboard Nasabah
                </h1>
                <p class="text-gray-500 mt-1">
                    Selamat datang kembali, {{ Auth::user()->name }}
                </p>
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
                <div class="mb-6 bg-red-50 border-l-4 border-red-500 p-4 rounded-2xl shadow-sm text-red-700 text-sm">
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
                <div class="flex space-x-4 mb-6 border-b border-gray-200 pb-4">
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

                <!-- SECTION 1: Informasi Data Diri & Opsi E-Wallet -->
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
        
        <!-- Status dari Database -->
        @if(Auth::user()->nasabah && Auth::user()->nasabah->rekening)
            @if(Auth::user()->nasabah->rekening->status == 'verified')
                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-bold shadow-sm">
                    Terverifikasi (ACC)
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
                <strong>Foto KTP:</strong> 
                @if(Auth::user()->nasabah->rekening->foto_ktp)
                    <a href="{{ asset('storage/' . Auth::user()->nasabah->rekening->foto_ktp) }}" target="_blank" class="text-indigo-600 underline font-semibold ml-1">Lihat KTP yang diupload</a>
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

                    <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 h-fit">
                        <div class="text-center">
                            <div class="h-24 w-24 rounded-full bg-indigo-100 mx-auto flex items-center justify-center text-3xl font-bold text-indigo-700">
                                {{ strtoupper(substr(Auth::user()->name,0,1)) }}
                            </div>
                            <h2 class="mt-4 text-xl font-bold text-gray-800">
                                {{ Auth::user()->name }}
                            </h2>
                            <p class="text-gray-500">
                                {{ Auth::user()->email }}
                            </p>
                        </div>
                    </div>
                </div>

                <!-- SECTION 2: Pencairan Dana Cash (Tunai) -->
                <div x-show="activeTab === 'cash'" class="bg-white rounded-3xl shadow-sm border border-gray-100 p-6 md:p-8 mb-12" style="display: none;">
                    <h2 class="text-lg font-bold text-gray-800 mb-2">Opsi 2: Pencairan Dana Secara Cash (Tunai)</h2>
                    <p class="text-sm text-gray-500 mb-6">Silakan periksa kembali data diri Anda di bawah ini sebelum mencetak formulir pencairan tunai.</p>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm text-gray-600 bg-gray-50 p-6 rounded-2xl border border-gray-200 mb-6">
                        <div><strong>NIK:</strong> {{ Auth::user()->nasabah->nik }}</div>
                        <div><strong>Nama Lengkap:</strong> {{ Auth::user()->nasabah->nama_lengkap }}</div>
                        <div><strong>Jenis Kelamin:</strong> {{ Auth::user()->nasabah->jenis_kelamin }}</div>
                        <div><strong>Tempat, Tgl Lahir:</strong> {{ Auth::user()->nasabah->tempat_lahir }}, {{ Auth::user()->nasabah->tanggal_lahir }}</div>
                        <div><strong>Nomor Telepon:</strong> {{ Auth::user()->nasabah->nomor_telepon }}</div>
                        <div><strong>Nama Orang Tua:</strong> {{ Auth::user()->nasabah->nama_orang_tua }}</div>
                        <div class="md:col-span-2"><strong>Alamat Lengkap:</strong> {{ Auth::user()->nasabah->alamat_lengkap }}</div>
                    </div>

                    <form action="#" method="GET" target="_blank" class="space-y-6">
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Cetak Formulir Pencairan Cash
                            </button>
                        </div>
                    </form>
                </div>

            @endif

        </div>

    @else
        <!-- TAMPILAN JIKA DIAKSES OLEH ROLE LAIN (Admin, RW, Bank Sampah Induk) -->
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
</x-app-layout>