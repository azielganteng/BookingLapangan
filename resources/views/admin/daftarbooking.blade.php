@extends('layouts.layout')

@section('title', 'Daftar Booking')

@section('content')

<div class="space-y-6 max-w-7xl mx-auto">

    <div>
        <h1 class="text-xl sm:text-2xl font-bold text-zinc-900">Daftar Booking</h1>
        <p class="text-zinc-500 text-xs sm:text-sm mt-1">Kelola semua booking dari seluruh pengguna</p>
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

    <div class="grid grid-cols-2 sm:grid-cols-4 gap-3 sm:gap-4">
        @php
            $cardData = [
                ['label' => 'Total Booking', 'value' => $counts->sum(), 'color' => 'bg-zinc-100 text-zinc-700'],
                ['label' => 'Pending', 'value' => $counts['pending'] ?? 0, 'color' => 'bg-yellow-50 text-yellow-700'],
                ['label' => 'Dikonfirmasi', 'value' => $counts['confirmed'] ?? 0, 'color' => 'bg-blue-50 text-blue-700'],
                ['label' => 'Selesai', 'value' => $counts['completed'] ?? 0, 'color' => 'bg-green-50 text-green-700'],
            ];
        @endphp
        @foreach($cardData as $card)
            <div class="rounded-xl border border-zinc-200 px-3.5 sm:px-5 py-3 sm:py-4 {{ $card['color'] }}">
                <p class="text-[11px] sm:text-xs font-medium opacity-70">{{ $card['label'] }}</p>
                <p class="text-lg sm:text-2xl font-bold mt-1">{{ $card['value'] }}</p>
            </div>
        @endforeach
    </div>

    @php
        $statuses = [
            'semua' => 'Semua',
            'pending' => 'Pending',
            'confirmed' => 'Dikonfirmasi',
            'completed' => 'Selesai',
            'cancelled' => 'Dibatalkan',
        ];
        $activeFilter = request('status', 'semua');
    @endphp

    <div class="flex flex-wrap gap-1.5 sm:gap-2">
        @foreach($statuses as $key => $label)
            <a href="{{ route('admin.daftar-booking', $key !== 'semua' ? ['status' => $key] : []) }}"
               class="px-3 sm:px-4 py-1.5 rounded-full text-xs sm:text-sm font-medium border transition-colors
               {{ $activeFilter === $key
                ? 'bg-zinc-900 text-white border-zinc-900 shadow-xs'
                : 'bg-white text-zinc-600 border-zinc-200 hover:border-zinc-400' }}">
                {{ $label }}
                @if($key !== 'semua' && isset($counts[$key]))
                    <span class="ml-1 text-xs {{ $activeFilter === $key ? 'opacity-60' : 'text-zinc-400' }}">
                        ({{ $counts[$key] }})
                    </span>
                @endif
            </a>
        @endforeach
    </div>

    <div class="bg-white border border-zinc-200 rounded-2xl overflow-hidden shadow-xs">
        <div class="overflow-x-auto">
            <table class="w-full text-xs sm:text-sm text-left">
                <thead>
                    <tr class="bg-zinc-50 text-left text-[11px] font-semibold text-zinc-500 uppercase tracking-wider">
                        <th class="px-4 py-3 border-b border-zinc-200 whitespace-nowrap">#</th>
                        <th class="px-4 py-3 border-b border-zinc-200 whitespace-nowrap">Pemesan</th>
                        <th class="px-4 py-3 border-b border-zinc-200 whitespace-nowrap">Lapangan</th>
                        <th class="px-4 py-3 border-b border-zinc-200 whitespace-nowrap">Tanggal</th>
                        <th class="px-4 py-3 border-b border-zinc-200 whitespace-nowrap">Jam</th>
                        <th class="px-4 py-3 border-b border-zinc-200 whitespace-nowrap">Total Harga</th>
                        <th class="px-4 py-3 border-b border-zinc-200 whitespace-nowrap">Status</th>
                        <th class="px-4 py-3 border-b border-zinc-200 whitespace-nowrap">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @forelse($bookings as $i => $booking)
                        @php
                            $durasi = \Carbon\Carbon::parse($booking->jam_mulai)
                                ->diffInHours(\Carbon\Carbon::parse($booking->jam_selesai));
                            $totalHarga = $booking->lapangan ? ($booking->lapangan->harga_sewa * $durasi) : ($booking->total_harga ?? 0);

                            $statusColor = match ($booking->status) {
                                'pending' => 'bg-yellow-100 text-yellow-700',
                                'confirmed' => 'bg-blue-100 text-blue-700',
                                'completed' => 'bg-green-100 text-green-700',
                                'cancelled' => 'bg-red-100 text-red-700',
                                default => 'bg-zinc-100 text-zinc-600',
                            };
                            $statusLabel = match ($booking->status) {
                                'pending' => 'Pending',
                                'confirmed' => 'Dikonfirmasi',
                                'completed' => 'Selesai',
                                'cancelled' => 'Dibatalkan',
                                default => $booking->status,
                            };
                        @endphp
                        <tr class="hover:bg-zinc-50/80 transition-colors">
                            <td class="px-4 py-3.5 text-zinc-400 whitespace-nowrap">
                                {{ $bookings->firstItem() + $i }}
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <div class="font-medium text-zinc-900">{{ $booking->user->name ?? '-' }}</div>
                                <div class="text-[11px] text-zinc-400">{{ $booking->user->email ?? '' }}</div>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap font-medium text-zinc-800">
                                {{ $booking->lapangan->nama_lapangan ?? '-' }}
                            </td>
                            <td class="px-4 py-3.5 text-zinc-600 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($booking->tanggal)->format('d M Y') }}
                            </td>
                            <td class="px-4 py-3.5 text-zinc-600 whitespace-nowrap">
                                {{ \Carbon\Carbon::parse($booking->jam_mulai)->format('H:i') }} –
                                {{ \Carbon\Carbon::parse($booking->jam_selesai)->format('H:i') }}
                                <span class="text-xs text-zinc-400">({{ $durasi }} jam)</span>
                            </td>
                            <td class="px-4 py-3.5 font-bold text-zinc-900 whitespace-nowrap">
                                Rp {{ number_format($totalHarga, 0, ',', '.') }}
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <span class="px-2.5 py-1 rounded-full text-xs font-semibold {{ $statusColor }}">
                                    {{ $statusLabel }}
                                </span>
                            </td>
                            <td class="px-4 py-3.5 whitespace-nowrap">
                                <div class="flex items-center gap-1.5 flex-wrap">
                                    @if($booking->status === 'pending')
                                        <form method="POST" action="{{ route('admin.booking.update', $booking->id) }}">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="action" value="confirmed">
                                            <button type="submit"
                                                    onclick="return confirm('Konfirmasi booking ini?')"
                                                    class="px-2.5 py-1 bg-green-600 hover:bg-green-700 text-white text-xs font-semibold rounded-lg transition-colors">
                                                Konfirmasi
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.booking.update', $booking->id) }}">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="action" value="cancelled">
                                            <button type="submit"
                                                    onclick="return confirm('Tolak booking ini?')"
                                                    class="px-2.5 py-1 text-xs font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 whitespace-nowrap">
                                                Tolak
                                            </button>
                                        </form>
                                    @elseif($booking->status === 'confirmed')
                                        <form action="{{ route('admin.booking.update', $booking->id) }}" method="POST">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="action" value="completed">
                                            <button type="submit"
                                                    onclick="return confirm('Tandai booking telah selesai?')"
                                                    class="px-2.5 py-1 text-xs font-semibold text-blue-700 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 whitespace-nowrap">
                                                Selesai
                                            </button>
                                        </form>
                                        <form method="POST" action="{{ route('admin.booking.update', $booking->id) }}">
                                            @csrf @method('PATCH')
                                            <input type="hidden" name="action" value="cancelled">
                                            <button type="submit"
                                                    onclick="return confirm('Batalkan booking ini?')"
                                                    class="px-2.5 py-1 text-xs font-medium text-red-600 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 whitespace-nowrap">
                                                Batal
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-xs text-zinc-300 italic">—</span>
                                    @endif
                                </div>
                            </td>

                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center py-12 text-zinc-400">
                                Tidak ada data booking
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    @if($bookings->hasPages())
        <div class="mt-4">
            {{ $bookings->links() }}
        </div>
    @endif

</div>

@endsection
