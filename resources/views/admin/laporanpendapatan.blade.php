@extends('layouts.layout')

@section('title', 'Laporan Pendapatan')

@section('content')
<div class="space-y-6 max-w-7xl mx-auto">

    {{-- Header --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-zinc-900">Laporan Pendapatan</h1>
            <p class="text-zinc-500 text-xs sm:text-sm mt-1">Rekap pendapatan dari booking lapangan</p>
        </div>
        {{-- Export PDF Button --}}
        <a href="{{ route('admin.laporan.pdf', request()->query()) }}"
           target="_blank"
           class="inline-flex items-center justify-center gap-2 bg-red-600 hover:bg-red-700 text-white font-semibold px-4 py-2.5 rounded-xl transition-colors text-xs sm:text-sm shadow-xs self-start sm:self-auto">
            <svg class="w-4 h-4 shrink-0" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3M3 17V7a2 2 0 012-2h6l2 2h6a2 2 0 012 2v8a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
            </svg>
            <span>Export PDF</span>
        </a>
    </div>

    {{-- Filter Form --}}
    <div class="bg-white rounded-2xl border border-zinc-200 shadow-xs p-4 sm:p-5">
        <form method="GET" action="{{ route('admin.laporan.pendapatan') }}" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 sm:gap-4 items-end">
            <div>
                <label class="block text-xs font-semibold text-zinc-500 mb-1.5">Dari Tanggal</label>
                <input type="date" name="dari" value="{{ request('dari') }}"
                    class="w-full border border-zinc-200 rounded-xl px-3 py-2 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900 bg-white">
            </div>
            <div>
                <label class="block text-xs font-semibold text-zinc-500 mb-1.5">Sampai Tanggal</label>
                <input type="date" name="sampai" value="{{ request('sampai') }}"
                    class="w-full border border-zinc-200 rounded-xl px-3 py-2 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900 bg-white">
            </div>
            <div>
                <label class="block text-xs font-semibold text-zinc-500 mb-1.5">Status</label>
                <select name="status" class="w-full border border-zinc-200 rounded-xl px-3 py-2 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900 bg-white text-zinc-700">
                    <option value="">Semua Status</option>
                    <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>Dikonfirmasi</option>
                    <option value="completed" {{ request('status') == 'completed' ? 'selected' : '' }}>Selesai</option>
                </select>
            </div>
            <div>
                <label class="block text-xs font-semibold text-zinc-500 mb-1.5">Lapangan</label>
                <select name="lapangan_id" class="w-full border border-zinc-200 rounded-xl px-3 py-2 text-xs sm:text-sm focus:outline-none focus:ring-2 focus:ring-zinc-900 bg-white text-zinc-700">
                    <option value="">Semua Lapangan</option>
                    @foreach($lapangans as $lp)
                        <option value="{{ $lp->id }}" {{ request('lapangan_id') == $lp->id ? 'selected' : '' }}>
                            {{ $lp->nama_lapangan }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="flex gap-2">
                <button type="submit"
                    class="flex-1 bg-zinc-900 hover:bg-zinc-700 text-white font-semibold py-2 rounded-xl text-xs sm:text-sm transition-colors text-center">
                    Filter
                </button>
                <a href="{{ route('admin.laporan.pendapatan') }}"
                    class="px-4 bg-zinc-100 hover:bg-zinc-200 text-zinc-700 font-semibold py-2 rounded-xl text-xs sm:text-sm transition-colors text-center flex items-center justify-center">
                    Reset
                </a>
            </div>
        </form>
    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <div class="bg-white rounded-2xl border border-zinc-200 shadow-xs p-4 sm:p-5 flex flex-col justify-between">
            <p class="text-zinc-500 text-xs sm:text-sm font-medium">Total Pendapatan</p>
            <h2 class="text-base sm:text-2xl font-bold text-green-600 mt-2 truncate">
                Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
            </h2>
        </div>
        <div class="bg-white rounded-2xl border border-zinc-200 shadow-xs p-4 sm:p-5 flex flex-col justify-between">
            <p class="text-zinc-500 text-xs sm:text-sm font-medium">Total Transaksi</p>
            <h2 class="text-lg sm:text-2xl font-bold text-blue-600 mt-2">{{ $totalTransaksi }}</h2>
        </div>
        <div class="bg-white rounded-2xl border border-zinc-200 shadow-xs p-4 sm:p-5 flex flex-col justify-between">
            <p class="text-zinc-500 text-xs sm:text-sm font-medium">Rata-rata / Transaksi</p>
            <h2 class="text-base sm:text-2xl font-bold text-purple-600 mt-2 truncate">
                Rp {{ $totalTransaksi > 0 ? number_format($totalPendapatan / $totalTransaksi, 0, ',', '.') : '0' }}
            </h2>
        </div>
        <div class="bg-white rounded-2xl border border-zinc-200 shadow-xs p-4 sm:p-5 flex flex-col justify-between">
            <p class="text-zinc-500 text-xs sm:text-sm font-medium">Total Jam Terisi</p>
            <h2 class="text-lg sm:text-2xl font-bold text-orange-500 mt-2">{{ $totalJam }} Jam</h2>
        </div>
    </div>

    {{-- Pendapatan per Lapangan --}}
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4 sm:gap-6">

        {{-- Chart Pendapatan per Lapangan --}}
        <div class="bg-white rounded-2xl border border-zinc-200 shadow-xs p-4 sm:p-5 overflow-hidden">
            <h3 class="font-bold text-sm sm:text-base text-zinc-800 mb-4">Pendapatan per Lapangan</h3>
            <div id="chart-lapangan"></div>
        </div>

        {{-- Pendapatan per Bulan --}}
        <div class="bg-white rounded-2xl border border-zinc-200 shadow-xs p-4 sm:p-5 overflow-hidden">
            <h3 class="font-bold text-sm sm:text-base text-zinc-800 mb-4">Tren Pendapatan Bulanan</h3>
            <div id="chart-bulanan"></div>
        </div>

    </div>

    {{-- Tabel Detail Transaksi --}}
    <div class="bg-white shadow-xs rounded-2xl overflow-hidden border border-zinc-200">
        <div class="p-4 sm:p-5 border-b border-zinc-100 flex items-center justify-between">
            <h2 class="text-sm sm:text-base font-bold text-zinc-800">Detail Transaksi</h2>
            <span class="text-xs text-zinc-400 font-medium">{{ $bookings->total() }} transaksi</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-xs sm:text-sm text-left">
                <thead class="bg-zinc-50 text-left text-zinc-500 text-[11px] uppercase font-semibold border-b border-zinc-200">
                    <tr>
                        <th class="px-4 py-3 whitespace-nowrap">No</th>
                        <th class="px-4 py-3 whitespace-nowrap">Tanggal</th>
                        <th class="px-4 py-3 whitespace-nowrap">Pemesan</th>
                        <th class="px-4 py-3 whitespace-nowrap">Lapangan</th>
                        <th class="px-4 py-3 whitespace-nowrap">Jam</th>
                        <th class="px-4 py-3 whitespace-nowrap">Durasi</th>
                        <th class="px-4 py-3 whitespace-nowrap">Status</th>
                        <th class="px-4 py-3 whitespace-nowrap text-right">Pendapatan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-zinc-100">
                    @forelse ($bookings as $i => $item)
                    @php
                        $durasi = \Carbon\Carbon::parse($item->jam_mulai)->diffInHours(\Carbon\Carbon::parse($item->jam_selesai));
                        $pendapatan = $item->lapangan ? ($item->lapangan->harga_sewa * $durasi) : 0;
                    @endphp
                    <tr class="hover:bg-zinc-50/80 transition-colors">
                        <td class="px-4 py-3.5 text-zinc-400 text-xs">{{ $bookings->firstItem() + $i }}</td>
                        <td class="px-4 py-3.5 whitespace-nowrap text-zinc-600">
                            {{ \Carbon\Carbon::parse($item->tanggal)->format('d M Y') }}
                        </td>
                        <td class="px-4 py-3.5 font-medium whitespace-nowrap text-zinc-900">
                            {{ $item->user->name ?? '-' }}
                        </td>
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            <div class="font-medium text-zinc-800">{{ $item->lapangan->nama_lapangan ?? '-' }}</div>
                            <div class="text-[11px] text-zinc-400">{{ $item->lapangan->jenisLapangan->nama_jenis ?? '-' }}</div>
                        </td>
                        <td class="px-4 py-3.5 text-zinc-600 whitespace-nowrap text-xs">
                            {{ \Carbon\Carbon::parse($item->jam_mulai)->format('H:i') }} –
                            {{ \Carbon\Carbon::parse($item->jam_selesai)->format('H:i') }}
                        </td>
                        <td class="px-4 py-3.5 text-zinc-600 whitespace-nowrap">{{ $durasi }} jam</td>
                        <td class="px-4 py-3.5 whitespace-nowrap">
                            @if($item->status == 'confirmed')
                                <span class="bg-blue-100 text-blue-700 text-xs px-2.5 py-1 rounded-full font-semibold">Dikonfirmasi</span>
                            @elseif($item->status == 'completed')
                                <span class="bg-green-100 text-green-700 text-xs px-2.5 py-1 rounded-full font-semibold">Selesai</span>
                            @elseif($item->status == 'pending')
                                <span class="bg-yellow-100 text-yellow-700 text-xs px-2.5 py-1 rounded-full font-semibold">Pending</span>
                            @else
                                <span class="bg-red-100 text-red-700 text-xs px-2.5 py-1 rounded-full font-semibold">Dibatalkan</span>
                            @endif
                        </td>
                        <td class="px-4 py-3.5 font-bold text-green-600 whitespace-nowrap text-right">
                            Rp {{ number_format($pendapatan, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-12 text-zinc-400">
                            <div class="text-2xl mb-1">📭</div>
                            <div>Tidak ada data transaksi</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
                @if($bookings->count() > 0)
                <tfoot class="bg-zinc-50 border-t border-zinc-200">
                    <tr>
                        <td colspan="7" class="px-4 py-3.5 font-bold text-right text-zinc-700 text-xs sm:text-sm">Total Pendapatan</td>
                        <td class="px-4 py-3.5 font-black text-green-600 text-sm sm:text-base text-right whitespace-nowrap">
                            Rp {{ number_format($totalPendapatan, 0, ',', '.') }}
                        </td>
                    </tr>
                </tfoot>
                @endif
            </table>
        </div>

        @if($bookings->hasPages())
            <div class="p-4 border-t border-zinc-100">
                {{ $bookings->appends(request()->query())->links() }}
            </div>
        @endif
    </div>

</div>

<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
<script>
    // Data dari PHP
    const lapanganLabels = @json($chartLapangan->pluck('nama'));
    const lapanganValues = @json($chartLapangan->pluck('total'));

    const bulanLabels = @json($chartBulanan->pluck('bulan_label'));
    const bulanValues = @json($chartBulanan->pluck('total'));

    // Chart Pendapatan per Lapangan
    const optLapangan = {
        series: [{ name: 'Pendapatan', data: lapanganValues }],
        chart: { type: 'bar', height: 250, toolbar: { show: false } },
        colors: ['#16a34a'],
        plotOptions: { bar: { borderRadius: 6, distributed: true } },
        dataLabels: { enabled: false },
        xaxis: { categories: lapanganLabels, labels: { style: { fontSize: '11px' } } },
        yaxis: {
            labels: {
                formatter: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v)
            }
        },
        tooltip: {
            y: { formatter: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v) }
        },
        legend: { show: false },
        grid: { borderColor: '#f3f4f6' }
    };
    new ApexCharts(document.getElementById('chart-lapangan'), optLapangan).render();

    // Chart Tren Bulanan
    const optBulanan = {
        series: [{ name: 'Pendapatan', data: bulanValues }],
        chart: { type: 'area', height: 250, toolbar: { show: false } },
        colors: ['#16a34a'],
        fill: {
            type: 'gradient',
            gradient: { shadeIntensity: 1, opacityFrom: 0.4, opacityTo: 0.05, stops: [0, 90, 100] }
        },
        dataLabels: { enabled: false },
        stroke: { curve: 'smooth', width: 2 },
        xaxis: { categories: bulanLabels, labels: { style: { fontSize: '11px' } } },
        yaxis: {
            labels: {
                formatter: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v)
            }
        },
        tooltip: {
            y: { formatter: v => 'Rp ' + new Intl.NumberFormat('id-ID').format(v) }
        },
        grid: { borderColor: '#f3f4f6' }
    };
    new ApexCharts(document.getElementById('chart-bulanan'), optBulanan).render();
</script>
@endsection