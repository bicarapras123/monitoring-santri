<x-app-layout>
    <div class="py-8">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-base font-bold text-gray-800 mb-1">Form Pengesahan Kelola Laporan Warga</h3>
                <p class="text-xs text-gray-500 mb-6">Lengkapi data diri warga dan centang validasi pengesahan di bawah ini.</p>

                <form action="{{ route('rw.kelolaporan.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
                    @csrf

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">NIK (Nomor Induk Kependudukan)</label>
                        <input type="text" name="nik" value="{{ old('nik') }}" maxlength="16" class="w-full text-xs rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Masukkan 16 digit NIK" required>
                        @error('nik') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap') }}" class="w-full text-xs rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500" placeholder="Nama sesuai KTP" required>
                        @error('nama_lengkap') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Kelurahan</label>
                            <input type="text" name="kelurahan" value="{{ old('kelurahan') }}" class="w-full text-xs rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Kecamatan</label>
                            <input type="text" name="kecamatan" value="{{ old('kecamatan') }}" class="w-full text-xs rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500" required>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Kode Pos</label>
                            <input type="text" name="kode_pos" value="{{ old('kode_pos') }}" class="w-full text-xs rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-700 mb-1">Kota</label>
                            <input type="text" name="kota" value="{{ old('kota') }}" class="w-full text-xs rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500" required>
                        </div>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Nomor Telepon / WhatsApp</label>
                        <input type="text" name="nomor_telepon" value="{{ old('nomor_telepon') }}" class="w-full text-xs rounded-xl border-gray-200 focus:border-emerald-500 focus:ring-emerald-500" placeholder="08xxxxxxxxxx" required>
                    </div>

                    <!-- Input File Upload Wajib Diisi -->
                    <div>
                        <label class="block text-xs font-bold text-gray-700 mb-1">Unggah Dokumen / Bukti Pendukung</label>
                        <input type="file" name="file_upload" class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-emerald-50 file:text-emerald-700 hover:file:bg-emerald-100 border border-gray-200 rounded-xl" required>
                        <span class="text-[10px] text-gray-400 mt-1 block">Format yang didukung: PDF, JPG, PNG (Maks. 2MB)</span>
                        @error('file_upload') <span class="text-red-500 text-[10px]">{{ $message }}</span> @enderror
                    </div>

                    <!-- Tombol-tombol Centang Pengesahan -->
                    <div class="p-4 bg-gray-50 rounded-xl space-y-3 border border-gray-100 mt-6">
                        <p class="text-xs font-bold text-gray-700 uppercase tracking-wider">Validasi & Pengesahan Dokumen:</p>
                        
                        <label class="flex items-start gap-2.5 text-xs text-gray-600 cursor-pointer">
                            <input type="checkbox" name="is_data_benar" value="1" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 mt-0.5" required>
                            <span>Saya menyatakan bahwa data kependudukan (NIK dan Alamat) di atas adalah benar dan sesuai dengan kondisi aslinya.</span>
                        </label>

                        <label class="flex items-start gap-2.5 text-xs text-gray-600 cursor-pointer">
                            <input type="checkbox" name="is_setuju_ketentuan" value="1" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 mt-0.5" required>
                            <span>Warga menyetujui ketentuan dan kebijakan operasional program Bank Sampah RW.</span>
                        </label>

                        <label class="flex items-start gap-2.5 text-xs text-gray-600 cursor-pointer">
                            <input type="checkbox" name="is_disahkan_pengurus" value="1" class="rounded border-gray-300 text-emerald-600 focus:ring-emerald-500 mt-0.5" required>
                            <span><strong>[Pengesahan RW]</strong> Disahkan secara resmi oleh Pengurus RW untuk diteruskan ke sistem administrasi.</span>
                        </label>
                    </div>

                    <div class="flex justify-end gap-3 pt-4">
                        <a href="{{ route('rw.kelolaporan.index') }}" class="px-4 py-2 bg-gray-100 text-gray-700 rounded-xl text-xs font-bold hover:bg-gray-200 transition">Batal</a>
                        <button type="submit" class="px-5 py-2 bg-emerald-600 text-white rounded-xl text-xs font-bold hover:bg-emerald-700 transition shadow-sm">Simpan & Sahkan Laporan</button>
                    </div>

                </form>
            </div>

        </div>
    </div>
</x-app-layout>