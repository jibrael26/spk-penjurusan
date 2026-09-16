<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Admin Panel - SPK Penjurusan</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    
    <!-- Alpine.js (Bawaan Laravel Breeze untuk interaksi UI) -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="font-[Figtree] antialiased bg-slate-100 text-gray-900" x-data="{ sidebarOpen: false }" @keydown.escape.window="sidebarOpen = false">

    <div class="flex h-screen overflow-hidden">
        
        <!-- Overlay untuk Mobile -->
        <div x-cloak x-show="sidebarOpen" class="fixed inset-0 z-20 bg-slate-950/60 backdrop-blur-sm lg:hidden" @click="sidebarOpen = false" x-transition.opacity></div>

        <!-- Sidebar Navigation -->
        <aside :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'" class="fixed inset-y-0 left-0 z-30 w-72 bg-slate-950 text-white transition-transform duration-300 lg:static lg:translate-x-0 flex flex-col shadow-xl">
            <!-- Logo Area -->
            <div class="flex items-center justify-between h-20 px-5 border-b border-white/10">
                <a href="{{ route('admin.dashboard') }}" class="text-lg font-bold tracking-wide flex items-center gap-3">
                    <span class="w-10 h-10 bg-cyan-400 text-slate-950 rounded-xl flex items-center justify-center font-black shadow-lg shadow-cyan-400/20">S</span>
                    <span>SPK <span class="text-cyan-300">Admin</span></span>
                </a>
                <button @click="sidebarOpen = false" class="lg:hidden p-2 text-slate-400 hover:text-white rounded-lg hover:bg-white/10" aria-label="Tutup menu">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>

            <!-- Menu Links -->
            <nav class="flex-1 px-4 py-7 space-y-2 overflow-y-auto">
                <p class="px-4 mb-3 text-[11px] font-bold uppercase tracking-[0.18em] text-slate-500">Navigasi utama</p>
                <!-- Dashboard -->
                <a href="{{ route('admin.dashboard') }}" @click="sidebarOpen = false" class="{{ request()->routeIs('admin.dashboard') ? 'bg-cyan-400 text-slate-950 shadow-lg shadow-cyan-400/10' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path></svg>
                    <span class="font-medium">Dashboard</span>
                </a>

                <!-- Data Siswa -->
                <a href="{{ route('admin.siswa') }}" @click="sidebarOpen = false" class="{{ request()->routeIs('admin.siswa') ? 'bg-cyan-400 text-slate-950 shadow-lg shadow-cyan-400/10' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                    <span class="font-medium">Data Siswa</span>
                </a>

                <!-- Manajemen Pertanyaan -->
                <a href="{{ route('admin.pertanyaan') }}" @click="sidebarOpen = false" class="{{ request()->routeIs('admin.pertanyaan') ? 'bg-cyan-400 text-slate-950 shadow-lg shadow-cyan-400/10' : 'text-slate-300 hover:bg-white/10 hover:text-white' }} flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    <span class="font-medium">Bank Soal & AI</span>
                </a>
            </nav>

            <!-- Profil & Logout (Bawah Sidebar) -->
            <div class="p-4 border-t border-white/10 bg-white/[0.03]">
                <div class="flex items-center gap-3 mb-4 px-2">
                    <div class="w-10 h-10 rounded-xl bg-cyan-400 text-slate-950 flex items-center justify-center font-bold uppercase">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <div class="overflow-hidden">
                        <p class="text-sm font-medium text-white truncate">{{ Auth::user()->name }}</p>
                        <p class="text-xs text-slate-400 truncate">{{ Auth::user()->email }}</p>
                    </div>
                </div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="w-full flex items-center justify-center gap-2 px-4 py-2.5 bg-white/5 text-slate-300 hover:bg-rose-500 hover:text-white rounded-xl transition-colors text-sm font-semibold">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                        Keluar
                    </button>
                </form>
            </div>
        </aside>

        <!-- Main Content Area -->
        <div class="flex-1 flex flex-col overflow-hidden">
            <!-- Topbar -->
            <header class="min-h-20 bg-white/90 backdrop-blur border-b border-slate-200 flex items-center justify-between px-4 sm:px-6 lg:px-8 z-10">
                <!-- Hamburger untuk Mobile -->
                <button @click="sidebarOpen = true" class="lg:hidden p-2 -ml-2 text-slate-500 hover:text-slate-900 hover:bg-slate-100 rounded-xl focus:outline-none" aria-label="Buka menu">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"></path></svg>
                </button>
                
                <div class="flex-1 px-3 sm:px-6">
                    <p class="text-xs font-semibold uppercase tracking-[0.16em] text-cyan-600">Panel administrasi</p>
                    <h1 class="text-base sm:text-lg font-bold text-slate-900">{{ request()->routeIs('admin.dashboard') ? 'Ringkasan sistem' : (request()->routeIs('admin.siswa') ? 'Data siswa' : 'Bank soal & AI') }}</h1>
                </div>

                <div class="hidden sm:flex items-center gap-3 text-right">
                    <div>
                        <p class="text-xs font-medium text-slate-400">Hari ini</p>
                        <p class="text-sm font-semibold text-slate-700">{{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}</p>
                    </div>
                    <div class="w-10 h-10 rounded-xl bg-cyan-50 text-cyan-700 flex items-center justify-center">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    </div>
                </div>
            </header>

            <!-- Page Content (Slot yang akan diisi oleh halaman lain) -->
            <main class="flex-1 overflow-y-auto bg-slate-100 p-4 sm:p-6 lg:p-8">
                <!-- Flash Message Error/Success -->
                @if (session('error') || session('success'))
                    <div x-data="{ visible: true }" x-show="visible" x-transition class="mb-5 flex items-start gap-3 p-4 rounded-2xl border shadow-sm {{ session('error') ? 'bg-rose-50 border-rose-200 text-rose-700' : 'bg-emerald-50 border-emerald-200 text-emerald-700' }}" role="alert">
                        <div class="mt-0.5 shrink-0">
                            @if (session('error'))
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v3.75m0 3.75h.008M10.29 3.86l-7.2 12.48A1.5 1.5 0 004.39 18.6h15.22a1.5 1.5 0 001.3-2.26l-7.2-12.48a1.5 1.5 0 00-2.6 0z"></path></svg>
                            @else
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            @endif
                        </div>
                        <p class="flex-1 font-semibold text-sm">{{ session('error') ?? session('success') }}</p>
                        <button @click="visible = false" class="opacity-60 hover:opacity-100" aria-label="Tutup pesan">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>
                @endif
                
                {{ $slot }}
            </main>
        </div>
    </div>
</body>
</html>