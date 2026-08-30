<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Beranda Siswa') }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Hero Banner Section -->
            <div class="relative bg-gradient-to-br from-indigo-600 via-blue-600 to-blue-500 rounded-[2rem] shadow-xl overflow-hidden mb-8 group">
                <!-- Dekorasi Latar Belakang (Blur/Glow) -->
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white opacity-10 blur-2xl transition-transform duration-700 group-hover:scale-150"></div>
                <div class="absolute bottom-0 right-1/4 -mb-16 w-40 h-40 rounded-full bg-white opacity-10 blur-xl"></div>
                <div class="absolute top-1/2 left-0 -ml-16 w-32 h-32 rounded-full bg-indigo-300 opacity-20 blur-xl"></div>

                <div class="relative px-6 py-12 sm:px-12 sm:py-16 flex flex-col md:flex-row items-center justify-between gap-8">
                    <!-- Teks Sapaan -->
                    <div class="text-white text-center md:text-left max-w-2xl">
                        <span class="inline-block py-1 px-3 rounded-full bg-white/20 backdrop-blur-sm text-sm font-semibold mb-4 border border-white/20">
                            Sistem Pendukung Keputusan
                        </span>
                        <h3 class="text-3xl sm:text-4xl font-extrabold mb-3 tracking-tight">
                            Halo, {{ explode(' ', Auth::user()->name)[0] }}! 🚀
                        </h3>
                        <p class="text-blue-100 text-base sm:text-lg leading-relaxed">
                            Masa depanmu dimulai dari pilihan yang tepat. Mari temukan potensi tersembunyimu dan pilih jurusan yang paling sesuai dengan minat serta bakatmu.
                        </p>
                    </div>
                    
                    <!-- Tombol Aksi Utama -->
                    <div class="shrink-0 z-10">
                         <a href="{{ route('assessment.index') }}" class="inline-flex items-center justify-center px-8 py-4 bg-white text-indigo-600 rounded-full font-extrabold text-base sm:text-lg hover:bg-gray-50 hover:scale-105 hover:shadow-2xl transition-all duration-300 focus:outline-none focus:ring-4 focus:ring-indigo-300 focus:ring-offset-2 focus:ring-offset-indigo-600">
                            Mulai Tes Sekarang
                            <svg class="ml-2 w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bento Grid Section -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Kartu Kiri: Riwayat Rekomendasi (Lebar 2 Kolom) -->
                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100 lg:col-span-2 flex flex-col justify-center hover:shadow-md transition-shadow">
                    <div class="flex flex-col sm:flex-row items-start sm:items-center gap-6">
                        <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center text-blue-600 shrink-0">
                            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div class="flex-1">
                            <h4 class="text-xl font-bold text-gray-900 mb-2">Riwayat Analisis & Hasil</h4>
                            <p class="text-gray-500 mb-4 text-sm leading-relaxed">
                                Lihat kembali hasil tes yang pernah kamu kerjakan. Mesin inferensi SPK kami menyimpan rincian kecocokan program studi untuk membantumu mematangkan keputusan.
                            </p>
                            <a href="#" class="inline-flex items-center text-sm font-bold text-blue-600 hover:text-blue-800 transition-colors group">
                                Buka Riwayat Tes
                                <svg class="w-4 h-4 ml-1 transform group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Kartu Kanan: Status/Statistik Ringkas -->
                <div class="bg-white rounded-[2rem] p-8 shadow-sm border border-gray-100 flex flex-col justify-center items-center text-center hover:shadow-md transition-shadow relative overflow-hidden">
                    <!-- Latar Belakang Kartu (Garis) -->
                    <div class="absolute inset-0 opacity-[0.03] pointer-events-none" style="background-image: radial-gradient(circle at 2px 2px, black 1px, transparent 0); background-size: 16px 16px;"></div>
                    
                    <div class="w-16 h-16 bg-amber-50 rounded-full flex items-center justify-center text-amber-500 mb-5 relative z-10">
                         <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>
                    <h4 class="text-lg font-bold text-gray-900 mb-1 relative z-10">Status Asesmen</h4>
                    <p class="text-gray-500 text-sm relative z-10">Belum ada tes yang diselesaikan</p>
                    
                    <!-- Progres Bar Statis (Sebagai Pemanis) -->
                    <div class="w-full bg-gray-100 rounded-full h-2.5 mt-6 relative z-10">
                        <div class="bg-amber-400 h-2.5 rounded-full" style="width: 0%"></div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-app-layout>