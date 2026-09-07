@extends('layouts.layout')

@section('title', 'Dashboard')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    {{-- Header --}}
    <div>
        <h1 class="text-xl sm:text-2xl font-bold text-zinc-900">Halo, {{ Auth::user()->name }}</h1>
        <p class="text-zinc-500 text-xs sm:text-sm mt-1">Selamat datang di aplikasi booking lapangan</p>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white border border-zinc-200 rounded-2xl p-4 sm:p-5 shadow-xs flex flex-col justify-between">
            <p class="text-xs text-zinc-400 font-medium">Total Booking</p>
            <p class="text-xl sm:text-2xl font-bold text-zinc-900 mt-1">{{ $totalBooking }}</p>
        </div>
        <div class="bg-yellow-50 border border-yellow-100 rounded-2xl p-4 sm:p-5 shadow-xs flex flex-col justify-between">
            <p class="text-xs text-yellow-600 font-medium">Menunggu Konfirmasi</p>
            <p class="text-xl sm:text-2xl font-bold text-yellow-700 mt-1">{{ $totalPending }}</p>
        </div>
        <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 sm:p-5 shadow-xs flex flex-col justify-between">
            <p class="text-xs text-blue-600 font-medium">Dikonfirmasi</p>
            <p class="text-xl sm:text-2xl font-bold text-blue-700 mt-1">{{ $totalConfirmed }}</p>
        </div>
        <div class="bg-green-50 border border-green-100 rounded-2xl p-4 sm:p-5 shadow-xs flex flex-col justify-between">
            <p class="text-xs text-green-600 font-medium">Selesai</p>
            <p class="text-xl sm:text-2xl font-bold text-green-700 mt-1">{{ $totalCompleted }}</p>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">

        {{-- Booking Terbaru --}}
        <div class="lg:col-span-2 bg-white border border-zinc-200 rounded-2xl overflow-hidden shadow-xs">
            <div class="flex items-center justify-between px-4 sm:px-5 py-3.5 border-b border-zinc-100">
                <h2 class="font-bold text-zinc-900 text-sm sm:text-base">Booking Terbaru</h2>
                <a href="{{ route('booking.index') }}" class="text-xs text-zinc-500 hover:text-zinc-900 font-medium">Lihat semua →</a>
            </div>

            @forelse($bookingTerbaru as $booking)
                @php
                    $statusColor = match($booking->status) {
                        'pending'   => 'bg-yellow-100 text-yellow-700',
                        'confirmed' => 'bg-blue-100 text-blue-700',
                        'completed' => 'bg-green-100 text-green-700',
                        'cancelled' => 'bg-red-100 text-red-700',
                        default     => 'bg-zinc-100 text-zinc-600',
                    };
                    $statusLabel = match($booking->status) {
                        'pending'   => 'Menunggu',
                        'confirmed' => 'Dikonfirmasi',
                        'completed' => 'Selesai',
                        'cancelled' => 'Dibatalkan',
                        default     => $booking->status,
                    };
                @endphp
                <div class="flex items-center justify-between px-4 sm:px-5 py-3.5 border-b border-zinc-100 last:border-b-0 hover:bg-zinc-50/80 transition-colors">
                    <div class="min-w-0 flex-1 mr-3">
                        <p class="text-xs sm:text-sm font-semibold text-zinc-900 truncate">{{ $booking->lapangan->nama_lapangan }}</p>
                        <p class="text-[11px] sm:text-xs text-zinc-400 mt-0.5">
                            {{ \Carbon\Carbon::parse($booking->tanggal)->format('d M Y') }}
                            · {{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }}–{{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }}
                        </p>
                    </div>
                    <span class="text-xs font-semibold px-2.5 py-1 rounded-full shrink-0 {{ $statusColor }}">
                        {{ $statusLabel }}
                    </span>
                </div>
            @empty
                <div class="px-5 py-10 text-center text-zinc-400 text-xs sm:text-sm">
                    Belum ada booking
                </div>
            @endforelse
        </div>

        {{-- Shortcut --}}
        <div class="flex flex-col gap-4">
            <div class="bg-white border border-zinc-200 rounded-2xl p-4 sm:p-5 shadow-xs">
                <h2 class="font-bold text-zinc-900 mb-3 text-sm sm:text-base">Menu Cepat</h2>
                <div class="flex flex-col gap-2">
                    <a href="{{ route('user.cari-lapangan') }}"
                       class="flex items-center gap-3 px-4 py-3 bg-zinc-900 text-white rounded-xl hover:bg-zinc-700 transition-colors text-xs sm:text-sm font-semibold shadow-xs">
                        <svg class="w-4 h-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/></svg>
                        Cari Lapangan
                    </a>
                    <a href="{{ route('booking.index') }}"
                       class="flex items-center gap-3 px-4 py-3 bg-white border border-zinc-200 text-zinc-700 rounded-xl hover:bg-zinc-50 transition-colors text-xs sm:text-sm font-semibold">
                        <svg class="w-4 h-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/></svg>
                        Booking Saya
                    </a>
                </div>
            </div>

            {{-- Info booking dikonfirmasi --}}
            @if($totalConfirmed > 0)
                <div class="bg-blue-50 border border-blue-100 rounded-2xl p-4 sm:p-5 shadow-xs">
                    <p class="text-xs sm:text-sm font-bold text-blue-800">🎉 Ada {{ $totalConfirmed }} booking dikonfirmasi!</p>
                    <p class="text-[11px] sm:text-xs text-blue-600 mt-1">Booking kamu sudah siap. Jangan lupa datang tepat waktu.</p>
                    <a href="{{ route('booking.index', ['status' => 'confirmed']) }}"
                       class="inline-block mt-2.5 text-xs font-semibold text-blue-700 underline">
                        Lihat detail →
                    </a>
                </div>
            @endif
        </div>

    </div>
</div>
@endsection

