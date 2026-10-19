<x-app-layout>
    
    <!-- Load Pustaka ApexCharts dari CDN untuk Grafik Interaktif -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <div class="min-h-screen bg-slate-50 py-8 sm:py-10">
        <div class="mx-auto max-w-7xl space-y-6 px-4 sm:px-6 lg:px-8">

            <!-- Flash Message Sukses -->
            @if(session('success'))
                <div class="flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-700 shadow-sm">
                    <div class="flex items-center space-x-3">
                        <svg class="mt-0.5 h-5 w-5 shrink-0 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        <p class="text-sm font-medium">{{ session('success') }}</p>
                    </div>
                </div>
            @endif

            <!-- Hero Banner Section -->
            <div class="relative overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-700 via-blue-700 to-blue-600 shadow-xl">
                <div class="absolute -right-20 -top-24 h-72 w-72 rounded-full bg-white/10 blur-2xl"></div>
                <div class="absolute -bottom-24 left-1/3 h-52 w-52 rounded-full bg-indigo-300/10 blur-2xl"></div>
                <div class="relative flex flex-col gap-8 px-6 py-8 sm:px-10 sm:py-10 md:flex-row md:items-center md:justify-between">
                    <div class="max-w-2xl text-center text-white md:text-left">
                        <span class="mb-4 inline-flex items-center rounded-full border border-white/20 bg-white/15 px-3 py-1 text-xs font-semibold text-blue-50 backdrop-blur-sm sm:text-sm">
                            Beranda Siswa SMK Negeri 1 Tatapaan
                        </span>
                        <h1 class="mb-3 text-3xl font-extrabold tracking-tight sm:text-4xl">
                            Halo, {{ explode(' ', Auth::user()->name)[0] }}!
                        </h1>
                        <p class="text-sm leading-relaxed text-blue-100 sm:text-base">
                            @if(isset($latestScore) && $latestScore)
                                Rekomendasi klaster jurusan terbaikmu adalah <strong class="text-white underline">{{ $latestScore->recommended_cluster }}</strong>.
                            @else
                                Belum ada data asesmen. Mulai tes sekarang untuk mengetahui rekomendasi jurusan SMK yang paling tepat untukmu.
                            @endif
                        </p>
                    </div>
                    
                    <div class="z-10 flex shrink-0 flex-col gap-3 sm:flex-row">
                         <a href="{{ route('assessment.fase1') }}" class="inline-flex items-center justify-center rounded-xl bg-white px-6 py-3.5 text-sm font-extrabold text-indigo-700 shadow-lg transition hover:bg-blue-50 sm:text-base">
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
            <div class="grid grid-cols-1 items-start gap-6 lg:grid-cols-3">

                <!-- Kumpulan 2 Grafik (Lebar 2 Kolom) -->
                <div class="space-y-6 lg:col-span-2">
                    
                    <!-- Grafik 1: Radar Chart RIASEC -->
                    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
                        <div class="mb-5 flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                            <div>
                                <h2 class="text-lg font-bold text-slate-900 sm:text-xl">Peta Minat RIASEC</h2>
                                <p class="mt-1 text-xs leading-relaxed text-slate-400">Profil enam dimensi minat berdasarkan hasil asesmen.</p>
                            </div>
                            <span class="{{ isset($latestScore) && $latestScore && $latestScore->riasec_scores ? 'bg-emerald-50 text-emerald-600' : 'bg-slate-100 text-slate-500' }} rounded-full px-3 py-1 text-xs font-bold">
                                {{ isset($latestScore) && $latestScore ? 'Aktif' : 'Kosong' }}
                            </span>
                        </div>

                        @if(isset($latestScore) && $latestScore && $latestScore->riasec_scores)
                            <div id="radarChart" class="flex min-h-[320px] w-full items-center justify-center"></div>
                        @else
                            <div class="py-12 text-center">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M11 3.055A9.001 9.001 0 1020.945 13H11V3.055z"></path></svg>
                                <h3 class="text-base font-semibold text-slate-700">Grafik belum tersedia</h3>
                                <p class="mt-1 text-sm text-slate-400">Selesaikan asesmen untuk melihat profil minat.</p>
                            </div>
                        @endif
                    </div>

                    <!-- Grafik 2: Kemampuan Bakat Dasar CHC -->
                    <div class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
                        <div class="mb-5 flex items-start justify-between gap-4 border-b border-slate-100 pb-4">
                            <div>
                                <h2 class="text-lg font-bold text-slate-900 sm:text-xl">Profil Bakat Dasar CHC</h2>
                                <p class="mt-1 text-xs leading-relaxed text-slate-400">
                                    Arahkan kursor ke grafik untuk melihat nilai setiap kemampuan.
                                </p>
                            </div>
                            <span class="{{ isset($latestScore) && $latestScore && $latestScore->chc_scores ? 'bg-indigo-50 text-indigo-600' : 'bg-slate-100 text-slate-500' }} whitespace-nowrap rounded-full px-3 py-1 text-xs font-bold">
                                {{ isset($latestScore) && $latestScore && $latestScore->chc_scores ? 'Aktif' : 'Kosong' }}
                            </span>
                        </div>

                        @if(isset($latestScore) && $latestScore && $latestScore->chc_scores)
                            <div id="chcChart" class="min-h-[350px] w-full"></div>
                            <div class="mt-2 flex items-center justify-center gap-2 text-xs text-slate-400">
                                <span class="inline-block h-2 w-2 rounded-full bg-indigo-500"></span>
                                Persentase jawaban benar
                            </div>
                        @else
                            <div class="py-12 text-center">
                                <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 17v-2m3 2v-4m3 4V9m2 10H7a2 2 0 01-2-2V7a2 2 0 012-2h10a2 2 0 012 2v10a2 2 0 01-2 2z" />
                                </svg>
                                <h3 class="text-base font-semibold text-slate-700">Grafik belum tersedia</h3>
                                <p class="mt-1 text-sm text-slate-400">Selesaikan asesmen untuk melihat profil bakat dasar.</p>
                            </div>
                        @endif
                    </div>

                </div>

                <!-- Kartu Samping: Status, Progres, & Tombol Riwayat Asesmen -->
                <div class="flex flex-col gap-6">
                    <div class="flex flex-grow flex-col justify-between rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-7">
                        <div>
                            <div class="mb-4 flex h-12 w-12 items-center justify-center rounded-2xl bg-amber-50 text-amber-500">
                                <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20V10m5 10V4m5 16v-7M4 20h16" />
                                </svg>
                            </div>
                            <h2 class="mb-1 text-lg font-bold text-slate-900">Status asesmen</h2>
                            <p class="mb-5 text-sm leading-relaxed text-slate-500">
                                @if(isset($latestScore) && $latestScore)
                                    Hasil analisis K-Means berhasil disimpan.
                                @else
                                    Belum ada asesmen yang dikerjakan.
                                @endif
                            </p>

                            @if(isset($latestScore) && $latestScore)
                                <div class="mb-5 rounded-2xl border border-indigo-100 bg-indigo-50 p-4">
                                    <p class="text-xs font-semibold uppercase tracking-wide text-indigo-500">Rekomendasi jurusan</p>
                                    <p class="mt-1 text-base font-extrabold leading-snug text-indigo-900">
                                        {{ $latestScore->recommended_cluster }}
                                    </p>
                                </div>

                                @if($riasecInsight)
                                    <div class="rounded-2xl border border-slate-200 bg-slate-50 p-4">
                                        <div class="mb-2 flex items-center justify-between gap-3">
                                            <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Karakteristik utama</p>
                                            <span class="rounded-full bg-white px-2.5 py-1 text-xs font-bold text-indigo-600 shadow-sm">
                                                {{ $riasecInsight['label'] }} {{ $riasecInsight['score'] }}%
                                            </span>
                                        </div>
                                        <p class="text-sm leading-relaxed text-slate-600">
                                            {{ $riasecInsight['description'] }}
                                        </p>
                                    </div>
                                @endif
                            @endif
                        </div>

                        <div class="mt-6 space-y-4 border-t border-slate-100 pt-5">
                            <div>
                                <div class="mb-1.5 flex justify-between text-xs font-semibold text-slate-500">
                                    <span>Progres penyelesaian</span>
                                    <span>{{ isset($latestScore) && $latestScore ? '100%' : '0%' }}</span>
                                </div>
                                <div class="h-2.5 w-full overflow-hidden rounded-full bg-slate-100">
                                    <div class="{{ isset($latestScore) && $latestScore ? 'bg-emerald-500' : 'bg-amber-400' }} h-full rounded-full transition-all duration-500" style="width: {{ isset($latestScore) && $latestScore ? '100%' : '0%' }}"></div>
                                </div>
                            </div>

                            <!-- TOMBOL AKSI KE RIWAYAT ASESMEN -->
                            <div class="pt-2">
                                <a href="{{ route('assessment.index') }}" class="inline-flex w-full items-center justify-center rounded-xl bg-slate-900 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-700">
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

    <!-- Script Inisialisasi Grafik ApexCharts -->
    @if(isset($latestScore) && $latestScore && ($latestScore->riasec_scores || $latestScore->chc_scores))
    <script>
        document.addEventListener("DOMContentLoaded", function() {
            @if($latestScore->riasec_scores)
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
            @endif

            @if($latestScore->chc_scores)
            const chcScores = @json($latestScore->chc_scores);
            const chcIndicators = [
                { key: 'gf', label: 'Fluid Reasoning (Gf)' },
                { key: 'gc', label: 'Comprehension-Knowledge (Gc)' },
                { key: 'gv', label: 'Visual Processing (Gv)' },
                { key: 'gq', label: 'Quantitative Knowledge (Gq)' },
                { key: 'gwm', label: 'Short-Term Working Memory (Gwm)' },
                { key: 'gs', label: 'Processing Speed (Gs)' }
            ];

            var chcOptions = {
                series: [{
                    name: 'Skor CHC',
                    data: chcIndicators.map(function(indicator) {
                        return Number(chcScores[indicator.key] || 0);
                    })
                }],
                chart: {
                    type: 'bar',
                    height: 350,
                    toolbar: {
                        show: true,
                        tools: {
                            download: false,
                            selection: false,
                            zoom: false,
                            zoomin: false,
                            zoomout: false,
                            pan: false,
                            reset: false
                        }
                    },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 700
                    }
                },
                plotOptions: {
                    bar: {
                        horizontal: true,
                        borderRadius: 7,
                        barHeight: '58%',
                        distributed: true,
                        dataLabels: { position: 'top' }
                    }
                },
                colors: ['#6366f1', '#4f46e5', '#7c3aed', '#8b5cf6', '#6366f1', '#4f46e5'],
                dataLabels: {
                    enabled: true,
                    textAnchor: 'start',
                    offsetX: 8,
                    style: {
                        colors: ['#334155'],
                        fontSize: '12px',
                        fontWeight: 700
                    },
                    formatter: function(val) {
                        return val + '%';
                    }
                },
                xaxis: {
                    categories: chcIndicators.map(function(indicator) {
                        return indicator.label;
                    }),
                    min: 0,
                    max: 100,
                    tickAmount: 5,
                    labels: {
                        formatter: function(val) {
                            return val + '%';
                        },
                        style: { colors: '#94a3b8', fontSize: '11px' }
                    }
                },
                yaxis: {
                    labels: {
                        maxWidth: 185,
                        style: {
                            colors: '#475569',
                            fontSize: '11px',
                            fontWeight: 600
                        }
                    }
                },
                grid: {
                    borderColor: '#e2e8f0',
                    strokeDashArray: 4
                },
                tooltip: {
                    theme: 'light',
                    y: {
                        formatter: function(val) {
                            return val + '% jawaban benar';
                        }
                    }
                }
            };

            var chcChart = new ApexCharts(document.querySelector("#chcChart"), chcOptions);
            chcChart.render();
            @endif

        });
    </script>
    @endif
</x-app-layout>