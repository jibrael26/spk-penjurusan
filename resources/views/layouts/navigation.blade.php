<nav x-data="{ open: false }" class="bg-white/95 backdrop-blur-md border-b border-indigo-50 sticky top-0 z-50 shadow-sm">
    <!-- Menu Navigasi Utama (Desktop) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-[4.5rem]">
            <div class="flex">
                <!-- Logo Aplikasi -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-3 group">
                        <img src="{{ asset('logo-smk.png') }}" alt="Logo SMK Negeri 1 Tatapaan"
                             class="w-11 h-11 object-contain group-hover:scale-105 transition-transform">
                        <span class="hidden sm:block leading-tight">
                            <span class="block font-extrabold text-base tracking-tight text-gray-900 group-hover:text-indigo-600 transition-colors">
                                SMK Negeri 1 Tatapaan
                            </span>
                            <span class="block mt-0.5 text-[10px] font-semibold uppercase tracking-[0.16em] text-indigo-400">
                                Sistem Penjurusan
                            </span>
                        </span>
                    </a>
                </div>

                <!-- Tautan Navigasi -->
                <div class="hidden items-center gap-2 sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="rounded-xl px-4 py-2 hover:bg-indigo-50 hover:text-indigo-600 transition-colors font-semibold">
                        <span class="inline-flex items-center gap-2">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l9-9 9 9M5 10v10h14V10M9 20v-6h6v6" />
                            </svg>
                        {{ __('Beranda') }}
                        </span>
                    </x-nav-link>

                    

                    <!-- Akses Pintas Admin (Disederhanakan) -->
                    @if (Auth::user()->is_admin)
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')" class="rounded-xl px-4 py-2 text-amber-600 hover:bg-amber-50 hover:text-amber-700 font-semibold">
                            <span class="inline-flex items-center gap-2">
                                <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                {{ __('Panel Admin') }}
                            </span>
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Pengaturan Akun (Dropdown) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-3 py-2 border border-indigo-100 text-sm font-semibold rounded-2xl text-gray-700 bg-white hover:bg-indigo-50 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200 shadow-sm">
                            <!-- Inisial Avatar -->
                            <div class="w-8 h-8 rounded-xl bg-gradient-to-br from-indigo-500 to-blue-500 text-white flex items-center justify-center font-bold text-xs mr-2 shadow-sm">
                                {{ substr(Auth::user()->name, 0, 1) }}
                            </div>
                            
                            <div>{{ explode(' ', Auth::user()->name)[0] }}</div>

                            <div class="ms-1">
                                <svg class="fill-current h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <!-- Informasi Akun -->
                        <div class="px-4 py-3 border-b border-gray-100">
                            <p class="text-xs text-gray-500">Masuk sebagai</p>
                            <p class="text-sm font-bold text-gray-900 truncate">{{ Auth::user()->email }}</p>
                        </div>

                        <x-dropdown-link :href="route('profile.edit')" class="hover:bg-indigo-50 hover:text-indigo-700 font-medium transition-colors">
                            {{ __('Profil Saya') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                    onclick="event.preventDefault(); this.closest('form').submit();" 
                                    class="text-red-600 hover:bg-red-50 hover:text-red-700 font-medium transition-colors">
                                {{ __('Keluar Sistem') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Tombol Hamburger (Mobile) -->
            <div class="-me-2 flex items-center sm:hidden">
                <button @click="open = ! open" aria-label="Buka menu navigasi" class="inline-flex items-center justify-center p-2.5 rounded-xl text-gray-500 hover:text-indigo-600 hover:bg-indigo-50 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Menu Navigasi Responsif (Mobile) -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden bg-white border-b border-indigo-100 shadow-lg absolute w-full z-50">
        <div class="p-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="rounded-xl">
                <span class="inline-flex items-center gap-3">
                    <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M3 12l9-9 9 9M5 10v10h14V10M9 20v-6h6v6" />
                    </svg>
                    {{ __('Beranda') }}
                </span>
            </x-responsive-nav-link>
            
            

            @if (Auth::user()->is_admin)
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')" class="rounded-xl text-amber-600">
                    <span class="inline-flex items-center gap-3">
                        <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                        {{ __('Panel Admin') }}
                    </span>
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-2 border-t border-indigo-100 bg-slate-50/80">
            <!-- Profil Akun (Mobile) -->
            <div class="px-4 flex items-center gap-3 mb-3">
                <div class="w-11 h-11 rounded-2xl bg-gradient-to-br from-indigo-500 to-blue-500 text-white flex items-center justify-center font-bold text-lg shadow-sm">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <div class="font-bold text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')" class="rounded-xl">
                    <span class="inline-flex items-center gap-3">
                        <svg class="w-5 h-5 text-indigo-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.118a7.5 7.5 0 0115 0A17.933 17.933 0 0112 21.75a17.933 17.933 0 01-7.5-1.632z" />
                        </svg>
                        {{ __('Profil Saya') }}
                    </span>
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();" 
                            class="rounded-xl text-red-600 font-semibold">
                        <span class="inline-flex items-center gap-3">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 9V5.25A2.25 2.25 0 0013.5 3h-6A2.25 2.25 0 005.25 5.25v13.5A2.25 2.25 0 007.5 21h6a2.25 2.25 0 002.25-2.25V15M12 9l3 3m0 0l-3 3m3-3H3" />
                            </svg>
                            {{ __('Keluar Sistem') }}
                        </span>
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>