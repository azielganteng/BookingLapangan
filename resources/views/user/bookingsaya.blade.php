@extends('layouts.layout')

@section('title', 'Booking Saya')

@section('content')
<div class="space-y-6 max-w-4xl mx-auto">

    <div>
        <h1 class="text-xl sm:text-2xl font-bold text-zinc-900">Booking Saya</h1>
        <p class="text-zinc-500 text-xs sm:text-sm mt-1">Pantau status booking lapangan kamu</p>
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

    @php
        $tabs = ['semua' => 'Semua', 'pending' => 'Pending', 'confirmed' => 'Dikonfirmasi', 'completed' => 'Selesai', 'cancelled' => 'Dibatalkan'];
        $active = request('status', 'semua');
    @endphp
    <div class="flex flex-wrap gap-1.5 sm:gap-2">
        @foreach($tabs as $key => $label)
            <a href="{{ route('booking.index', $key !== 'semua' ? ['status' => $key] : []) }}"
               class="px-3 sm:px-4 py-1.5 rounded-full text-xs sm:text-sm font-medium border transition-colors
               {{ $active === $key ? 'bg-zinc-900 text-white border-zinc-900 shadow-xs' : 'bg-white text-zinc-600 border-zinc-200 hover:border-zinc-400' }}">
                {{ $label }}
                @if($key !== 'semua' && ($counts[$key] ?? 0) > 0)
                    <span class="ml-1 text-xs opacity-60">({{ $counts[$key] }})</span>
                @endif
            </a>
        @endforeach
    </div>

    @forelse($bookings as $booking)
        @php
            $durasi = \Carbon\Carbon::parse($booking->jam_mulai)->diffInHours(\Carbon\Carbon::parse($booking->jam_selesai));
            $totalHarga = $booking->lapangan ? ($booking->lapangan->harga_sewa * $durasi) : ($booking->total_harga ?? 0);
            
            $statusColor = match($booking->status) {
                'pending'   => 'bg-yellow-100 text-yellow-700',
                'confirmed' => 'bg-blue-100 text-blue-700',
                'completed' => 'bg-green-100 text-green-700',
                'cancelled' => 'bg-red-100 text-red-700',
                default     => 'bg-zinc-100 text-zinc-600',
            };
            $statusLabel = match($booking->status) {
                'pending'   => 'Menunggu Konfirmasi',
                'confirmed' => 'Dikonfirmasi ✓',
                'completed' => 'Selesai',
                'cancelled' => 'Dibatalkan',
                default     => $booking->status,
            };

            $waMessage = "Halo admin, saya ingin konfirmasi pembayaran booking:\n"
                . "ID Booking: #" . $booking->id . "\n"
                . "Lapangan: " . ($booking->lapangan->nama_lapangan ?? '-') . "\n"
                . "Tanggal: " . \Carbon\Carbon::parse($booking->tanggal)->format('d M Y') . "\n"
                . "Jam: " . \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') . " - " . \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') . "\n"
                . "Total: Rp " . number_format($totalHarga, 0, ',', '.');
            $waUrl = "https://wa.me/+6283851072814?text=" . urlencode($waMessage);
        @endphp

        <div class="bg-white border border-zinc-200 rounded-2xl overflow-hidden shadow-xs">

            @if($booking->status === 'confirmed')
                <div class="px-4 sm:px-5 py-2.5 bg-blue-50 border-b border-blue-100 text-xs sm:text-sm text-blue-700 font-medium flex items-center gap-2">
                    <svg class="w-4 h-4 shrink-0 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    Booking kamu telah dikonfirmasi oleh admin! Silakan hadir tepat waktu.
                </div>
            @endif

            <div class="flex items-start sm:items-center gap-3.5 sm:gap-4 p-4 sm:p-5">
                @if($booking->lapangan && $booking->lapangan->gambar_url)
                    <img src="{{ $booking->lapangan->gambar_url }}"
                         class="w-14 h-14 sm:w-20 sm:h-20 object-cover rounded-xl shrink-0 border border-zinc-100">
                @else
                    <div class="w-14 h-14 sm:w-20 sm:h-20 bg-zinc-100 rounded-xl flex items-center justify-center text-zinc-400 shrink-0 border border-zinc-100">
                        <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                        </svg>
                    </div>
                @endif

                <div class="flex-1 min-w-0">
                    <div class="flex flex-col sm:flex-row sm:items-start sm:justify-between gap-1 sm:gap-2">
                        <div class="min-w-0">
                            <p class="font-bold text-zinc-900 text-sm sm:text-base truncate">
                                {{ $booking->lapangan->nama_lapangan ?? 'Lapangan' }}
                            </p>
                            <p class="text-[11px] sm:text-xs text-zinc-400 mt-0.5">
                                {{ $booking->lapangan->jenisLapangan->nama_jenis ?? '-' }}
                            </p>
                        </div>
                        <span class="text-xs font-semibold px-2.5 py-1 rounded-full {{ $statusColor }} self-start sm:self-auto shrink-0 mt-1 sm:mt-0">
                            {{ $statusLabel }}
                        </span>
                    </div>

                    <div class="flex flex-wrap gap-x-3.5 gap-y-1 mt-2 text-xs text-zinc-600">
                        <span class="font-medium">
                            📅 {{ \Carbon\Carbon::parse($booking->tanggal)->format('d M Y') }}
                        </span>
                        <span>
                            ⏰ {{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }} – {{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }} ({{ $durasi }} jam)
                        </span>
                        <span class="text-green-600 font-bold">
                            Rp {{ number_format($totalHarga, 0, ',', '.') }}
                        </span>
                    </div>
                </div>
            </div>

            <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-2 px-4 sm:px-5 py-3 bg-zinc-50 border-t border-zinc-100 text-xs">
                <span class="text-zinc-400">Dipesan {{ $booking->created_at ? $booking->created_at->diffForHumans() : '-' }}</span>
                <div class="flex items-center gap-2 flex-wrap">
                    @if($booking->status === 'pending')
                        <a href="{{ $waUrl }}" target="_blank"
                           class="inline-flex items-center gap-1 px-3 py-1.5 font-semibold text-green-700 bg-green-100 hover:bg-green-200 rounded-lg transition-colors">
                            <span>Konfirmasi WA</span>
                        </a>
                        <form action="{{ route('booking.destroy', $booking->id) }}" method="POST"
                              onsubmit="return confirm('Yakin ingin membatalkan booking ini?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="px-3 py-1.5 font-semibold text-red-600 bg-white border border-red-200 rounded-lg hover:bg-red-50 transition-colors">
                                Batalkan
                            </button>
                        </form>
                    @endif

                    @if(in_array($booking->status, ['completed', 'cancelled']) && $booking->lapangan)
                        <a href="{{ route('booking.create', $booking->lapangan->id) }}"
                           class="px-3 py-1.5 font-semibold text-zinc-700 bg-white border border-zinc-200 rounded-lg hover:bg-zinc-100 transition-colors">
                            Booking Lagi
                        </a>
                    @endif
                </div>
            </div>
        </div>

    @empty
        <div class="flex flex-col items-center justify-center py-16 bg-white border border-zinc-200 rounded-2xl text-center px-4 shadow-xs">
            <div class="w-14 h-14 rounded-full bg-zinc-100 flex items-center justify-center text-2xl mb-3">
                📋
            </div>
            <p class="text-zinc-700 font-bold text-base">Belum ada booking</p>
            <p class="text-zinc-400 text-xs sm:text-sm mt-1">Kamu belum memiliki riwayat booking untuk filter ini.</p>
            <a href="{{ route('user.cari-lapangan') }}"
               class="mt-4 px-5 py-2.5 bg-zinc-900 text-white text-xs sm:text-sm font-semibold rounded-xl hover:bg-zinc-700 transition-colors shadow-xs">
                Cari Lapangan Sekarang
            </a>
        </div>
    @endforelse

    @if($bookings->hasPages())
        <div class="mt-4">{{ $bookings->links() }}</div>
    @endif

</div>
@endsection

