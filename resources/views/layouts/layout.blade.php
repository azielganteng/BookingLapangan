<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
    <title>@yield('title', 'SportField')</title>
</head>
<body class="bg-zinc-50 text-zinc-900 antialiased min-h-screen">

    {{-- Top Mobile Header --}}
    <header class="sm:hidden fixed top-0 left-0 right-0 h-16 bg-white/95 backdrop-blur-md border-b border-zinc-200 z-30 px-4 flex items-center justify-between shadow-xs">
        <div class="flex items-center gap-3">
            <button onclick="toggleSidebar()" type="button"
                    class="flex items-center justify-center w-10 h-10 rounded-xl bg-zinc-100 hover:bg-zinc-200 text-zinc-700 transition-colors focus:outline-none focus:ring-2 focus:ring-zinc-900"
                    aria-label="Buka Menu">
                <svg class="w-5 h-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                </svg>
            </button>
            <a href="{{ Auth::user() && Auth::user()->role === 'admin' ? route('admin.dashboard') : route('user.dashboard') }}"
               class="flex items-center gap-2">
                <div class="w-8 h-8 rounded-lg bg-zinc-900 flex items-center justify-center text-white font-bold text-sm">
                    ⚽
                </div>
                <span class="font-bold text-lg tracking-tight text-zinc-900">
                    Sport<span class="text-green-600">Field</span>
                </span>
            </a>
        </div>

        @auth
        <div class="flex items-center gap-2">
            <div class="w-8 h-8 rounded-full bg-green-100 border border-green-200 flex items-center justify-center font-bold text-xs text-green-800">
                {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
            </div>
        </div>
        @endauth
    </header>

    {{-- Sidebar Navigation --}}
    <x-sidenavbar />

    {{-- Main View Container --}}
    <main class="min-h-screen sm:ml-64 pt-20 sm:pt-6 px-3.5 sm:px-8 pb-12 transition-all">
        @yield('content')
    </main>

    @stack('scripts')

</body>
</html>

