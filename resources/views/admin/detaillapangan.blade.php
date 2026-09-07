@extends('layouts.layout')

@section('title', 'Detail ' . $lapangan->nama_lapangan)

@section('content')

<div class="p-4 sm:p-8">

    {{-- ── Top Navigation Bar ── --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-zinc-900">{{ $lapangan->nama_lapangan }}</h1>
            <p class="text-zinc-500 text-sm mt-1">
                Kategori: <span class="font-semibold text-zinc-800">{{ $lapangan->jenisLapangan->nama_jenis ?? '-' }}</span> &bull;
                ID: LPG-{{ str_pad($lapangan->id, 3, '0', STR_PAD_LEFT) }}
            </p>
        </div>
        <div class="flex items-center gap-2">
            <a href="{{ route('edit-lapangan', $lapangan->id) }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 bg-zinc-900 hover:bg-zinc-700 text-white rounded-xl text-sm font-semibold transition-colors">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                </svg>
                Edit
            </a>
            <a href="{{ route('admin.semua-lapangan') }}"
               class="inline-flex items-center gap-1.5 px-4 py-2 border border-zinc-200 rounded-xl text-sm font-semibold text-zinc-600 hover:bg-zinc-50 transition-colors">
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                </svg>
                Kembali
            </a>
        </div>
    </div>

    {{-- ── Stat Cards ── --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
        <div class="bg-white border border-zinc-200 rounded-2xl p-4 sm:p-5 shadow-sm">
            <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wide">Total Booking</p>
            <p class="text-2xl font-bold text-blue-600 mt-2">{{ $totalBooking ?? 0 }}</p>
        </div>
        <div class="bg-white border border-zinc-200 rounded-2xl p-4 sm:p-5 shadow-sm">
            <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wide">Jam Terpakai</p>
            <p class="text-2xl font-bold text-orange-600 mt-2">{{ $jamTerpakai ?? 0 }} Jam</p>
        </div>
        <div class="bg-white border border-zinc-200 rounded-2xl p-4 sm:p-5 shadow-sm">
            <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wide">Harga Sewa</p>
            <p class="text-2xl font-bold text-green-600 mt-2">Rp {{ number_format($lapangan->harga_sewa, 0, ',', '.') }}<span class="text-xs text-zinc-400 font-normal">/jam</span></p>
        </div>
        <div class="bg-white border border-zinc-200 rounded-2xl p-4 sm:p-5 shadow-sm">
            <p class="text-xs font-semibold text-zinc-400 uppercase tracking-wide">Total Pendapatan</p>
            <p class="text-2xl font-bold text-purple-600 mt-2">Rp {{ number_format($totalPendapatan ?? 0, 0, ',', '.') }}</p>
        </div>
    </div>

    {{-- ── Detail Content Grid ── --}}
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        {{-- Left: Foto & Info Utama --}}
        <div class="bg-white border border-zinc-200 rounded-2xl overflow-hidden shadow-sm">
            <div class="w-full h-64 bg-zinc-100 flex items-center justify-center relative">
                @if($lapangan->gambar_url)
                    <img src="{{ $lapangan->gambar_url }}"
                         alt="Foto {{ $lapangan->nama_lapangan }}"
                         class="w-full h-64 object-cover">
                @else
                    <div class="flex flex-col items-center gap-2 text-zinc-400">
                        <svg class="w-12 h-12" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                        <span class="text-xs">Belum ada foto</span>
                    </div>
                @endif

                <div class="absolute top-3 right-3">
                    @if(strtolower($lapangan->status ?? 'tersedia') === 'tersedia')
                        <span class="px-3 py-1 bg-green-500 text-white font-bold text-xs rounded-full shadow">Tersedia</span>
                    @else
                        <span class="px-3 py-1 bg-red-500 text-white font-bold text-xs rounded-full shadow">Penuh / Ditutup</span>
                    @endif
                </div>
            </div>

            <div class="p-5 divide-y divide-zinc-100 text-sm">
                <div class="py-3 flex items-center justify-between first:pt-0">
                    <span class="text-zinc-400">Jenis Lapangan</span>
                    <span class="font-semibold text-zinc-800 bg-zinc-100 px-3 py-1 rounded-lg text-xs">{{ $lapangan->jenisLapangan->nama_jenis ?? '-' }}</span>
                </div>
                <div class="py-3 flex items-center justify-between">
                    <span class="text-zinc-400">Jam Operasional</span>
                    <span class="font-medium text-zinc-700">
                        {{ \Illuminate\Support\Str::substr($lapangan->jam_buka, 0, 5) }} – {{ \Illuminate\Support\Str::substr($lapangan->jam_tutup, 0, 5) }}
                    </span>
                </div>
                <div class="py-3 flex items-center justify-between">
                    <span class="text-zinc-400">Status</span>
                    <span class="font-medium {{ strtolower($lapangan->status ?? 'tersedia') === 'tersedia' ? 'text-green-600' : 'text-red-600' }}">
                        {{ $lapangan->status ?? 'Tersedia' }}
                    </span>
                </div>
                <div class="py-3 flex items-center justify-between last:pb-0">
                    <span class="text-zinc-400">Dibuat Pada</span>
                    <span class="font-medium text-zinc-700">{{ \Carbon\Carbon::parse($lapangan->created_at)->format('d M Y') }}</span>
                </div>
            </div>
        </div>

        {{-- Right: Deskripsi & Booking Terakhir --}}
        <div class="lg:col-span-2 space-y-6">

            {{-- Card Deskripsi --}}
            <div class="bg-white border border-zinc-200 rounded-2xl p-6 shadow-sm">
                <h2 class="text-base font-bold text-zinc-900 mb-3">Deskripsi & Fasilitas</h2>
                <div class="text-sm text-zinc-600 leading-relaxed bg-zinc-50 border border-zinc-100 rounded-xl p-4">
                    {{ $lapangan->deskripsi_lapangan ?? 'Tidak ada deskripsi tambahan untuk lapangan ini.' }}
                </div>
            </div>

            {{-- Card Booking Terbaru di Lapangan Ini --}}
            <div class="bg-white border border-zinc-200 rounded-2xl overflow-hidden shadow-sm">
                <div class="px-6 py-4 border-b border-zinc-100 flex items-center justify-between">
                    <h2 class="text-base font-bold text-zinc-900">Riwayat Booking Terbaru</h2>
                    <a href="{{ route('admin.daftar-booking') }}" class="text-xs text-blue-600 hover:underline">Semua Booking &rarr;</a>
                </div>

                <div class="divide-y divide-zinc-50">
                    @forelse($recentBookings ?? [] as $b)
                        <div class="px-6 py-3.5 flex items-center justify-between hover:bg-zinc-50 transition-colors">
                            <div>
                                <p class="text-sm font-semibold text-zinc-800">{{ $b->user->name ?? 'Pengguna' }}</p>
                                <p class="text-xs text-zinc-400 mt-0.5">
                                    {{ \Carbon\Carbon::parse($b->tanggal)->format('d M Y') }} &bull;
                                    {{ \Carbon\Carbon::parse($b->jam_mulai)->format('H:i') }} - {{ \Carbon\Carbon::parse($b->jam_selesai)->format('H:i') }}
                                </p>
                            </div>
                            <div>
                                @php
                                    $stColor = match($b->status) {
                                        'pending' => 'bg-yellow-100 text-yellow-700',
                                        'confirmed' => 'bg-blue-100 text-blue-700',
                                        'completed' => 'bg-green-100 text-green-700',
                                        default => 'bg-red-100 text-red-700'
                                    };
                                @endphp
                                <span class="px-2.5 py-1 text-xs font-semibold rounded-full {{ $stColor }}">
                                    {{ ucfirst($b->status) }}
                                </span>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-zinc-400 text-sm">
                            Belum ada riwayat booking untuk lapangan ini
                        </div>
                    @endforelse
                </div>
            </div>

        </div>

    </div>

</div>

@endsection