@extends('layouts.layout')

@section('title', 'Dashboard Admin')

@section('content')

<div class="p-4 sm:p-8">

    <div class="mb-6 sm:mb-8">
        <h1 class="text-2xl sm:text-3xl font-bold text-zinc-900">Dashboard Admin</h1>
        <p class="text-zinc-500 mt-1 text-sm sm:text-base">Ringkasan statistik booking dan performa pendapatan lapangan</p>
    </div>

    {{-- Stat Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-4 mb-6 sm:mb-8">

        <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-5 border border-zinc-200">
            <p class="text-zinc-400 text-xs sm:text-sm font-semibold uppercase tracking-wider">Total Lapangan</p>
            <h2 class="text-2xl sm:text-3xl font-black text-zinc-900 mt-2">{{ $totalLapangan ?? 0 }}</h2>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-5 border border-zinc-200">
            <p class="text-zinc-400 text-xs sm:text-sm font-semibold uppercase tracking-wider">Total Booking</p>
            <h2 class="text-2xl sm:text-3xl font-black text-blue-600 mt-2">{{ $totalBookingSemua ?? 0 }}</h2>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-5 border border-zinc-200">
            <p class="text-zinc-400 text-xs sm:text-sm font-semibold uppercase tracking-wider">Booking Pending</p>
            <h2 class="text-2xl sm:text-3xl font-black text-yellow-600 mt-2">{{ $bookingPending ?? 0 }}</h2>
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-4 sm:p-5 border border-zinc-200">
            <p class="text-zinc-400 text-xs sm:text-sm font-semibold uppercase tracking-wider">Total Pendapatan</p>
            <h2 class="text-xl sm:text-2xl font-black text-green-600 mt-2">
                Rp {{ number_format($totalPendapatanSemua ?? 0, 0, ',', '.') }}
            </h2>
        </div>

    </div>

    {{-- Charts Grid --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6 sm:mb-8">

        <div class="bg-white rounded-2xl shadow-sm p-5 border border-zinc-200">
            <h2 class="text-base font-bold text-zinc-800 mb-4">Grafik Total Booking</h2>
            {!! $bookingChart->container() !!}
        </div>

        <div class="bg-white rounded-2xl shadow-sm p-5 border border-zinc-200">
            <h2 class="text-base font-bold text-zinc-800 mb-4">Grafik Pendapatan</h2>
            {!! $pendapatanChart->container() !!}
        </div>

    </div>

    {{-- Recent Bookings Table --}}
    <div class="bg-white shadow-sm rounded-2xl overflow-hidden border border-zinc-200">

        <div class="p-4 sm:p-5 border-b border-zinc-100 flex items-center justify-between">
            <h2 class="text-base font-bold text-zinc-800">Booking Masuk Terbaru</h2>
            <a href="{{ route('admin.daftar-booking') }}" class="text-xs font-semibold text-blue-600 hover:underline">
                Lihat Semua &rarr;
            </a>
        </div>

        <div class="overflow-x-auto">
            <table class="min-w-full text-sm text-left">
                <thead class="bg-zinc-50 text-zinc-500 uppercase text-xs font-semibold border-b border-zinc-200">
                    <tr>
                        <th class="px-5 py-3.5 whitespace-nowrap">Pemesan</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Lapangan</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Kategori</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Tanggal</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Jam</th>
                        <th class="px-4 py-3.5 whitespace-nowrap">Status</th>
                        <th class="px-5 py-3.5 whitespace-nowrap text-right">Total</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @forelse ($bookings as $item)
                    <tr class="hover:bg-zinc-50 transition-colors">

                        <td class="px-5 py-4 font-semibold text-zinc-900 whitespace-nowrap">
                            {{ $item->user->name ?? 'Pengguna' }}
                        </td>

                        <td class="px-4 py-4 whitespace-nowrap text-zinc-800 font-medium">
                            {{ $item->lapangan->nama_lapangan ?? 'Lapangan' }}
                        </td>

                        <td class="px-4 py-4 whitespace-nowrap">
                            <span class="bg-zinc-100 text-zinc-600 text-xs px-2.5 py-1 rounded-lg font-medium">
                                {{ $item->lapangan->jenisLapangan->nama_jenis ?? '-' }}
                            </span>
                        </td>

                        <td class="px-4 py-4 text-zinc-600 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                        </td>

                        <td class="px-4 py-4 text-zinc-600 whitespace-nowrap">
                            {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} – {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}
                        </td>

                        <td class="px-4 py-4 whitespace-nowrap">
                            @if($item->status == 'pending')
                                <span class="bg-yellow-100 text-yellow-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    Pending
                                </span>
                            @elseif($item->status == 'confirmed')
                                <span class="bg-blue-100 text-blue-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    Dikonfirmasi
                                </span>
                            @elseif($item->status == 'completed')
                                <span class="bg-green-100 text-green-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    Selesai
                                </span>
                            @else
                                <span class="bg-red-100 text-red-700 text-xs font-semibold px-2.5 py-1 rounded-full">
                                    Dibatalkan
                                </span>
                            @endif
                        </td>

                        <td class="px-5 py-4 font-bold text-green-600 whitespace-nowrap text-right">
                            @php
                                $durasi = \Carbon\Carbon::parse($item->jam_mulai)->diffInHours(\Carbon\Carbon::parse($item->jam_selesai));
                                $harga = $item->lapangan ? ($item->lapangan->harga_sewa * $durasi) : ($item->total_harga ?? 0);
                            @endphp
                            Rp {{ number_format($harga, 0, ',', '.') }}
                        </td>

                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-10 text-zinc-400">
                            Belum ada transaksi booking masuk
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($bookings->hasPages())
            <div class="p-4 border-t border-zinc-100">
                {{ $bookings->links() }}
            </div>
        @endif

    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
{{ $bookingChart->script() }}
{{ $pendapatanChart->script() }}

@endsection
