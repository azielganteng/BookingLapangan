@extends("layouts.layout")

@section('title', 'Daftar Lapangan')

@section('content')
<div class="p-4 sm:p-8">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900">Kelola Lapangan</h1>
            <p class="text-zinc-500 text-sm mt-1">Daftar semua lapangan yang terdaftar dalam sistem</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="{{ route('admin.tambah-lapangan') }}"
               class="inline-flex items-center gap-2 bg-zinc-900 hover:bg-zinc-700 text-white px-4 py-2.5 rounded-xl shadow-sm text-sm font-semibold transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Tambah Lapangan
            </a>
            <a href="{{ route('jenis-lapangan') }}"
               class="inline-flex items-center gap-2 border border-zinc-200 hover:bg-zinc-50 text-zinc-700 px-4 py-2.5 rounded-xl text-sm font-semibold transition-colors">
                Kategori
            </a>
        </div>
    </div>

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="mb-5 px-4 py-3 bg-green-50 border border-green-200 text-green-700 rounded-xl text-sm flex items-center gap-2">
            <svg class="w-5 h-5 shrink-0 text-green-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="mb-5 px-4 py-3 bg-red-50 border border-red-200 text-red-700 rounded-xl text-sm flex items-center gap-2">
            <svg class="w-5 h-5 shrink-0 text-red-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            {{ session('error') }}
        </div>
    @endif

    {{-- Search & Filter --}}
    <form method="GET" action="{{ route('admin.semua-lapangan') }}" class="flex flex-col sm:flex-row gap-3 mb-6">
        <div class="flex-1 relative">
            <svg class="absolute left-3.5 top-1/2 -translate-y-1/2 w-4 h-4 text-zinc-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
            </svg>
            <input type="text" name="search" value="{{ request('search') }}"
                   placeholder="Cari nama lapangan..."
                   class="w-full pl-10 pr-4 py-2.5 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900 bg-white">
        </div>

        <div class="flex gap-2">
            <select name="jenis"
                    class="px-4 py-2.5 border border-zinc-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900 bg-white text-zinc-700">
                <option value="">Semua Kategori</option>
                @foreach($jenis_lapangan as $jenis)
                    <option value="{{ $jenis->id }}" {{ request('jenis') == $jenis->id ? 'selected' : '' }}>
                        {{ $jenis->nama_jenis }}
                    </option>
                @endforeach
            </select>

            <button type="submit"
                    class="px-5 py-2.5 bg-zinc-900 text-white text-sm font-semibold rounded-xl hover:bg-zinc-700 transition-colors">
                Filter
            </button>

            @if(request('search') || request('jenis'))
                <a href="{{ route('admin.semua-lapangan') }}"
                   class="px-4 py-2.5 border border-zinc-200 text-zinc-600 text-sm font-semibold rounded-xl hover:bg-zinc-50 transition-colors">
                    Reset
                </a>
            @endif
        </div>
    </form>

    {{-- Tabel Lapangan --}}
    <div class="bg-white shadow-sm border border-zinc-200 rounded-2xl overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-sm text-left">
                <thead class="bg-zinc-50 text-zinc-500 uppercase text-xs font-semibold border-b border-zinc-200">
                    <tr>
                        <th class="px-5 py-3.5 whitespace-nowrap">Lapangan</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Kategori</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Jam Operasional</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Harga Sewa</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Status</th>
                        <th class="px-5 py-3.5 text-right whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-zinc-100 text-zinc-700">
                    @forelse ($lapangan as $item)
                    <tr class="hover:bg-zinc-50 transition-colors">
                        <td class="px-5 py-4">
                            <div class="flex items-center gap-3.5">
                                @if($item->gambar_url)
                                    <img src="{{ $item->gambar_url }}"
                                         alt="{{ $item->nama_lapangan }}"
                                         class="w-14 h-12 rounded-lg object-cover shrink-0 border border-zinc-100">
                                @else
                                    <div class="w-14 h-12 bg-zinc-100 rounded-lg flex items-center justify-center text-zinc-400 shrink-0 border border-zinc-100">
                                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                        </svg>
                                    </div>
                                @endif
                                <div>
                                    <p class="font-bold text-zinc-900 text-sm">{{ $item->nama_lapangan }}</p>
                                    <p class="text-xs text-zinc-400 mt-0.5 line-clamp-1 max-w-xs">{{ $item->deskripsi_lapangan ?? 'Tidak ada deskripsi' }}</p>
                                </div>
                            </div>
                        </td>

                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="bg-zinc-100 text-zinc-700 font-medium px-2.5 py-1 rounded-lg text-xs">
                                {{ $item->jenisLapangan->nama_jenis ?? '-' }}
                            </span>
                        </td>

                        <td class="px-4 py-4 text-xs font-medium text-zinc-600 whitespace-nowrap">
                            {{ \Illuminate\Support\Str::substr($item->jam_buka, 0, 5) }} – {{ \Illuminate\Support\Str::substr($item->jam_tutup, 0, 5) }}
                        </td>

                        <td class="px-4 py-4 font-bold text-green-600 whitespace-nowrap text-sm">
                            Rp {{ number_format($item->harga_sewa, 0, ',', '.') }}<span class="text-xs text-zinc-400 font-normal">/jam</span>
                        </td>

                        <td class="px-4 py-4 whitespace-nowrap">
                            @if(strtolower($item->status ?? 'tersedia') === 'tersedia')
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-700">
                                    Tersedia
                                </span>
                            @else
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-700">
                                    Penuh
                                </span>
                            @endif
                        </td>

                        <td class="px-5 py-4 text-right">
                            <div class="flex items-center justify-end gap-1.5 flex-wrap">
                                <a href="{{ route('detail-lapangan', $item->id) }}"
                                   class="px-2.5 py-1.5 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 rounded-lg text-xs font-semibold transition-colors">
                                    Detail
                                </a>

                                <a href="{{ route('edit-lapangan', $item->id) }}"
                                   class="px-2.5 py-1.5 bg-blue-50 hover:bg-blue-100 text-blue-600 border border-blue-200 rounded-lg text-xs font-semibold transition-colors">
                                    Edit
                                </a>

                                <form action="{{ route('delete-lapangan', $item->id) }}"
                                      method="POST"
                                      onsubmit="return confirm('Yakin ingin menghapus lapangan ini?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                            class="px-2.5 py-1.5 bg-red-50 hover:bg-red-100 text-red-600 border border-red-200 rounded-lg text-xs font-semibold transition-colors">
                                        Hapus
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="text-center py-12 text-zinc-400">
                            Tidak ada data lapangan yang ditemukan
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($lapangan->hasPages())
            <div class="p-4 border-t border-zinc-100">
                {{ $lapangan->links() }}
            </div>
        @endif
    </div>

</div>
@endsection
