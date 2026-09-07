@extends('layouts.layout')

@section('title', 'Kategori Lapangan')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-zinc-900">Kategori Lapangan</h1>
            <p class="text-zinc-500 text-xs sm:text-sm mt-1">Kelola daftar kategori atau jenis olahraga</p>
        </div>

        <div class="flex items-center gap-2.5">
            <a href="{{ route('tambah-jenis') }}"
               class="inline-flex items-center gap-2 bg-zinc-900 hover:bg-zinc-700 text-white px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl shadow-xs text-xs sm:text-sm font-semibold transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Kategori
            </a>
            <a href="{{ route('admin.semua-lapangan') }}"
               class="inline-flex items-center gap-2 border border-zinc-200 hover:bg-zinc-50 text-zinc-700 px-3.5 sm:px-4 py-2 sm:py-2.5 rounded-xl text-xs sm:text-sm font-semibold transition-colors">
                Semua Lapangan
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-xs sm:text-sm flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-xs sm:text-sm flex items-center gap-2">
            <svg class="w-4 h-4 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    <div class="bg-white shadow-xs border border-zinc-200 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs sm:text-sm">
                <thead class="bg-zinc-50 text-zinc-500 uppercase text-[11px] font-semibold border-b border-zinc-200">
                    <tr>
                        <th class="py-3 px-4 whitespace-nowrap w-16">No</th>
                        <th class="py-3 px-4 whitespace-nowrap">Nama Kategori Lapangan</th>
                        <th class="py-3 px-4 text-right whitespace-nowrap w-28">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @forelse ($jenisLapangan as $index => $jenis)
                    <tr class="hover:bg-zinc-50/80 transition-colors">
                        <td class="py-3.5 px-4 text-zinc-400 font-medium">{{ $index + 1 }}</td>
                        <td class="py-3.5 px-4 font-semibold text-zinc-900">{{ $jenis->nama_jenis }}</td>
                        <td class="py-3.5 px-4 text-right whitespace-nowrap">
                            <form action="{{ route('hapus-jenis', $jenis->id) }}" method="POST"
                                  onsubmit="return confirm('Yakin hapus kategori ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="px-2.5 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 rounded-lg text-xs font-semibold transition-colors">
                                    Hapus
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" class="text-center py-10 text-zinc-400">
                            Belum ada data kategori
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

