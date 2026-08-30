<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Rekomendasi Jurusan') }} - Akses Masuk</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-[Figtree] text-slate-900 antialiased bg-slate-50 relative overflow-hidden min-h-screen flex flex-col sm:justify-center items-center pt-6 sm:pt-0">
    
    <!-- Latar Belakang Abstrak -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full -z-10 pointer-events-none">
        <div class="absolute top-0 left-0 w-96 h-96 bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70"></div>
    </div>

    <!-- Container Kartu Login/Register -->
    <div class="w-full sm:max-w-md mt-6 px-8 py-10 bg-white/80 backdrop-blur-xl shadow-2xl shadow-indigo-100 border border-white rounded-[2.5rem] overflow-hidden z-10">
        
        <!-- Area Logo -->
        <div class="flex justify-center mb-8">
            <a href="/" class="flex flex-col items-center gap-3 group">
                <div class="w-14 h-14 bg-gradient-to-br from-indigo-600 to-blue-500 rounded-2xl flex items-center justify-center text-white font-black text-3xl shadow-lg shadow-indigo-200 group-hover:scale-105 transition-transform">
                    RJ
                </div>
                <span class="font-bold text-xl tracking-tight text-slate-800">
                    Rekomendasi Jurusan
                </span>
            </a>
        </div>

        <!-- Slot untuk form login/register -->
        {{ $slot }}
    </div>
</body>
</html>