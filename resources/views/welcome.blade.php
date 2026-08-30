<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SPK Penjurusan - Temukan Arah Masa Depanmu</title>
    
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet" />
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-[Figtree] antialiased bg-slate-50 text-slate-900 selection:bg-indigo-500 selection:text-white">

    <!-- Navbar -->
    <nav class="fixed w-full z-50 bg-white/80 backdrop-blur-lg border-b border-slate-200/50 transition-all">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-20">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-indigo-600 to-blue-500 rounded-xl flex items-center justify-center text-white font-black text-xl shadow-lg shadow-indigo-200">
                        RJ
                    </div>
                    <span class="font-bold text-2xl tracking-tight bg-clip-text text-transparent bg-gradient-to-r from-slate-900 to-slate-600">Rekomendasi Jurusan</span>
                </div>

                <div class="hidden md:flex items-center space-x-8">
                    <a href="#fitur" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors">Fitur Sistem</a>
                    <a href="#cara-kerja" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors">Cara Kerja</a>
                    
                    <div class="w-px h-6 bg-slate-200"></div>

                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="text-sm font-bold text-indigo-600 hover:text-indigo-700 transition-colors">
                                Buka Dashboard &rarr;
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-indigo-600 transition-colors">
                                Masuk
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-5 py-2.5 text-sm font-bold text-white bg-indigo-600 rounded-full hover:bg-indigo-700 hover:shadow-lg hover:shadow-indigo-200 hover:-translate-y-0.5 transition-all">
                                    Daftar Akun
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <main class="relative pt-32 pb-20 lg:pt-48 lg:pb-32 overflow-hidden">
        <!-- Abstract Background Shapes -->
        <div class="absolute top-0 left-1/2 -translate-x-1/2 w-full h-full -z-10 pointer-events-none">
            <div class="absolute top-20 left-0 w-96 h-96 bg-blue-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob"></div>
            <div class="absolute top-20 right-0 w-96 h-96 bg-indigo-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-2000"></div>
            <div class="absolute -bottom-32 left-1/2 -translate-x-1/2 w-96 h-96 bg-purple-100 rounded-full mix-blend-multiply filter blur-3xl opacity-70 animate-blob animation-delay-4000"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center relative z-10">
            <div class="inline-flex items-center gap-2 px-4 py-2 rounded-full bg-indigo-50 border border-indigo-100 text-indigo-600 text-sm font-semibold mb-8">
                <span class="flex h-2 w-2 rounded-full bg-indigo-600"></span>
                Sistem Pendukung Keputusan Berbasis Analitis
            </div>
            
            <h1 class="text-5xl md:text-7xl font-extrabold tracking-tight text-slate-900 mb-8 leading-tight">
                Kenali Potensimu, <br class="hidden md:block">
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-indigo-600 to-blue-500">
                    Pilih Jurusan yang Tepat.
                </span>
            </h1>
            
            <p class="mt-6 text-lg md:text-xl text-slate-600 max-w-2xl mx-auto mb-10 leading-relaxed">
                Tinggalkan keraguan. Platform kami menggunakan algoritma terstruktur untuk menganalisis minat, bakat, dan kemampuan logikamu demi merekomendasikan jalur akademik terbaik.
            </p>
            
            <div class="flex flex-col sm:flex-row items-center justify-center gap-4">
                @auth
                    <a href="{{ url('/dashboard') }}" class="w-full sm:w-auto px-8 py-4 text-base font-bold text-white bg-slate-900 rounded-full hover:bg-slate-800 hover:shadow-xl hover:-translate-y-1 transition-all">
                        Kembali ke Dashboard
                    </a>
                @else
                    <a href="{{ route('register') }}" class="w-full sm:w-auto px-8 py-4 text-base font-bold text-white bg-indigo-600 rounded-full hover:bg-indigo-700 hover:shadow-xl hover:shadow-indigo-200 hover:-translate-y-1 transition-all">
                        Mulai Tes Gratis
                    </a>
                    <a href="#cara-kerja" class="w-full sm:w-auto px-8 py-4 text-base font-bold text-slate-700 bg-white border border-slate-200 rounded-full hover:bg-slate-50 hover:shadow-md transition-all">
                        Pelajari Cara Kerja
                    </a>
                @endauth
            </div>
        </div>
    </main>

    <!-- Fitur Section -->
    <section id="fitur" class="py-24 bg-white relative">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center max-w-3xl mx-auto mb-20">
                <h2 class="text-3xl md:text-4xl font-bold text-slate-900 mb-4">Mengapa Menggunakan SPK Ini?</h2>
                <p class="text-lg text-slate-600">Pendekatan saintifik untuk membantu sekolah dan siswa meminimalisir kesalahan dalam pemilihan program studi.</p>
            </div>

            <div class="grid md:grid-cols-3 gap-8">
                <!-- Fitur 1 -->
                <div class="p-8 rounded-[2rem] bg-slate-50 border border-slate-100 hover:shadow-xl transition-all duration-300 group">
                    <div class="w-14 h-14 bg-white rounded-2xl shadow-sm flex items-center justify-center text-indigo-600 mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Kuesioner Komprehensif</h3>
                    <p class="text-slate-600 leading-relaxed">Pertanyaan dirancang secara khusus untuk menggali indikator psikometrik dan kemampuan dasar akademik secara akurat.</p>
                </div>

                <!-- Fitur 2 -->
                <div class="p-8 rounded-[2rem] bg-slate-50 border border-slate-100 hover:shadow-xl transition-all duration-300 group">
                    <div class="w-14 h-14 bg-white rounded-2xl shadow-sm flex items-center justify-center text-blue-600 mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Mesin Inferensi Cerdas</h3>
                    <p class="text-slate-600 leading-relaxed">Data diproses menggunakan metode perhitungan matematika untuk menghasilkan pembobotan jurusan yang presisi dan objektif.</p>
                </div>

                <!-- Fitur 3 -->
                <div class="p-8 rounded-[2rem] bg-slate-50 border border-slate-100 hover:shadow-xl transition-all duration-300 group">
                    <div class="w-14 h-14 bg-white rounded-2xl shadow-sm flex items-center justify-center text-purple-600 mb-6 group-hover:scale-110 transition-transform">
                        <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-3">Laporan Hasil Instan</h3>
                    <p class="text-slate-600 leading-relaxed">Siswa dapat langsung mengunduh atau meninjau rekomendasi jurusan beserta rincian analisis sesaat setelah tes selesai.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to Action -->
    <section class="py-20">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-slate-900 rounded-[3rem] p-10 md:p-16 text-center relative overflow-hidden shadow-2xl">
                <!-- Background pattern -->
                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 24px 24px;"></div>
                
                <h2 class="text-3xl md:text-5xl font-bold text-white mb-6 relative z-10">Siap Menentukan Pilihan?</h2>
                <p class="text-slate-400 text-lg mb-10 max-w-2xl mx-auto relative z-10">Daftarkan akunmu sekarang, lengkapi profil, dan kerjakan kuesionernya hanya dalam waktu 15 menit.</p>
                
                <a href="{{ route('register') }}" class="inline-flex items-center justify-center px-8 py-4 text-base font-bold text-slate-900 bg-white rounded-full hover:bg-slate-100 hover:scale-105 transition-all relative z-10">
                    Mulai Asesmen Sekarang
                </a>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-white py-12 border-t border-slate-200 mt-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col md:flex-row justify-between items-center gap-4">
            <div class="flex items-center gap-2">
                <div class="w-8 h-8 bg-slate-900 rounded-lg flex items-center justify-center text-white font-bold">RJ</div>
                <span class="font-bold text-slate-900">Rekomendasi Jurusan</span>
            </div>
            <p class="text-slate-500 text-sm font-medium">
                &copy; {{ date('Y') }} Dibangun dengan Laravel & Tailwind CSS.
            </p>
        </div>
    </footer>

</body>
</html>