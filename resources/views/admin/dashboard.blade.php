<x-admin-layout>
    <!-- Tambahkan CDN ApexCharts di bagian atas atau di layout utama -->
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

    <!-- Header Halaman (Tetap) -->
    <div class="mb-8 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-5">
        <div>
            <p class="text-sm font-semibold text-cyan-600 mb-2">Selamat datang kembali, {{ explode(' ', Auth::user()->name)[0] }}</p>
            <h1 class="text-3xl font-black tracking-tight text-slate-900">Pantau rekomendasi jurusan</h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">Kelola data siswa, instrumen tes, dan pantau hasil rekomendasi jurusan dari satu ruang kerja yang ringkas.</p>
        </div>
        
        <!-- Tombol Aksi Cepat -->
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.siswa') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-sm font-semibold text-slate-700 rounded-xl hover:border-cyan-300 hover:text-cyan-700 shadow-sm transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v6m3-3h-6M9 11a4 4 0 100-8 4 4 0 000 8zm-7 9a7 7 0 0114 0"></path></svg>
                Data siswa
            </a>
            <a href="{{ route('admin.pertanyaan') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-900 text-white text-sm font-semibold rounded-xl hover:bg-cyan-700 shadow-sm transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Kelola bank soal
            </a>
        </div>
    </div>

    <!-- Grid Statistik Cards (Tetap) -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
        <!-- Widget 1: Total Siswa -->
        <a href="{{ route('admin.siswa') }}" class="group bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex items-center justify-between hover:-translate-y-0.5 hover:shadow-lg hover:border-cyan-200 transition-all">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Total siswa</p>
                <h3 class="text-4xl font-black text-slate-900">{{ $totalSiswa }}</h3>
                <p class="mt-2 text-xs font-medium text-cyan-700">Lihat semua data <span class="group-hover:ml-1 transition-all">→</span></p>
            </div>
            <div class="w-14 h-14 bg-cyan-50 rounded-2xl flex items-center justify-center text-cyan-600">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </a>

        <!-- Widget 2: Bank Soal -->
        <a href="{{ route('admin.pertanyaan') }}" class="group bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex items-center justify-between hover:-translate-y-0.5 hover:shadow-lg hover:border-violet-200 transition-all">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Bank pertanyaan</p>
                <h3 class="text-4xl font-black text-slate-900">{{ $totalSoal }}</h3>
                <p class="mt-2 text-xs font-medium text-violet-700">Kelola instrumen <span class="group-hover:ml-1 transition-all">→</span></p>
            </div>
            <div class="w-14 h-14 bg-violet-50 rounded-2xl flex items-center justify-center text-violet-600">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
        </a>

        <!-- Widget 3: Tes Selesai -->
        <div class="bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-800 flex items-center justify-between relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Tes diselesaikan</p>
                <h3 class="text-4xl font-black text-white">{{ $totalSudahTes }}</h3>
                <p class="mt-2 text-xs font-medium text-emerald-300">Siswa unik yang selesai</p>
            </div>
            <div class="relative z-10 w-14 h-14 bg-emerald-400/15 rounded-2xl flex items-center justify-center text-emerald-300">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
            <!-- Dekorasi background -->
            <div class="absolute -right-6 -bottom-6 w-32 h-32 rounded-full border-[12px] border-slate-800/50"></div>
        </div>
    </div>

    <!-- AREA BARU: Grafik & Aktivitas Terbaru -->
    <div class="grid grid-cols-1 xl:grid-cols-3 gap-5 mb-8">
        
        <!-- Kolom Kiri (Lebar 2/3): Grafik Distribusi Jurusan -->
        <div class="xl:col-span-2 bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Distribusi Rekomendasi Jurusan</h3>
                    <p class="mt-1 text-xs text-slate-500">Statistik jurusan yang direkomendasikan kepada siswa.</p>
                </div>
            </div>
            <div class="p-4">
                <!-- Kontainer ApexCharts: ID diubah menjadi jurusanApexChart -->
                <div id="jurusanApexChart" class="w-full h-[300px]"></div>
            </div>
        </div>

        <!-- Kolom Kanan (Lebar 1/3): Ruang Kerja Cepat & Status -->
        <div class="flex flex-col gap-5">
            <!-- Status Sistem -->
            <div class="bg-gradient-to-br from-cyan-600 to-blue-700 rounded-2xl shadow-sm p-6 text-white relative overflow-hidden flex-1">
                <div class="relative z-10">
                    <p class="text-xs font-bold uppercase tracking-[0.16em] text-cyan-200">Status sistem</p>
                    <h3 class="mt-2 text-2xl font-bold">Rekomendasi Jurusan Aktif</h3>
                    <p class="mt-2 text-sm leading-relaxed text-cyan-50">Sistem rekomendasi jurusan berjalan normal dan siap menerima respons siswa.</p>
                    <div class="mt-6 inline-flex items-center gap-2 px-3 py-1.5 rounded-full bg-white/20 backdrop-blur-sm text-sm font-semibold text-white">
                        <span class="w-2 h-2 rounded-full bg-emerald-300 animate-pulse"></span> Sistem Online
                    </div>
                </div>
                <!-- Dekorasi -->
                <div class="absolute -right-10 -top-10 w-40 h-40 rounded-full border-[20px] border-white/10"></div>
            </div>

            <!-- Ruang Kerja Cepat (Diperkecil menjadi list) -->
            <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-5 flex-1">
                <h3 class="text-sm font-bold text-slate-900 mb-4">Aksi Cepat</h3>
                <div class="space-y-3">
                    <a href="{{ route('admin.siswa') }}" class="flex items-center gap-4 p-3 rounded-xl border border-slate-100 hover:border-cyan-300 hover:bg-cyan-50 group transition-all">
                        <div class="w-10 h-10 rounded-lg bg-slate-50 flex items-center justify-center text-slate-500 group-hover:bg-cyan-100 group-hover:text-cyan-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v6m3-3h-6M9 11a4 4 0 100-8 4 4 0 000 8zm-7 9a7 7 0 0114 0"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">Periksa Siswa</p>
                            <p class="text-xs text-slate-500">Daftar peserta tes baru</p>
                        </div>
                    </a>
                    <a href="{{ route('admin.pertanyaan') }}" class="flex items-center gap-4 p-3 rounded-xl border border-slate-100 hover:border-violet-300 hover:bg-violet-50 group transition-all">
                        <div class="w-10 h-10 rounded-lg bg-slate-50 flex items-center justify-center text-slate-500 group-hover:bg-violet-100 group-hover:text-violet-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                        </div>
                        <div>
                            <p class="text-sm font-bold text-slate-800">Bank Soal AI</p>
                            <p class="text-xs text-slate-500">Kelola instrumen tes</p>
                        </div>
                    </a>
                </div>
            </div>

            <div class="p-6 bg-white rounded-lg shadow-md">
                <h3 class="text-lg font-semibold mb-4">Statistik (Chart.js)</h3>
                <!-- Kontainer Chart.js: ID diubah menjadi jurusanChartJs -->
                <canvas id="jurusanChartJs" class="w-full h-64"></canvas>
            </div>
        </div>
    </div>

    <!-- AREA BARU: Tabel Siswa Terbaru -->
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
        <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
            <h3 class="text-base font-bold text-slate-900">Aktivitas Tes Terbaru</h3>
            <button class="text-sm font-medium text-cyan-600 hover:text-cyan-800">Lihat Laporan Lengkap</button>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left text-sm text-slate-600">
                <thead class="bg-slate-50 text-xs uppercase font-semibold text-slate-500 border-b border-slate-200">
                    <tr>
                        <th class="px-6 py-4">Nama Siswa</th>
                        <th class="px-6 py-4">Waktu Selesai</th>
                        <th class="px-6 py-4">Rekomendasi Jurusan</th>
                        <th class="px-6 py-4 text-right">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentTests as $test)
                        <tr class="hover:bg-slate-50/50 transition-colors">
                            <td class="px-6 py-4 font-medium text-slate-900">
                                {{ $test->user?->name ?? 'Pengguna tidak ditemukan' }}
                            </td>
                            <td class="px-6 py-4 text-slate-500">
                                {{ $test->created_at?->format('d M Y, H:i') ?? 'Waktu tidak tersedia' }}
                            </td>
                            <td class="px-6 py-4">
                                @if($test->recommended_cluster)
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-emerald-100 text-emerald-700">
                                        {{ $test->recommended_cluster }}
                                    </span>
                                @else
                                    <span class="inline-flex px-2.5 py-1 rounded-full text-xs font-bold bg-slate-100 text-slate-600">
                                        Belum tersedia
                                    </span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-right">
                                <a href="{{ route('admin.siswa') }}" class="text-cyan-600 hover:text-cyan-900 font-medium">
                                    Detail
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-slate-500">
                                Belum ada data tes yang diselesaikan siswa.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

