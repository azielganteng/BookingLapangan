{{-- Backdrop Overlay untuk Mobile --}}
<div id="sidebar-overlay" onclick="closeSidebar()"
     class="fixed inset-0 z-40 bg-zinc-950/60 backdrop-blur-xs transition-opacity duration-300 hidden sm:hidden"></div>

{{-- Sidebar Container --}}
<aside id="main-sidebar"
  class="fixed top-0 left-0 z-50 w-72 sm:w-64 h-full -translate-x-full sm:translate-x-0 transition-transform duration-300 ease-in-out shadow-2xl sm:shadow-none bg-white border-r border-zinc-200"
  aria-label="Sidebar">
  <div class="h-full px-4 py-5 overflow-y-auto flex flex-col justify-between">

    <div>
      {{-- Brand Logo & Close Button for Mobile --}}
      <div class="flex items-center justify-between mb-6 px-2">
        <a href="{{ Auth::user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}"
           class="flex items-center gap-2.5">
          <div class="w-9 h-9 rounded-xl bg-zinc-900 flex items-center justify-center text-white font-bold text-lg shadow-sm">
            ⚽
          </div>
          <span class="font-bold text-xl tracking-tight text-zinc-900">
            Sport<span class="text-green-600">Field</span>
          </span>
        </a>

        {{-- Close button for mobile --}}
        <button onclick="closeSidebar()" type="button"
                class="sm:hidden w-8 h-8 flex items-center justify-center rounded-lg text-zinc-400 hover:text-zinc-700 hover:bg-zinc-100 transition-colors"
                aria-label="Tutup Menu">
          <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
          </svg>
        </button>
      </div>

      {{-- Admin Navigation --}}
      @if (Auth::user()->role == 'admin')
        <ul class="space-y-1 font-medium text-sm">

          {{-- Dashboard --}}
          <li>
            <a href="{{ route('admin.dashboard') }}" onclick="closeSidebar()"
              class="flex items-center px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-zinc-900 text-white font-semibold shadow-sm' : 'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900' }}">
              <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.dashboard') ? 'text-white' : 'text-zinc-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m0-8H5m7 0h7"/>
              </svg>
              <span class="ms-3">Dashboard</span>
            </a>
          </li>

          <p class="px-3.5 pt-4 pb-1.5 text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Kelola Transaksi</p>

          {{-- Daftar Booking --}}
          <li>
            <a href="{{ route('admin.daftar-booking') }}" onclick="closeSidebar()"
              class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.daftar-booking') ? 'bg-zinc-900 text-white font-semibold shadow-sm' : 'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900' }}">
              <div class="flex items-center">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.daftar-booking') ? 'text-white' : 'text-zinc-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                </svg>
                <span class="ms-3">Daftar Booking</span>
              </div>
              <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold rounded-full {{ request()->routeIs('admin.daftar-booking') ? 'bg-zinc-700 text-white' : 'bg-zinc-100 text-zinc-600' }}">
                {{ \App\Models\Booking::count() }}
              </span>
            </a>
          </li>

          <p class="px-3.5 pt-4 pb-1.5 text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Kelola Lapangan</p>

          {{-- Semua Lapangan --}}
          <li>
            <a href="{{ route('admin.semua-lapangan') }}" onclick="closeSidebar()"
              class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.semua-lapangan') || request()->routeIs('detail-lapangan') || request()->routeIs('edit-lapangan') ? 'bg-zinc-900 text-white font-semibold shadow-sm' : 'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900' }}">
              <div class="flex items-center">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.semua-lapangan') || request()->routeIs('detail-lapangan') || request()->routeIs('edit-lapangan') ? 'text-white' : 'text-zinc-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"/>
                </svg>
                <span class="ms-3">Daftar Lapangan</span>
              </div>
              <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold rounded-full {{ request()->routeIs('admin.semua-lapangan') ? 'bg-zinc-700 text-white' : 'bg-zinc-100 text-zinc-600' }}">
                {{ \App\Models\Lapangan::count() }}
              </span>
            </a>
          </li>

          {{-- Kategori Lapangan --}}
          <li>
            <a href="{{ route('jenis-lapangan') }}" onclick="closeSidebar()"
              class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('jenis-lapangan*') || request()->routeIs('tambah-jenis') ? 'bg-zinc-900 text-white font-semibold shadow-sm' : 'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900' }}">
              <div class="flex items-center">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('jenis-lapangan*') || request()->routeIs('tambah-jenis') ? 'text-white' : 'text-zinc-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l7 7a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-7-7A1.994 1.994 0 013 12V7a4 4 0 014-4z"/>
                </svg>
                <span class="ms-3">Kategori</span>
              </div>
              <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold rounded-full {{ request()->routeIs('jenis-lapangan*') ? 'bg-zinc-700 text-white' : 'bg-zinc-100 text-zinc-600' }}">
                {{ \App\Models\JenisLapangan::count() }}
              </span>
            </a>
          </li>

          <p class="px-3.5 pt-4 pb-1.5 text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Laporan & Analisis</p>

          {{-- Laporan Pendapatan --}}
          <li>
            <a href="{{ route('admin.laporan.pendapatan') }}" onclick="closeSidebar()"
              class="flex items-center px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('admin.laporan.*') ? 'bg-zinc-900 text-white font-semibold shadow-sm' : 'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900' }}">
              <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('admin.laporan.*') ? 'text-white' : 'text-zinc-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
              </svg>
              <span class="ms-3">Laporan Pendapatan</span>
            </a>
          </li>

        </ul>
      @endif

      {{-- User Navigation --}}
      @if (Auth::user()->role == 'user')
        <ul class="space-y-1 font-medium text-sm">

          {{-- Dashboard --}}
          <li>
            <a href="{{ route('user.dashboard') }}" onclick="closeSidebar()"
              class="flex items-center px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('user.dashboard') ? 'bg-zinc-900 text-white font-semibold shadow-sm' : 'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900' }}">
              <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('user.dashboard') ? 'text-white' : 'text-zinc-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m0-8H5m7 0h7"/>
              </svg>
              <span class="ms-3">Dashboard</span>
            </a>
          </li>

          {{-- Cari Lapangan --}}
          <li>
            <a href="{{ route('user.cari-lapangan') }}" onclick="closeSidebar()"
              class="flex items-center px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('user.cari-lapangan') || request()->routeIs('user.detail-lapangan') || request()->routeIs('booking.create') ? 'bg-zinc-900 text-white font-semibold shadow-sm' : 'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900' }}">
              <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('user.cari-lapangan') || request()->routeIs('user.detail-lapangan') ? 'text-white' : 'text-zinc-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
              </svg>
              <span class="ms-3">Cari Lapangan</span>
            </a>
          </li>

          {{-- Booking Saya --}}
          <li>
            <a href="{{ route('booking.index') }}" onclick="closeSidebar()"
              class="flex items-center justify-between px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('booking.index') ? 'bg-zinc-900 text-white font-semibold shadow-sm' : 'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900' }}">
              <div class="flex items-center">
                <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('booking.index') ? 'text-white' : 'text-zinc-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                </svg>
                <span class="ms-3">Booking Saya</span>
              </div>
              <span class="inline-flex items-center justify-center px-2 py-0.5 text-xs font-bold rounded-full {{ request()->routeIs('booking.index') ? 'bg-zinc-700 text-white' : 'bg-zinc-100 text-zinc-600' }}">
                {{ \App\Models\Booking::where('user_id', Auth::id())->count() }}
              </span>
            </a>
          </li>

          <p class="px-3.5 pt-4 pb-1.5 text-[11px] font-bold text-zinc-400 uppercase tracking-wider">Akun</p>

          {{-- Profil --}}
          <li>
            <a href="{{ route('profile.edit') }}" onclick="closeSidebar()"
              class="flex items-center px-3.5 py-2.5 rounded-xl transition-all {{ request()->routeIs('profile.*') ? 'bg-zinc-900 text-white font-semibold shadow-sm' : 'text-zinc-600 hover:bg-zinc-100 hover:text-zinc-900' }}">
              <svg class="w-5 h-5 shrink-0 {{ request()->routeIs('profile.*') ? 'text-white' : 'text-zinc-400' }}" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
              <span class="ms-3">Profil Saya</span>
            </a>
          </li>

        </ul>
      @endif
    </div>

    {{-- User Info & Logout --}}
    <div class="pt-4 border-t border-zinc-100">
      <div class="px-3 py-2.5 mb-2 flex items-center gap-3 bg-zinc-50 rounded-xl">
        <div class="w-8 h-8 rounded-full bg-zinc-900 text-white flex items-center justify-center font-bold text-xs shrink-0 shadow-xs">
          {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
        </div>
        <div class="min-w-0 flex-1">
          <p class="text-xs font-bold text-zinc-900 truncate">{{ Auth::user()->name }}</p>
          <p class="text-[11px] text-zinc-500 truncate">{{ Auth::user()->role === 'admin' ? 'Administrator' : 'User' }}</p>
        </div>
      </div>

      <a href="{{ route('logout') }}"
        class="flex items-center px-3.5 py-2.5 text-red-600 rounded-xl hover:bg-red-50 transition-colors text-sm font-medium"
        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
        <svg class="shrink-0 w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
            d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
        </svg>
        <span class="ms-3">Keluar</span>
      </a>
      <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
        @csrf
      </form>
    </div>

  </div>
</aside>

<script>
  function toggleSidebar() {
    const sidebar = document.getElementById('main-sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    if (sidebar && overlay) {
      const isHidden = sidebar.classList.contains('-translate-x-full');
      if (isHidden) {
        sidebar.classList.remove('-translate-x-full');
        overlay.classList.remove('hidden');
        document.body.classList.add('overflow-hidden', 'sm:overflow-auto');
      } else {
        sidebar.classList.add('-translate-x-full');
        overlay.classList.add('hidden');
        document.body.classList.remove('overflow-hidden', 'sm:overflow-auto');
      }
    }
  }
  function closeSidebar() {
    const sidebar = document.getElementById('main-sidebar');
    const overlay = document.getElementById('sidebar-overlay');
    if (sidebar && overlay) {
      sidebar.classList.add('-translate-x-full');
      overlay.classList.add('hidden');
      document.body.classList.remove('overflow-hidden', 'sm:overflow-auto');
    }
  }
</script>