<x-app-layout>
    
    <!-- Load Pustaka ApexCharts dari CDN untuk Grafik Interaktif -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <div class="py-10">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">

            <!-- Flash Message Sukses -->
            @if(session('success'))
                <div class="bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-xl shadow-sm flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                        <svg class="w-6 h-6 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <p class="font-medium text-sm">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Hero Banner Section -->
            <div class="relative bg-gradient-to-br from-indigo-600 via-blue-600 to-blue-500 rounded-[2rem] shadow-xl overflow-hidden group">
                <div class="absolute top-0 right-0 -mr-16 -mt-16 w-64 h-64 rounded-full bg-white opacity-10 blur-2xl"></div>
                <div class="relative px-6 py-12 sm:px-12 sm:py-16 flex flex-col md:flex-row items-center justify-between gap-8">
                    <div class="text-white text-center md:text-left max-w-2xl">
                        <span class="inline-block py-1 px-3 rounded-full bg-white/25 backdrop-blur-sm text-sm font-semibold mb-4 border border-white/20">
                            Beranda Siswa SMK Negeri 1 Tatapaan
                        </span>
                        <h3 class="text-3xl sm:text-4xl font-extrabold mb-3 tracking-tight">
                            Halo, {{ explode(' ', Auth::user()->name)[0] }}! 🚀
                        </h3>
                        <p class="text-blue-100 text-base sm:text-lg leading-relaxed">
                            @if(isset($latestScore) && $latestScore)
                                Rekomendasi klaster jurusan terbaikmu adalah <strong class="text-white underline">{{ $latestScore->recommended_cluster }}</strong>.
                            @else
                                Belum ada data asesmen. Mulai tes sekarang untuk mengetahui rekomendasi jurusan SMK yang paling tepat untukmu.
                            @endif
                        </p>
                    </div>
                    
                    <div class="shrink-0 z-10 flex flex-col sm:flex-row gap-3">
                         <a href="{{ route('assessment.fase1') }}" class="inline-flex items-center justify-center px-6 py-3.5 bg-white text-indigo-600 rounded-full font-extrabold text-base hover:bg-gray-50 hover:scale-105 transition-all duration-300 shadow-lg">
                            @if(isset($latestScore) && $latestScore)
                                Ulangi Tes Asesmen
                            @else
                                Mulai Tes Sekarang
                            @endif
                            <svg class="ml-2 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M13 7l5 5m0 0l-5 5m5-5H6"></path></svg>
                        </a>
                    </div>
                </div>
            </div>

            <!-- Bagian Visualisasi 2 Grafik Interaktif & Detail Hasil -->
            <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

                <!-- Kumpulan 2 Grafik (Lebar 2 Kolom) -->
                <div class="lg:col-span-2 space-y-6">
                    
                    <!-- Grafik 1: Radar Chart RIASEC -->
                    <div class="bg-white rounded-[2rem] p-6 sm:p-8 shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between mb-4 border-b pb-4">
                            <div>
                                <h4 class="text-xl font-bold text-gray-900">Peta Minat RIASEC</h4>
                                <p class="text-gray-400 text-xs mt-0.5">Profil enam dimensi minat berdasarkan hasil assessment</p>
                            </div>
                            <span class="{{ isset($latestScore) && $latestScore ? 'bg-emerald-50 text-emerald-600' : 'bg-indigo-50 text-indigo-600' }} px-3 py-1 rounded-full text-xs font-bold">
                                {{ isset($latestScore) && $latestScore ? 'Aktif' : 'Kosong' }}
                            </span>
                        </div>

                        @if(isset($latestScore) && $latestScore && $latestScore->riasec_scores)
                            <div id="radarChart" class="w-full flex justify-center py-2"></div>
                        @else
                            <div class="py-12 text-center">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path></svg>
                                <h5 class="text-base font-semibold text-gray-700">Grafik Belum Tersedia</h5>
                                <p class="text-sm text-gray-400 mt-1">Selesaikan tes asesmen terlebih dahulu untuk merender grafik potensi.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Grafik 2: Bar Chart RIASEC -->
                    <div class="bg-white rounded-[2rem] p-6 sm:p-8 shadow-sm border border-gray-100">
                        <div class="flex items-center justify-between mb-4 border-b pb-4">
                            <div>
                                <h4 class="text-xl font-bold text-gray-900">Perolehan Nilai RIASEC</h4>
                                <p class="text-gray-400 text-xs mt-0.5">Perbandingan persentase skor setiap dimensi minat</p>
                            </div>
                        </div>

                        @if(isset($latestScore) && $latestScore && $latestScore->riasec_scores)
                            <div id="barChart" class="w-full flex justify-center py-2"></div>
                        @else
                            <div class="py-12 text-center">
                                <h5 class="text-base font-semibold text-gray-700">Grafik Batang Belum Tersedia</h5>
                                <p class="text-sm text-gray-400 mt-1">Lakukan tes untuk melihat grafik batang perolehan nilai.</p>
                            </div>
                        @endif
                    </div>

                </div>

                <!-- Kartu Samping: Status, Progres, & Tombol Riwayat Asesmen -->
                <div class="space-y-6 flex flex-col justify-between">
                    <div class="bg-white rounded-[2rem] p-6 sm:p-8 shadow-sm border border-gray-100 flex flex-col justify-between flex-grow">
                        <div>
                            <div class="w-12 h-12 bg-amber-50 rounded-2xl flex items-center justify-center text-amber-500 mb-4 font-bold text-xl">
                                📊
                            </div>
                            <h4 class="text-lg font-bold text-gray-900 mb-1">Status Asesmen</h4>
                            <p class="text-gray-500 text-sm mb-4">
                                @if(isset($latestScore) && $latestScore)
                                    Hasil analisis K-Means berhasil disimpan.
                                @else
                                    Belum ada asesmen yang dikerjakan.
                                @endif
                            </p>

                            @if(isset($latestScore) && $latestScore)
                                <div class="mb-5 rounded-2xl bg-indigo-50 border border-indigo-100 p-4">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-indigo-500">Rekomendasi Jurusan</p>
                                    <p class="mt-1 text-base font-extrabold leading-snug text-indigo-900">
                                        {{ $latestScore->recommended_cluster }}
                                    </p>
                                </div>

                                @if($riasecInsight)
                                    <div class="rounded-2xl bg-gray-50 border border-gray-100 p-4">
                                        <div class="flex items-center justify-between gap-3 mb-2">
                                            <p class="text-xs font-semibold uppercase tracking-wide text-gray-500">Karakteristik Utama</p>
                                            <span class="rounded-full bg-white px-2.5 py-1 text-xs font-bold text-indigo-600 shadow-sm">
                                                {{ $riasecInsight['label'] }} {{ $riasecInsight['score'] }}%
                                            </span>
                                        </div>
                                        <p class="text-sm leading-relaxed text-gray-600">
                                            {{ $riasecInsight['description'] }}
                                        </p>
                                    </div>
                                @endif
                            @endif
                        </div>

                        <div class="space-y-4 mt-4">
                            <div>
                                <div class="flex justify-between text-xs font-semibold text-gray-500 mb-1">
                                    <span>Progres Penyelesaian</span>
                                    <span>{{ isset($latestScore) && $latestScore ? '100%' : '0%' }}</span>
                                </div>
                                <div class="w-full bg-gray-100 rounded-full h-3">
                                    <div class="{{ isset($latestScore) && $latestScore ? 'bg-emerald-500' : 'bg-amber-400' }} h-3 rounded-full transition-all duration-500" style="width: {{ isset($latestScore) && $latestScore ? '100%' : '0%' }}"></div>
                                </div>
                            </div>

                            <!-- TOMBOL AKSI KE RIWAYAT ASESMEN -->
                            <div class="pt-2">
                                <a href="{{ route('assessment.index') }}" class="w-full inline-flex items-center justify-center px-4 py-3 bg-gray-900 hover:bg-gray-800 text-white rounded-xl text-sm font-semibold shadow transition-all">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    Riwayat Asesmen
                                </a>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <!-- Script Inisialisasi 2 Grafik ApexCharts (Hanya dirender jika data ada) -->
    @if(isset($latestScore) && $latestScore && $latestScore->riasec_scores)
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            const riasecScores = @json($latestScore->riasec_scores);
            const scoreData = ['r', 'i', 'a', 's', 'e', 'c'].map(function(dimensi) {
                return Number(riasecScores[dimensi] || 0);
            });
            const categoriesList = ['Realistic (R)', 'Investigative (I)', 'Artistic (A)', 'Social (S)', 'Enterprising (E)', 'Conventional (C)'];

            // 1. Konfigurasi Grafik Radar
            var radarOptions = {
                series: [{
                    name: 'Skor RIASEC',
                    data: scoreData,
                }],
                chart: {
                    height: 320,
                    type: 'radar',
                    toolbar: { show: false }
                },
                colors: ['#3b82f6'],
                markers: {
                    size: 5,
                    colors: ['#ffffff'],
                    strokeColors: '#3b82f6',
                    strokeWidth: 3,
                },
                xaxis: {
                    categories: categoriesList,
                    labels: {
                        style: {
                            colors: ['#374151', '#374151', '#374151', '#374151', '#374151'],
                            fontSize: '12px',
                            fontWeight: 600
                        }
                    }
                },
                yaxis: { show: false },
                fill: {
                    opacity: 0.4,
                    colors: ['#3b82f6']
                },
                stroke: {
                    show: true,
                    width: 3,
                    colors: ['#2563eb']
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + "%";
                        }
                    }
                }
            };
            var radarChart = new ApexCharts(document.querySelector("#radarChart"), radarOptions);
            radarChart.render();

            // 2. Konfigurasi Grafik Batang (Bar Chart) yang Menarik
            var barOptions = {
                series: [{
                    name: 'Nilai RIASEC',
                    data: scoreData
                }],
                chart: {
                    type: 'bar',
                    height: 300,
                    toolbar: { show: false }
                },
                plotOptions: {
                    bar: {
                        borderRadius: 8,
                        distributed: true, // Warna batang berbeda-beda setiap kriteria agar estetik
                        columnWidth: '55%',
                    }
                },
                colors: ['#6366f1', '#3b82f6', '#10b981', '#f59e0b', '#ec4899', '#14b8a6'],
                dataLabels: {
                    enabled: false
                },
                xaxis: {
                    categories: categoriesList,
                    labels: {
                        style: {
                            fontSize: '12px',
                            fontWeight: 600,
                            colors: '#374151'
                        }
                    }
                },
                yaxis: {
                    max: 100
                },
                legend: {
                    show: false
                },
                tooltip: {
                    y: {
                        formatter: function(val) {
                            return val + "%";
                        }
                    }
                }
            };
            var barChart = new ApexCharts(document.querySelector("#barChart"), barOptions);
            barChart.render();
        });
    </script>
    @endif
</x-app-layout>