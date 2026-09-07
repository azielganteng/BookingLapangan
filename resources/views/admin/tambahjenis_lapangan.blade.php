@extends('layouts.layout')

@section('title', 'Tambah Kategori Lapangan')

@section('content')
<div class="space-y-6 max-w-xl mx-auto">

    <div>
        <h1 class="text-xl sm:text-2xl font-bold text-zinc-900">Tambah Kategori Lapangan</h1>
        <p class="text-zinc-500 text-xs sm:text-sm mt-1">Buat jenis atau kategori lapangan baru</p>
    </div>

    <div class="bg-white border border-zinc-200 shadow-xs rounded-2xl p-5 sm:p-6">

        <form action="{{ route('store-jenis') }}" method="POST" class="space-y-4">
            @csrf

            <div>
                <label for="nama_jenis" class="block text-xs sm:text-sm font-semibold text-zinc-700 mb-1.5">
                    Nama Kategori Lapangan <span class="text-red-500">*</span>
                </label>

                <input type="text"
                       name="nama_jenis"
                       id="nama_jenis"
                       required
                       placeholder="Contoh: Futsal, Badminton, Basket, Tenis"
                       class="w-full border border-zinc-200 rounded-xl px-4 py-2.5 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900 bg-white">
            </div>

            <div class="flex items-center gap-2 pt-2">
                <button type="submit"
                        class="px-5 py-2.5 bg-zinc-900 hover:bg-zinc-700 text-white rounded-xl text-xs sm:text-sm font-semibold transition-colors">
                    Simpan Kategori
                </button>
                <a href="{{ route('jenis-lapangan') }}"
                   class="px-4 py-2.5 border border-zinc-200 hover:bg-zinc-50 text-zinc-700 rounded-xl text-xs sm:text-sm font-semibold transition-colors">
                    Batal
                </a>
            </div>

        </form>

    </div>

</div>
@endsection