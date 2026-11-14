<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Rekomendasi Program Keahlian') }} - Akses Masuk</title>
    <link rel="icon" type="image/x-icon" href="{{ asset('favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="relative min-h-screen overflow-x-hidden bg-slate-50 font-[Figtree] text-slate-900 antialiased">
    <div class="pointer-events-none absolute inset-0 -z-10 overflow-hidden">
        <div class="absolute -left-24 -top-24 h-80 w-80 rounded-full bg-indigo-100/70 blur-3xl"></div>
        <div class="absolute -bottom-24 -right-24 h-80 w-80 rounded-full bg-cyan-100/70 blur-3xl"></div>
    </div>

    <main class="relative z-10 flex min-h-screen items-center justify-center px-4 py-8 sm:px-6 lg:px-8">
        <div class="w-full max-w-5xl overflow-hidden rounded-[2rem] border border-white/80 bg-white/90 shadow-2xl shadow-indigo-100 backdrop-blur-xl sm:rounded-[2.5rem]">
            <div class="grid lg:grid-cols-[0.9fr_1.1fr]">
                <aside class="hidden bg-gradient-to-br from-indigo-700 via-blue-700 to-cyan-600 p-10 text-white lg:flex lg:flex-col lg:justify-between">
                    <div>
                        <img src="{{ asset('logo-smk.png') }}" alt="Logo SMK Negeri 1 Tatapaan" class="h-20 w-20 object-contain">
                        <p class="mt-8 text-xs font-bold uppercase tracking-[0.2em] text-cyan-100">SMK Negeri 1 Tatapaan</p>
                        <h1 class="mt-3 text-3xl font-black leading-tight">Kenali potensi,<br>pilih Program Keahlian.</h1>
                        <p class="mt-4 text-sm leading-6 text-blue-100">Gunakan hasil asesmen minat dan bakat sebagai bahan pertimbangan untuk menentukan langkah belajar yang sesuai.</p>
                    </div>
                    <div class="rounded-2xl bg-white/10 p-4 text-sm leading-6 text-blue-50 ring-1 ring-white/15">
                        Profil Anda dianalisis melalui minat RIASEC dan kemampuan CHC.
                    </div>
                </aside>
                <section class="px-5 py-8 sm:px-10 sm:py-10">
                    <div class="mb-8 flex items-center gap-3">
                        <a href="/" class="flex items-center gap-3 group">
                            <img src="{{ asset('logo-smk.png') }}" alt="Logo SMK Negeri 1 Tatapaan" class="h-14 w-14 object-contain transition-transform group-hover:scale-105">
                            <span>
                                <span class="block text-xs font-bold uppercase tracking-wider text-indigo-500">Sistem Rekomendasi</span>
                                <span class="block text-lg font-black tracking-tight text-slate-900 sm:text-xl">Program Keahlian</span>
                            </span>
                        </a>
                    </div>
                    {{ $slot }}
                </section>
            </div>
        </div>
    </main>
</body>
</html>
