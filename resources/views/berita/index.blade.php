<x-app-layout>
    <!-- Main Content -->
    <div class="p-4 md:p-6 bg-gray-50 min-h-screen pb-20">

        <!-- Heading & Flash Message -->
        <div class="mb-5 flex flex-col md:flex-row md:items-center md:justify-between gap-2">
            <div>
                <h1 class="text-xl md:text-2xl font-bold text-gray-800">Manajemen Berita</h1>
                <p class="text-gray-500 text-xs md:text-sm">Kelola data berita, kategori, dan publikasi artikel.</p>
            </div>
        </div>

        <!-- Notifikasi Sukses -->
        @if(session('success'))
            <div class="mb-4 bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-2xl shadow-sm text-xs md:text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Layout Grid Utama (Form di Kiri, Tabel di Kanan) -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 items-start">

            <!-- Card Form (Tambah / Edit Berita) -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 sticky top-6 max-h-[85vh] overflow-y-auto">
                <h2 class="text-base font-bold text-gray-800 mb-3">
                    {{ isset($editData) ? 'Edit Berita' : 'Tambah Berita' }}
                </h2>

                <form action="{{ isset($editData) ? route('berita.update', $editData->id) : route('berita.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf
                    @if(isset($editData))
                        @method('PUT')
                    @endif

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Judul Berita</label>
                        <input type="text" name="judul" value="{{ old('judul', $editData->judul ?? '') }}" required placeholder="Contoh: Kegiatan Bersih Lingkungan Serentak"
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs md:text-sm">
                        @error('judul')
                            <span class="text-xs text-red-500 mt-0.5 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Kategori</label>
                        <select name="kategori" required class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs md:text-sm bg-white">
                            <option value="">Pilih Kategori</option>
                            <option value="Pengumuman" {{ (old('kategori', $editData->kategori ?? '') == 'Pengumuman') ? 'selected' : '' }}>Pengumuman</option>
                            <option value="Kegiatan" {{ (old('kategori', $editData->kategori ?? '') == 'Kegiatan') ? 'selected' : '' }}>Kegiatan</option>
                            <option value="Edukasi" {{ (old('kategori', $editData->kategori ?? '') == 'Edukasi') ? 'selected' : '' }}>Edukasi</option>
                            <option value="Artikel" {{ (old('kategori', $editData->kategori ?? '') == 'Artikel') ? 'selected' : '' }}>Artikel</option>
                        </select>
                        @error('kategori')
                            <span class="text-xs text-red-500 mt-0.5 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Isi Berita / Konten</label>
                        <textarea name="konten" rows="4" required placeholder="Tulis isi berita di sini..."
                            class="w-full px-3.5 py-2.5 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-indigo-500 text-xs md:text-sm">{{ old('konten', $editData->konten ?? '') }}</textarea>
                        @error('konten')
                            <span class="text-xs text-red-500 mt-0.5 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div>
                        <label class="block text-xs font-medium text-gray-700 mb-1">Upload Gambar / Banner</label>
                        <input type="file" name="upload_image" accept="image/*"
                            class="w-full text-xs text-gray-500 file:mr-3 file:py-2 file:px-3 file:rounded-xl file:border-0 file:text-xs file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100 border border-gray-200 rounded-xl cursor-pointer">
                        @error('upload_image')
                            <span class="text-xs text-red-500 mt-0.5 block">{{ $message }}</span>
                        @enderror

                        @if(isset($editData) && $editData->upload_image)
                            <div class="mt-2">
                                <span class="text-[11px] text-gray-400 block mb-1">Gambar saat ini:</span>
                                <img src="{{ asset('storage/' . $editData->upload_image) }}" alt="Preview" class="h-16 w-16 object-cover rounded-xl border border-gray-200">
                            </div>
                        @endif
                    </div>

                    <div class="flex items-center gap-2 pt-2">
                        <button type="submit" class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-medium py-2.5 rounded-xl transition shadow-sm text-xs md:text-sm">
                            {{ isset($editData) ? 'Perbarui Berita' : 'Simpan Berita' }}
                        </button>
                        @if(isset($editData))
                            <a href="{{ route('berita.index') }}" class="px-3.5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl text-xs md:text-sm font-medium transition text-center">
                                Batal
                            </a>
                        @endif
                    </div>
                </form>
            </div>

            <!-- Card Tabel Daftar Berita (2 Kolom) -->
            <div class="lg:col-span-2 bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                <div class="flex items-center justify-between mb-4">
                    <h2 class="text-base font-bold text-gray-800">Daftar Berita</h2>
                    <span class="text-xs bg-indigo-50 text-indigo-700 font-semibold px-2.5 py-1 rounded-lg">
                        Total: {{ $beritas->count() }} Berita
                    </span>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-gray-100 text-gray-400 text-[11px] uppercase tracking-wider">
                                <th class="py-2.5 px-3 font-semibold w-10">No</th>
                                <th class="py-2.5 px-3 font-semibold">Gambar</th>
                                <th class="py-2.5 px-3 font-semibold">Judul Berita</th>
                                <th class="py-2.5 px-3 font-semibold">Kategori</th>
                                <th class="py-2.5 px-3 font-semibold text-center w-20">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="text-xs md:text-sm divide-y divide-gray-50">
                            @forelse($beritas as $index => $item)
                                <tr class="hover:bg-gray-50/50 transition">
                                    <td class="py-3 px-3 text-gray-500">{{ $index + 1 }}</td>
                                    <td class="py-3 px-3">
                                        @if($item->upload_image)
                                            <img src="{{ asset('storage/' . $item->upload_image) }}" alt="{{ $item->judul }}" class="h-10 w-10 object-cover rounded-lg border border-gray-200">
                                        @else
                                            <span class="text-[11px] text-gray-400 italic">No Image</span>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3 font-semibold text-gray-800">
                                        {{ $item->judul }}
                                        @if($item->konten)
                                            <p class="text-[11px] text-gray-400 font-normal mt-0.5">{{ Str::limit($item->konten, 35) }}</p>
                                        @endif
                                    </td>
                                    <td class="py-3 px-3">
                                        @php
                                            $badge = match($item->kategori) {
                                                'Pengumuman' => 'bg-blue-50 text-blue-600',
                                                'Kegiatan' => 'bg-emerald-50 text-emerald-600',
                                                'Edukasi' => 'bg-amber-50 text-amber-600',
                                                'Artikel' => 'bg-purple-50 text-purple-600',
                                                default => 'bg-gray-50 text-gray-600'
                                            };
                                        @endphp
                                        <span class="{{ $badge }} text-[11px] px-2.5 py-0.5 rounded-lg font-medium inline-block">
                                            {{ $item->kategori }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-3 text-center">
                                        <div class="inline-flex items-center gap-1.5">
                                            <a href="{{ route('berita.edit', $item->id) }}" class="p-1.5 bg-indigo-50 hover:bg-indigo-100 text-indigo-600 rounded-lg transition" title="Edit">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                                </svg>
                                            </a>
                                            
                                            <form action="{{ route('berita.destroy', $item->id) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus berita ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="p-1.5 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg transition" title="Hapus">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="py-6 text-center text-gray-400 text-xs">
                                        Belum ada data berita yang ditambahkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>