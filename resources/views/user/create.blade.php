@extends('layouts.layout')

@section('title', 'Booking Lapangan')

@section('content')

<div class="space-y-6 max-w-3xl mx-auto">

    <div>
        <h1 class="text-xl sm:text-2xl font-bold text-zinc-900">Booking Lapangan</h1>
        <p class="text-zinc-500 text-xs sm:text-sm mt-1">Pilih tanggal dan slot waktu bermain</p>
    </div>

    @if(session('error'))
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-xs sm:text-sm">
            {{ session('error') }}
        </div>
    @endif

    @if(session('success'))
        <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl text-xs sm:text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if($errors->any())
        <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl text-xs sm:text-sm">
            <ul class="list-disc ml-4 space-y-1">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Info lapangan --}}
    <div class="bg-white rounded-2xl shadow-xs border border-zinc-200 p-4 sm:p-5 flex items-center gap-4">
        @if($lapangan->gambar_url)
            <img src="{{ $lapangan->gambar_url }}"
                 class="w-16 h-16 rounded-xl object-cover shrink-0 border border-zinc-100" alt="{{ $lapangan->nama_lapangan }}">
        @endif
        <div class="min-w-0 flex-1">
            <h2 class="font-bold text-zinc-900 text-sm sm:text-base truncate">{{ $lapangan->nama_lapangan }}</h2>
            <p class="text-xs text-zinc-500 mt-0.5">
                Jam Operasional:
                <span class="font-semibold text-zinc-700">
                    {{ \Illuminate\Support\Str::substr($lapangan->jam_buka, 0, 5) }} – {{ \Illuminate\Support\Str::substr($lapangan->jam_tutup, 0, 5) }}
                </span>
            </p>
            <p class="text-xs sm:text-sm text-green-600 font-bold mt-1">
                Rp {{ number_format($lapangan->harga_sewa, 0, ',', '.') }} / jam
            </p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-xs border border-zinc-200 p-4 sm:p-6">

        <form action="{{ route('booking.store') }}" method="POST">

            @csrf

            <input type="hidden" name="lapangan_id" value="{{ $lapangan->id }}">

            <div class="mb-5 sm:mb-6">
                <label class="block mb-2 font-semibold text-zinc-800 text-xs sm:text-sm">Tanggal Booking</label>
                <input
                    type="date"
                    id="tanggal"
                    name="tanggal"
                    value="{{ old('tanggal', date('Y-m-d')) }}"
                    min="{{ date('Y-m-d') }}"
                    required
                    class="w-full border border-zinc-200 rounded-xl p-3 focus:ring-2 focus:ring-zinc-900 focus:outline-none text-xs sm:text-sm bg-white">
            </div>

            <div class="mb-5 sm:mb-6">
                <label class="block mb-3 font-semibold text-zinc-800 text-xs sm:text-sm">Pilih Jam Booking</label>

                {{--
                    Slot dirender dari $availableSlots yang dikirim controller
                    (sudah sesuai jam_buka s/d jam_tutup lapangan).
                    Slot yang sudah lewat (hari ini) dinonaktifkan via JS setelah render.
                --}}
                <div id="slotContainer" class="grid grid-cols-3 sm:grid-cols-4 md:grid-cols-6 gap-2 sm:gap-2.5">
                    @foreach($availableSlots as $jam)
                        <button
                            type="button"
                            data-jam="{{ $jam }}"
                            class="slot-btn border border-zinc-200 rounded-xl py-2.5 sm:py-3 text-xs sm:text-sm font-semibold transition hover:bg-zinc-100">
                            {{ $jam }}
                        </button>
                    @endforeach
                </div>

                {{-- Jika tidak ada slot sama sekali (lapangan tutup / jam_buka == jam_tutup) --}}
                @if(empty($availableSlots))
                    <p class="text-xs sm:text-sm text-zinc-500 mt-3">Tidak ada slot tersedia untuk lapangan ini.</p>
                @endif

                <div class="flex flex-wrap gap-3 sm:gap-5 mt-4 sm:mt-5 text-[11px] sm:text-xs">
                    <div class="flex items-center gap-1.5">
                        <div class="w-3.5 h-3.5 bg-zinc-900 rounded-md"></div>
                        <span class="text-zinc-600 font-medium">Dipilih</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-3.5 h-3.5 bg-red-500 rounded-md"></div>
                        <span class="text-zinc-600 font-medium">Sudah Dibooking</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-3.5 h-3.5 bg-zinc-200 rounded-md"></div>
                        <span class="text-zinc-600 font-medium">Sudah Lewat</span>
                    </div>
                    <div class="flex items-center gap-1.5">
                        <div class="w-3.5 h-3.5 border border-zinc-300 rounded-md"></div>
                        <span class="text-zinc-600 font-medium">Tersedia</span>
                    </div>
                </div>
            </div>

            <div id="hiddenSlots"></div>

            <div class="bg-zinc-50 rounded-2xl p-4 sm:p-5 mb-5 sm:mb-6 border border-zinc-100">
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p class="text-zinc-400 text-xs font-medium uppercase tracking-wider">Durasi</p>
                        <h2 id="durasi" class="text-xl sm:text-2xl font-bold text-zinc-900 mt-1">0 Jam</h2>
                    </div>
                    <div>
                        <p class="text-zinc-400 text-xs font-medium uppercase tracking-wider">Total Harga</p>
                        <h2 id="harga" class="text-xl sm:text-2xl font-bold text-green-600 mt-1">Rp 0</h2>
                    </div>
                </div>
            </div>

            <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white py-3 sm:py-3.5 rounded-xl font-bold text-sm sm:text-base transition-colors shadow-xs">
                Konfirmasi Booking
            </button>

        </form>

    </div>

