<nav x-data="{ open: false }" class="bg-white border-b border-gray-100 sticky top-0 z-50 shadow-sm">
    <!-- Menu Navigasi Utama (Desktop) -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <div class="flex">
                <!-- Logo Aplikasi -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2 group">
                        <div class="w-8 h-8 bg-gradient-to-br from-indigo-600 to-blue-500 rounded-lg flex items-center justify-center text-white font-bold text-xl group-hover:shadow-md transition-all">
                            O
                        </div>
                        <span class="font-bold text-xl tracking-tight text-gray-900 group-hover:text-indigo-600 transition-colors hidden sm:block">
                            SMK Negeri 1 Tatapaan
                        </span>
                    </a>
                </div>

                <!-- Tautan Navigasi -->
                <div class="hidden space-x-8 sm:-my-px sm:ms-10 sm:flex">
                    <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')" class="hover:text-indigo-600 transition-colors font-medium">
                        {{ __('Beranda') }}
                    </x-nav-link>

                    

                    <!-- Akses Pintas Admin (Disederhanakan) -->
                    @if (Auth::user()->is_admin)
                        <x-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')" class="text-amber-600 hover:text-amber-700 font-medium">
                            {{ __('Buka Panel Admin') }}
                        </x-nav-link>
                    @endif
                </div>
            </div>

            <!-- Pengaturan Akun (Dropdown) -->
            <div class="hidden sm:flex sm:items-center sm:ms-6">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-semibold rounded-full text-gray-700 bg-gray-50 hover:bg-indigo-50 hover:text-indigo-600 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all duration-200">
                            <!-- Inisial Avatar -->
                            <div class="w-6 h-6 rounded-full bg-indigo-200 text-indigo-700 flex items-center justify-center font-bold text-xs mr-2">
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
                <button @click="open = ! open" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-indigo-500 hover:bg-indigo-50 focus:outline-none focus:bg-indigo-50 focus:text-indigo-500 transition duration-150 ease-in-out">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': open, 'inline-flex': !open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Menu Navigasi Responsif (Mobile) -->
    <div :class="{ 'block': open, 'hidden': !open }" class="hidden sm:hidden bg-white border-b border-gray-200 shadow-lg absolute w-full z-50">
        <div class="pt-2 pb-3 space-y-1">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                {{ __('Beranda') }}
            </x-responsive-nav-link>
            
            

            @if (Auth::user()->is_admin)
                <x-responsive-nav-link :href="route('admin.dashboard')" :active="request()->routeIs('admin.*')" class="text-amber-600">
                    {{ __('Buka Panel Admin') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200 bg-gray-50">
            <!-- Profil Akun (Mobile) -->
            <div class="px-4 flex items-center gap-3 mb-3">
                <div class="w-10 h-10 rounded-full bg-indigo-200 text-indigo-700 flex items-center justify-center font-bold text-lg">
                    {{ substr(Auth::user()->name, 0, 1) }}
                </div>
                <div>
                    <div class="font-bold text-base text-gray-800">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('profile.edit')">
                    {{ __('Profil Saya') }}
                </x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                            onclick="event.preventDefault(); this.closest('form').submit();" 
                            class="text-red-600 font-semibold">
                        {{ __('Keluar Sistem') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>