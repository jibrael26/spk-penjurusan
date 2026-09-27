<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Dashboard Rekomendasi Penjurusan') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Notifikasi Sukses -->
            @if (session('success'))
                <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6 rounded-md shadow-sm">
                    {{ session('success') }}
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if(isset($latestScore))
                    <!-- Jika Siswa Sudah Mengikuti Tes -->
                    <div class="text-center py-6">
                        <span class="bg-blue-100 text-blue-800 text-sm font-semibold px-4 py-1.5 rounded-full uppercase tracking-wider">
                            Hasil Analisis K-Means Selesai
                        </span>
                        
                        <h3 class="text-gray-600 mt-4 text-lg">Rekomendasi Jurusan SMK untuk Anda:</h3>
                        
                        <div class="mt-3 p-6 bg-blue-600 text-white text-2xl font-bold rounded-xl shadow-md inline-block max-w-2xl">
                            {{ $latestScore->recommended_cluster }}
                        </div>

                        <div class="mt-8 text-left bg-gray-50 p-6 rounded-lg border">
                            <h4 class="font-bold text-gray-700 mb-3">Rincian Perolehan Skor per Kriteria:</h4>
                            <div class="grid grid-cols-2 sm:grid-cols-5 gap-4 text-center">
                                <div class="bg-white p-3 rounded shadow-sm border">
                                    <span class="block text-xs text-gray-500 font-semibold">K01 (ATPH)</span>
                                    <span class="text-xl font-bold text-blue-600">{{ $latestScore->k01 }}</span>
                                </div>
                                <div class="bg-white p-3 rounded shadow-sm border">
                                    <span class="block text-xs text-gray-500 font-semibold">K02 (APHP)</span>
                                    <span class="text-xl font-bold text-blue-600">{{ $latestScore->k02 }}</span>
                                </div>
                                <div class="bg-white p-3 rounded shadow-sm border">
                                    <span class="block text-xs text-gray-500 font-semibold">K03 (AKL)</span>
                                    <span class="text-xl font-bold text-blue-600">{{ $latestScore->k03 }}</span>
                                </div>
                                <div class="bg-white p-3 rounded shadow-sm border">
                                    <span class="block text-xs text-gray-500 font-semibold">K04 (TKRO)</span>
                                    <span class="text-xl font-bold text-blue-600">{{ $latestScore->k04 }}</span>
                                </div>
                                <div class="bg-white p-3 rounded shadow-sm border">
                                    <span class="block text-xs text-gray-500 font-semibold">K05 (TKJ)</span>
                                    <span class="text-xl font-bold text-blue-600">{{ $latestScore->k05 }}</span>
                                </div>
                            </div>
                        </div>

                        <div class="mt-8">
                            <a href="{{ route('assessment.create') }}" class="text-sm text-blue-600 hover:underline font-semibold">
                                &larr; Ulangi Tes Penjurusan
                            </a>
                        </div>
                    </div>
                @else
                    <!-- Jika Siswa Belum Mengikuti Tes -->
                    <div class="text-center py-12">
                        <h3 class="text-2xl font-bold text-gray-800 mb-2">Selamat Datang di Sistem Pakar Penjurusan SMK</h3>
                        <p class="text-gray-600 mb-6 max-w-lg mx-auto">
                            Anda belum mengikuti asesmen penjurusan. Silakan mulai tes untuk mengetahui rekomendasi jurusan terbaik berdasarkan minat dan bakat Anda.
                        </p>
                        <a href="{{ route('assessment.create') }}" class="px-6 py-3 bg-blue-600 text-white font-semibold rounded-lg shadow-md hover:bg-blue-700 transition-all">
                            Mulai Tes Sekarang
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>