</div>

<script>
    const hargaPerJam  = {{ $lapangan->harga_sewa }};
    const tanggalInput = document.getElementById('tanggal');
    const hiddenSlots  = document.getElementById('hiddenSlots');
    const durasiText   = document.getElementById('durasi');
    const hargaText    = document.getElementById('harga');

    // Jam sekarang (server-side agar konsisten dengan timezone aplikasi)
    const nowHour      = {{ (int) now()->format('H') }};
    const todayStr     = '{{ now()->toDateString() }}';

    let selectedSlots = [];

    const CLASS_DEFAULT  = 'slot-btn border rounded-xl py-2.5 sm:py-3 text-sm font-semibold transition hover:bg-blue-100';
    const CLASS_SELECTED = 'slot-btn border border-blue-500 rounded-xl py-2.5 sm:py-3 text-sm font-semibold bg-blue-500 text-white';
    const CLASS_BOOKED   = 'slot-btn border border-red-500 rounded-xl py-2.5 sm:py-3 text-sm font-semibold bg-red-500 text-white cursor-not-allowed';
    const CLASS_PAST     = 'slot-btn border border-zinc-300 rounded-xl py-2.5 sm:py-3 text-sm font-semibold bg-zinc-200 text-zinc-400 cursor-not-allowed';

    function markPastSlots() {
        const isToday = tanggalInput.value === todayStr;
        const buttons = document.querySelectorAll('.slot-btn');

        buttons.forEach(btn => {
            // Jangan sentuh slot yang sudah dibooking (merah)
            if (btn.dataset.status === 'booked') return;

            const slotHour = parseInt(btn.dataset.jam);

            if (isToday && slotHour <= nowHour) {
                btn.disabled    = true;
                btn.className   = CLASS_PAST;
                btn.title       = 'Jam ini sudah lewat';
                // Kalau slot ini terlanjur dipilih, hapus dari selectedSlots
                selectedSlots   = selectedSlots.filter(s => s !== btn.dataset.jam);
            } else if (!btn.dataset.status) {
                btn.disabled    = false;
                btn.className   = CLASS_DEFAULT;
                btn.title       = '';
            }
        });

        updateBooking();
    }

    async function loadBookedSlots() {
        const tanggal = tanggalInput.value;
        if (!tanggal) return;

        try {
            const response    = await fetch(`/user/booking/slots/{{ $lapangan->id }}/${tanggal}`);
            const bookedSlots = await response.json();
            const buttons     = document.querySelectorAll('.slot-btn');

            // Reset semua slot dulu
            buttons.forEach(btn => {
                delete btn.dataset.status;
                btn.disabled  = false;
                btn.className = CLASS_DEFAULT;
                btn.title     = '';
            });

            selectedSlots = [];

            // Tandai slot yang sudah dibooking
            buttons.forEach(btn => {
                if (bookedSlots.includes(btn.dataset.jam)) {
                    btn.disabled        = true;
                    btn.className       = CLASS_BOOKED;
                    btn.dataset.status  = 'booked';
                    btn.title           = 'Sudah dibooking';
                }
            });

            // Tandai slot yang sudah lewat (harus setelah booked agar tidak override)
            markPastSlots();

        } catch (error) {
            console.error(error);
        }
    }

    document.addEventListener('click', function (e) {
        if (!e.target.classList.contains('slot-btn')) return;

        const button = e.target;
        if (button.disabled) return;

        const jam = button.dataset.jam;

        if (selectedSlots.includes(jam)) {
            selectedSlots     = selectedSlots.filter(s => s !== jam);
            button.className  = CLASS_DEFAULT;
        } else {
            const temp = [...selectedSlots, jam].sort();

            if (!isSequential(temp)) {
                alert('Slot harus berurutan! Pilih jam yang sambung-menyambung.');
                return;
            }

            selectedSlots    = temp;
            button.className = CLASS_SELECTED;
        }

        updateBooking();
    });

    function isSequential(slots) {
        for (let i = 0; i < slots.length - 1; i++) {
            if (parseInt(slots[i + 1]) !== parseInt(slots[i]) + 1) return false;
        }
        return true;
    }

    function updateBooking() {
        // Sinkronkan warna selected slot (bisa berubah setelah reset)
        document.querySelectorAll('.slot-btn').forEach(btn => {
            if (btn.dataset.status === 'booked' || btn.disabled) return;
            btn.className = selectedSlots.includes(btn.dataset.jam) ? CLASS_SELECTED : CLASS_DEFAULT;
        });

        hiddenSlots.innerHTML = selectedSlots
            .map(slot => `<input type="hidden" name="slots[]" value="${slot}">`)
            .join('');

        durasiText.innerText = selectedSlots.length + ' Jam';
        hargaText.innerText  = 'Rp ' + (selectedSlots.length * hargaPerJam).toLocaleString('id-ID');
    }

    tanggalInput.addEventListener('change', loadBookedSlots);

    // Jalankan saat pertama load
    loadBookedSlots();
</script>

@endsection