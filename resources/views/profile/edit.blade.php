<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 rounded-2xl bg-indigo-50 text-indigo-600 flex items-center justify-center">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M15.75 6a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0zM4.5 20.118a7.5 7.5 0 0115 0A17.933 17.933 0 0112 21.75a17.933 17.933 0 01-7.5-1.632z" />
                </svg>
            </div>
            <div>
                <h2 class="font-bold text-xl text-gray-900 leading-tight">
                    {{ __('Profil Saya') }}
                </h2>
                <p class="text-xs text-gray-500 mt-0.5">Kelola informasi akun dan keamanan Anda</p>
            </div>
        </div>
    </x-slot>

    <div class="py-10 bg-slate-50/70 min-h-screen">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 space-y-6">
            <div class="relative overflow-hidden rounded-[2rem] bg-gradient-to-br from-indigo-600 via-blue-600 to-blue-500 p-6 sm:p-8 shadow-xl">
                <div class="absolute -right-12 -top-16 w-48 h-48 rounded-full bg-white/10 blur-2xl"></div>
                <div class="relative flex flex-col sm:flex-row sm:items-center gap-4">
                    <div class="w-14 h-14 rounded-2xl bg-white/20 border border-white/20 text-white flex items-center justify-center text-2xl font-extrabold">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <div class="text-white">
                        <p class="text-sm text-blue-100">Pengaturan akun siswa</p>
                        <h1 class="mt-1 text-2xl font-extrabold tracking-tight">{{ Auth::user()->name }}</h1>
                        <p class="mt-1 text-sm text-blue-100">{{ Auth::user()->email }}</p>
                    </div>
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-white shadow-sm rounded-[2rem] border border-gray-100">
                <div class="max-w-2xl">
                    @include('profile.partials.update-profile-information-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-white shadow-sm rounded-[2rem] border border-gray-100">
                <div class="max-w-2xl">
                    @include('profile.partials.update-password-form')
                </div>
            </div>

            <div class="p-6 sm:p-8 bg-white shadow-sm rounded-[2rem] border border-red-100">
                <div class="max-w-2xl">
                    @include('profile.partials.delete-user-form')
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
