@extends('layouts.layout')

@section('title', 'Edit Lapangan - ' . $lapangan->nama_lapangan)

@section('content')

<div class="p-4 sm:p-8">

    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900">Edit Lapangan</h1>
            <p class="text-zinc-500 text-sm mt-1">Perbarui informasi dan status lapangan</p>
        </div>
        <a href="{{ route('admin.semua-lapangan') }}"
           class="inline-flex items-center gap-2 px-4 py-2 border border-zinc-200 rounded-xl text-sm font-semibold text-zinc-600 hover:bg-zinc-50 transition-colors">
            <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
            </svg>
            Kembali
        </a>
    </div>

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-5 text-sm">
            <ul class="list-disc ml-4 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-2xl border border-zinc-100 shadow-sm p-4 sm:p-8">

        <form action="{{ route('update-lapangan', $lapangan->id) }}" method="POST" enctype="multipart/form-data" class="space-y-5">
            @csrf
            @method('PATCH')

            {{-- Nama Lapangan --}}
            <div>
                <label for="nama_lapangan" class="block text-sm font-semibold text-zinc-700 mb-1.5">
                    Nama Lapangan <span class="text-red-500">*</span>
                </label>
                <input type="text" id="nama_lapangan" name="nama_lapangan"
                       value="{{ old('nama_lapangan', $lapangan->nama_lapangan) }}"
                       required
                       placeholder="Contoh: Lapangan Futsal A"
                       class="w-full px-4 py-2.5 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900 bg-white @error('nama_lapangan') border-red-400 @enderror">
            </div>

            {{-- Jenis & Status Grid --}}
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                {{-- Jenis Lapangan --}}
                <div>
                    <label for="jenis_lapangan" class="block text-sm font-semibold text-zinc-700 mb-1.5">
                        Jenis Lapangan <span class="text-red-500">*</span>
                    </label>
                    <select id="jenis_lapangan" name="jenis_lapangan" required
                            class="w-full px-4 py-2.5 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900 bg-white text-zinc-700 @error('jenis_lapangan') border-red-400 @enderror">
                        @foreach ($jenis_lapangan as $jenis)
                            <option value="{{ $jenis->id }}"
                                {{ old('jenis_lapangan', $lapangan->jenis_lapangan) == $jenis->id ? 'selected' : '' }}>
                                {{ $jenis->nama_jenis }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Status --}}
                <div>
                    <label for="status" class="block text-sm font-semibold text-zinc-700 mb-1.5">
                        Status Lapangan <span class="text-red-500">*</span>
                    </label>
                    <select id="status" name="status" required
                            class="w-full px-4 py-2.5 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900 bg-white text-zinc-700 @error('status') border-red-400 @enderror">
                        <option value="Tersedia" {{ old('status', $lapangan->status) == 'Tersedia' ? 'selected' : '' }}>Tersedia</option>
                        <option value="Penuh" {{ old('status', $lapangan->status) == 'Penuh' ? 'selected' : '' }}>Penuh / Perbaikan</option>
                    </select>
                </div>
            </div>

            {{-- Harga Sewa --}}
            <div>
                <label for="harga_sewa" class="block text-sm font-semibold text-zinc-700 mb-1.5">
                    Harga Sewa <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-sm text-zinc-400 font-medium select-none">Rp</span>
                    <input type="number" id="harga_sewa" name="harga_sewa"
                           value="{{ old('harga_sewa', $lapangan->harga_sewa) }}"
                           required min="0"
                           placeholder="50000"
                           class="w-full pl-10 pr-14 py-2.5 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900 bg-white @error('harga_sewa') border-red-400 @enderror">
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-sm text-zinc-400 select-none">/jam</span>
                </div>
            </div>

            {{-- Jam Operasional --}}
            <div>
                <label class="block text-sm font-semibold text-zinc-700 mb-1.5">
                    Jam Operasional <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 gap-3">
                    <div>
                        <label for="jam_buka" class="block text-xs text-zinc-500 mb-1">Jam Buka</label>
                        <input type="time" id="jam_buka" name="jam_buka"
                               value="{{ old('jam_buka', \Illuminate\Support\Str::substr($lapangan->jam_buka, 0, 5)) }}"
                               required
                               class="w-full px-4 py-2.5 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900 bg-white @error('jam_buka') border-red-400 @enderror">
                    </div>
                    <div>
                        <label for="jam_tutup" class="block text-xs text-zinc-500 mb-1">Jam Tutup</label>
                        <input type="time" id="jam_tutup" name="jam_tutup"
                               value="{{ old('jam_tutup', \Illuminate\Support\Str::substr($lapangan->jam_tutup, 0, 5)) }}"
                               required
                               class="w-full px-4 py-2.5 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900 bg-white @error('jam_tutup') border-red-400 @enderror">
                    </div>
                </div>
            </div>

            {{-- Deskripsi --}}
            <div>
                <label for="deskripsi_lapangan" class="block text-sm font-semibold text-zinc-700 mb-1.5">Deskripsi Lapangan</label>
                <textarea id="deskripsi_lapangan" name="deskripsi_lapangan" rows="4"
                          placeholder="Deskripsikan fasilitas dan kondisi lapangan..."
                          class="w-full px-4 py-2.5 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900 bg-white resize-none">{{ old('deskripsi_lapangan', $lapangan->deskripsi_lapangan) }}</textarea>
            </div>

            {{-- Gambar Saat Ini & Ganti Gambar --}}
            <div>
                <label class="block text-sm font-semibold text-zinc-700 mb-1.5">Gambar Lapangan</label>

                <div class="flex flex-col sm:flex-row gap-4 items-start">
                    @if($lapangan->gambar_url)
                        <div class="relative rounded-xl overflow-hidden border border-zinc-200 w-36 h-28 shrink-0 bg-zinc-100">
                            <img src="{{ $lapangan->gambar_url }}" alt="Foto saat ini" class="w-full h-full object-cover">
                            <span class="absolute bottom-1 left-1 right-1 bg-black/60 text-white text-[10px] text-center py-0.5 rounded">Saat Ini</span>
                        </div>
                    @endif

                    <label for="gambar_lapangan"
                           class="flex-1 flex flex-col items-center justify-center w-full h-28 border-2 border-dashed border-zinc-200 rounded-xl cursor-pointer hover:border-zinc-400 hover:bg-zinc-50 transition-colors">
                        <div id="dropPlaceholder" class="flex flex-col items-center gap-1 text-center px-4">
                            <svg class="w-6 h-6 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5"
                                      d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-xs text-zinc-600 font-medium">Klik untuk ganti gambar (opsional)</p>
                            <p class="text-[11px] text-zinc-400">JPG, PNG, WebP · Maks. 3 MB</p>
                        </div>
                        <img id="previewImg" src="" alt="Preview" class="hidden h-24 w-auto rounded-lg object-cover">
                    </label>
                    <input type="file" id="gambar_lapangan" name="gambar_lapangan"
                           accept="image/*" class="hidden">
                </div>
            </div>

            {{-- Tombol Aksi --}}
            <div class="flex gap-3 pt-4 border-t border-zinc-100">
                <button type="submit"
                        class="px-6 py-2.5 bg-zinc-900 hover:bg-zinc-700 text-white text-sm font-bold rounded-xl transition-colors">
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.semua-lapangan') }}"
                   class="px-6 py-2.5 border border-zinc-200 text-zinc-600 text-sm font-semibold rounded-xl hover:bg-zinc-50 transition-colors">
                    Batal
                </a>
            </div>

        </form>

    </div>

</div>

<script>
    document.getElementById('gambar_lapangan').addEventListener('change', function () {
        const file = this.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (e) {
            const preview     = document.getElementById('previewImg');
            const placeholder = document.getElementById('dropPlaceholder');
            preview.src       = e.target.result;
            preview.classList.remove('hidden');
            placeholder.classList.add('hidden');
        };
        reader.readAsDataURL(file);
    });
</script>

@endsection