<!-- Load Chart.js CDN -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<!-- Script Inisialisasi Chart.js -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Selector diubah menjadi jurusanChartJs
        const ctx = document.getElementById('jurusanChartJs').getContext('2d');

        const chartLabels = @json($labels ?? $chartLabels ?? []);
        const chartData = @json($totals ?? $chartData ?? []);

        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: chartLabels,
                datasets: [{
                    label: 'Jumlah Siswa',
                    data: chartData,
                    backgroundColor: [
                        'rgba(54, 162, 235, 0.6)',
                        'rgba(255, 99, 132, 0.6)',
                        'rgba(255, 206, 86, 0.6)',
                        'rgba(75, 192, 192, 0.6)'
                    ],
                    borderColor: [
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 99, 132, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(75, 192, 192, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0
                        }
                    }
                }
            }
        });
    });
</script>

<!-- Script Inisialisasi Grafik ApexCharts -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        var options = {
            series: [{
                name: 'Jumlah Siswa',
                data: {!! $chartData ?? $totals ?? '[45, 32, 15]' !!} 
            }],
            chart: {
                type: 'bar',
                height: 320,
                toolbar: { show: false },
                fontFamily: 'inherit'
            },
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    horizontal: false,
                    columnWidth: '40%',
                    distributed: true
                }
            },
            colors: ['#06b6d4', '#8b5cf6', '#10b981', '#f59e0b'], 
            dataLabels: {
                enabled: true,
                style: { fontSize: '12px', fontWeight: 'bold' }
            },
            xaxis: {
                categories: {!! $chartLabels ?? $labels ?? "['IPA', 'IPS', 'BAHASA']" !!},
                axisBorder: { show: false },
                axisTicks: { show: false },
                labels: { style: { colors: '#64748b', fontWeight: 500 } }
            },
            yaxis: {
                labels: { style: { colors: '#64748b' } }
            },
            grid: {
                borderColor: '#f1f5f9',
                strokeDashArray: 4,
                yaxis: { lines: { show: true } }
            },
            legend: { show: false },
            tooltip: {
                theme: 'light',
                y: { formatter: function (val) { return val + " Siswa" } }
            }
        };

        // Selector diubah menjadi #jurusanApexChart
        var chart = new ApexCharts(document.querySelector("#jurusanApexChart"), options);
        chart.render();
    });
</script>
</x-admin-layout>