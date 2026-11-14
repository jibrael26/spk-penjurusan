<x-admin-layout>
    <div class="py-12 bg-gray-50/50 min-h-screen" x-data="studentTableManager()">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <!-- Section 1: Ringkasan & Grafik -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Card Statistik Assessment -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-semibold mb-4 text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        Status Assessment Siswa
                    </h3>
                    <div class="relative h-64">
                        <canvas id="assessmentChart"></canvas>
                    </div>
                </div>

                <!-- Card Grafik Jurusan -->
                <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                    <h3 class="text-lg font-semibold mb-2 text-gray-800 flex items-center gap-2">
                        <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path></svg>
                        Distribusi Program Keahlian
                    </h3>
                    @if(isset($jurusanTerbanyak) && $jurusanTerbanyak)
                        <p class="text-sm text-gray-500 mb-4 bg-emerald-50 text-emerald-700 px-3 py-1.5 rounded-lg inline-block font-medium">
                            Program Keahlian terbanyak:
                            <span class="font-bold text-emerald-800">{{ $jurusanTerbanyak->recommended_cluster }}</span> 
                            ({{ $jurusanTerbanyak->total }} siswa)
                        </p>
                    @endif
                    <div class="relative h-56">
                        <canvas id="majorChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="grid grid-cols-2 gap-4 md:grid-cols-5">
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Data terukur</p>
                    <p class="mt-1 text-2xl font-black text-slate-900">{{ $clusteringSummary['total'] ?? 0 }}</p>
                    <p class="text-xs text-slate-400">hasil baru</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Rata-rata skor</p>
                    <p class="mt-1 text-2xl font-black text-slate-900">{{ $clusteringSummary['rata_rata_skor'] ?? 0 }}%</p>
                    <p class="text-xs text-slate-400">profil terbaik</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Rata-rata margin</p>
                    <p class="mt-1 text-2xl font-black text-slate-900">{{ $clusteringSummary['rata_rata_margin'] ?? 0 }}%</p>
                    <p class="text-xs text-slate-400">vs kandidat kedua</p>
                </div>
                <div class="rounded-2xl border border-slate-200 bg-white p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-slate-500">Rata-rata yakin</p>
                    <p class="mt-1 text-2xl font-black text-emerald-700">{{ $clusteringSummary['rata_rata_keyakinan'] ?? 0 }}%</p>
                    <p class="text-xs text-slate-400">indikator pemisahan</p>
                </div>
                <div class="rounded-2xl border border-amber-200 bg-amber-50 p-4 shadow-sm">
                    <p class="text-xs font-semibold uppercase tracking-wide text-amber-700">Perlu ditinjau</p>
                    <p class="mt-1 text-2xl font-black text-amber-900">{{ $clusteringSummary['keyakinan_rendah'] ?? 0 }}</p>
                    <p class="text-xs text-amber-700">keyakinan &lt; 50%</p>
                </div>
            </div>

            <!-- Section 2: Tabel hasil pengelompokan -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <div class="mb-4 flex flex-col sm:flex-row sm:items-start sm:justify-between gap-3">
                    <div>
                        <h3 class="text-lg font-semibold text-gray-800">Hasil Pengelompokan Profil</h3>
                        <p class="text-sm text-gray-500 mt-1">Siswa mendapatkan rekomendasi berdasarkan kedekatan skor profil RIASEC dan CHC. Detail kinerja tersedia pada hasil asesmen siswa.</p>
                    </div>
                    <a href="{{ route('admin.export.kmeans') }}"
                       class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                        </svg>
                        Export Hasil Pengelompokan
                    </a>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    @if(isset($hasilCluster) && $hasilCluster->count() > 0)
                        @foreach($hasilCluster as $jurusan => $cluster)
                            <div class="border border-gray-100 rounded-xl p-4 bg-gradient-to-b from-gray-50 to-white shadow-xs">
                                <div class="flex items-center justify-between border-b pb-2 mb-3">
                                    <h4 class="font-bold text-indigo-600">{{ $jurusan }}</h4>
                                    <span class="text-xs bg-indigo-50 text-indigo-700 font-semibold px-2.5 py-0.5 rounded-full">
                                        {{ count($cluster) }} Siswa
                                    </span>
                                </div>
                                <ul class="space-y-2 max-h-60 overflow-y-auto pr-1 text-sm">
                                    @forelse($cluster as $item)
                                        <li class="text-gray-700 flex justify-between items-center bg-white p-2 rounded-lg border border-gray-100">
                                            <span class="font-medium text-gray-800">{{ $item['user']->name ?? $item['name'] }}</span>
                                            <span class="text-xs text-gray-400 font-mono">ID: {{ $item['user']->id ?? $item['id'] }}</span>
                                        </li>
                                    @empty
                                        <li class="text-sm text-gray-400 italic text-center py-4">Belum ada data di cluster ini.</li>
                                    @endforelse
                                </ul>
                            </div>
                        @endforeach
                    @else
                        <div class="md:col-span-3 rounded-xl border border-dashed border-gray-300 bg-gray-50 px-4 py-8 text-center">
                            <p class="font-medium text-gray-600">Belum ada data assessment untuk dikelompokkan.</p>
                            <p class="mt-1 text-sm text-gray-400">Hasil rekomendasi akan tampil setelah siswa menyelesaikan asesmen.</p>
                        </div>
                    @endif
                </div>
            </div>

            <!-- Section 3: Datatable Data Siswa -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <!-- Table Header & Filter -->
                <div class="p-6 border-b border-gray-100 flex flex-col lg:flex-row lg:items-center justify-between gap-4">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800">Manajemen Seluruh Siswa</h3>
                        <p class="text-xs text-gray-500 mt-0.5">Kelola data siswa, lihat rincian asesmen, dan pantau rekomendasi Program Keahlian.</p>
                    </div>
                    
                    <!-- Right Actions: Export Button + Search Input -->
                    <div class="flex flex-col sm:flex-row items-center gap-3 w-full lg:w-auto">
                        <a href="{{ route('admin.export.assessment.answers') }}"
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 text-white text-sm font-semibold rounded-xl shadow-sm transition-colors">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <span>Export Jawaban</span>
                        </a>
                        <!-- Tombol Export Excel -->
                        <a href="{{ route('admin.export.assessment') }}" 
                           class="w-full sm:w-auto inline-flex items-center justify-center gap-2 px-4 py-2 bg-emerald-600 hover:bg-emerald-700 active:bg-emerald-800 text-white text-sm font-semibold rounded-xl shadow-sm hover:shadow-emerald-600/20 transition-all duration-200">
                            <svg class="w-4 h-4 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <span>Export Excel</span>
                        </a>

                        <!-- Search Input -->
                        <form method="GET" action="{{ route('admin.siswa') }}" class="relative w-full sm:w-64">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none text-gray-400">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            </div>
                            <input type="search" name="search" value="{{ $search ?? '' }}"                             placeholder="Cari nama, email, atau Program Keahlian..."
                                   class="w-full pl-9 pr-4 py-2 text-sm border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500/20 focus:border-indigo-500 transition-all outline-none">
                        </form>
                    </div>
                </div>

                <!-- Table Content -->
                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="bg-gray-50/70 text-gray-500 uppercase text-xs tracking-wider font-semibold border-b border-gray-100">
                                <th class="py-3.5 px-6 w-16 text-center">No</th>
                                <th class="py-3.5 px-6">Nama Siswa</th>
                                <th class="py-3.5 px-6">Rekomendasi Program Keahlian</th>
                                <th class="py-3.5 px-6 text-center w-36">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm">
                            @php
                                $usersList = $users ?? $siswa ?? [];
                            @endphp

                            @forelse($usersList as $index => $user)
                                <tr class="hover:bg-indigo-50/30 transition-colors group">
                                    <!-- Nomor -->
                                    <td class="py-4 px-6 text-center font-medium text-gray-500">
                                        {{ method_exists($usersList, 'firstItem') ? $usersList->firstItem() + $index : $index + 1 }}
                                    </td>

                                    <!-- Nama Siswa -->
                                    <td class="py-4 px-6">
                                        <div class="flex items-center gap-3">
                                            <div class="w-9 h-9 rounded-full bg-indigo-100 text-indigo-700 flex items-center justify-center font-bold text-xs uppercase shadow-xs">
                                                {{ substr($user->name, 0, 2) }}
                                            </div>
                                            <div>
                                                <div class="font-semibold text-gray-800 group-hover:text-indigo-600 transition-colors">
                                                    {{ $user->name }}
                                                </div>
                                                <div class="text-xs text-gray-400">{{ $user->email }}</div>
                                            </div>
                                        </div>
                                    </td>

                                    <!-- Rekomendasi Program Keahlian -->
                                    <td class="py-4 px-6">
                                        @php
                                            $score = $user->assessmentScore ?? $user->assessmentScores;
                                            $major = $score->recommended_cluster ?? $score->recommended_major ?? $user->recommended_major ?? null;
                                        @endphp

                                        @if($major)
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200/60">
                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span>
                                                {{ $major }}
                                            </span>
                                        @else
                                            <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-500 border border-gray-200/60">
                                                <span class="w-1.5 h-1.5 rounded-full bg-gray-400"></span>
                                                Belum Assessment
                                            </span>
                                        @endif
                                    </td>

                                    <!-- Kolom Aksi (Icon Only) -->
                                    <td class="py-4 px-6 text-center">
                                        <div class="flex items-center justify-center gap-2">
                                            <!-- Icon 1: Lihat Hasil Assessment -->
                                            <button type="button" 
                                                    @click="openDetailModal({{ json_encode($user) }}, '{{ addslashes($major ?? 'Belum Assessment') }}', '{{ route('admin.users.export-assessment-answers', $user->id) }}')"
                                                    title="Lihat Hasil Assessment"
                                                    class="p-2 text-indigo-600 hover:text-indigo-800 hover:bg-indigo-50 rounded-lg transition-all transform hover:scale-110">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </button>

                                            <a href="{{ route('admin.users.export-assessment-answers', $user->id) }}"
                                               title="Export Jawaban Assessment"
                                               class="p-2 text-emerald-600 hover:text-emerald-800 hover:bg-emerald-50 rounded-lg transition-all transform hover:scale-110">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                                                </svg>
                                            </a>

                                            <!-- Icon 2: Edit Data -->
                                            <button type="button" 
                                                    @click="openEditModal({{ json_encode($user) }})"
                                                    title="Edit Data Siswa"
                                                    class="p-2 text-amber-600 hover:text-amber-800 hover:bg-amber-50 rounded-lg transition-all transform hover:scale-110">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                                </svg>
                                            </button>

                                            <!-- Icon 3: Hapus Data -->
                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST" class="inline-block" @submit.prevent="$dispatch('open-confirmation', { form: $el, title: 'Hapus data siswa?', message: 'Data siswa dan hasil assessment terkait akan dihapus permanen.' })">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                        title="Hapus Data Siswa"
                                                        class="p-2 text-rose-600 hover:text-rose-800 hover:bg-rose-50 rounded-lg transition-all transform hover:scale-110">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                    </svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="py-8 text-center text-gray-400 italic">
                                        Belum ada data siswa yang tersedia.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination Footer -->
                @if(method_exists($usersList, 'hasPages') && $usersList->hasPages())
                    <div class="p-4 border-t border-gray-100 bg-gray-50/50">
                        {{ $usersList->withQueryString()->links() }}
                    </div>
                @endif
            </div>

        </div>

        <!-- ========================================== -->
        <!-- MODAL 1: VERTICALLY CENTERED - LIHAT HASIL -->
        <!-- ========================================== -->
        <div x-show="showDetail" 
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm overflow-y-auto">
            
            <div x-show="showDetail"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                 @click.away="showDetail = false"
                 class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden border border-gray-100">
                
                <!-- Modal Header -->
                <div class="bg-gradient-to-r from-indigo-600 to-indigo-700 p-6 text-white relative">
                    <button @click="showDetail = false" type="button" class="absolute top-4 right-4 text-white/70 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                    <div class="flex items-center gap-3">
                        <div class="p-3 bg-white/10 rounded-xl backdrop-blur-md">
                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold">Hasil Assessment Siswa</h3>
                            <p class="text-xs text-indigo-100">Rincian rekomendasi hasil evaluasi</p>
                        </div>
                    </div>
                </div>

                <!-- Modal Body -->
                <div class="p-6 space-y-4">
                    <div class="bg-indigo-50/50 p-4 rounded-xl border border-indigo-100/60 flex items-center gap-3">
                        <div class="w-12 h-12 rounded-full bg-indigo-600 text-white flex items-center justify-center font-bold text-base shadow-sm">
                            <span x-text="activeUser.name ? activeUser.name.substring(0, 2).toUpperCase() : ''"></span>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-800" x-text="activeUser.name"></h4>
                            <p class="text-xs text-gray-500" x-text="activeUser.email"></p>
                        </div>
                    </div>

                    <div class="space-y-3 pt-2">
                        <div class="flex justify-between items-center py-2 border-b border-gray-100 text-sm">
                            <span class="text-gray-500">ID Siswa</span>
                            <span class="font-mono font-semibold text-gray-700" x-text="'#' + activeUser.id"></span>
                        </div>

                        <div class="py-3 px-4 rounded-xl bg-gray-50 border border-gray-100 space-y-1">
                            <span class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Program Keahlian Utama</span>
                            <div class="flex items-center gap-2">
                                <svg class="w-5 h-5 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span class="font-bold text-emerald-700 text-base" x-text="activeUserMajor"></span>
                            </div>
                        </div>

                        <div x-show="answerRows.length" class="pt-2">
                            <div class="flex items-center justify-between mb-2">
                                <span class="text-xs text-gray-400 uppercase tracking-wider font-semibold">Jawaban Assessment</span>
                                <a :href="activeAnswersExportUrl" title="Export jawaban siswa" class="text-xs font-semibold text-indigo-600 hover:text-indigo-800">Export Excel</a>
                            </div>
                            <div class="max-h-72 overflow-y-auto space-y-2 pr-1">
                                <template x-for="row in answerRows" :key="row.id">
                                    <div class="rounded-lg border border-gray-100 bg-white p-3 text-sm">
                                        <div class="flex items-start justify-between gap-3">
                                            <p class="font-medium text-gray-700" x-text="'F' + row.fase + ' - ' + (row.indikator || 'Pertanyaan')"></p>
                                            <span class="shrink-0 text-xs font-semibold" :class="row.status === 'Benar' ? 'text-emerald-600' : (row.status === 'Salah' ? 'text-rose-600' : 'text-gray-400')" x-text="row.status"></span>
                                        </div>
                                        <p class="mt-1 text-xs text-gray-500" x-text="row.question"></p>
                                        <p class="mt-2 text-xs text-gray-700"><span class="font-semibold">Jawaban:</span> <span x-text="row.answerLabel"></span></p>
                                    </div>
                                </template>
                            </div>
                        </div>
                        <div x-show="showDetail && !answerRows.length" class="rounded-lg border border-dashed border-gray-200 px-3 py-4 text-center text-xs text-gray-400">
                            Jawaban assessment belum tersimpan untuk siswa ini.
                        </div>
                    </div>
                </div>

                <!-- Modal Footer -->
                <div class="p-4 bg-gray-50 border-t border-gray-100 flex justify-end">
                    <button type="button" @click="showDetail = false" class="px-5 py-2.5 bg-gray-200 hover:bg-gray-300 text-gray-700 rounded-xl text-sm font-semibold transition-colors">
                        Tutup
                    </button>
                </div>
            </div>
        </div>

        <!-- ========================================== -->
        <!-- MODAL 2: VERTICALLY CENTERED - EDIT SISWA  -->
        <!-- ========================================== -->
        <div x-show="showEdit" 
             x-cloak
             x-transition:enter="transition ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="transition ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-gray-900/60 backdrop-blur-sm overflow-y-auto">
            
            <div x-show="showEdit"
                 x-transition:enter="transition ease-out duration-300"
                 x-transition:enter-start="opacity-0 scale-95 translate-y-4"
                 x-transition:enter-end="opacity-100 scale-100 translate-y-0"
                 x-transition:leave="transition ease-in duration-200"
                 x-transition:leave-start="opacity-100 scale-100 translate-y-0"
                 x-transition:leave-end="opacity-0 scale-95 translate-y-4"
                 @click.away="showEdit = false"
                 class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden border border-gray-100">
                
                <form :action="editActionUrl" method="POST">
                    @csrf
                    @method('PUT')

                    <!-- Modal Header -->
                    <div class="bg-gradient-to-r from-slate-900 to-slate-800 p-6 text-white relative border-b border-slate-200">
                        <button @click="showEdit = false" type="button" class="absolute top-4 right-4 text-slate-300 hover:text-white p-1 rounded-lg hover:bg-white/10 transition-colors">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                        <div class="flex items-center gap-3">
                            <div class="p-3 bg-cyan-400/15 rounded-xl backdrop-blur-md">
                                <svg class="w-6 h-6 text-cyan-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                            </div>
                            <div>
                                <h3 class="text-lg font-bold">Edit Data Siswa</h3>
                                <p class="text-xs text-slate-300">Perbarui informasi dasar siswa</p>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Body Form -->
                    <div class="p-6 space-y-4">
                        <!-- Input Nama -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Nama Lengkap</label>
                            <input type="text" name="name" x-model="editForm.name" required
                                   class="w-full px-4 py-2.5 text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                        </div>

                        <!-- Input Email -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Email Siswa</label>
                            <input type="email" name="email" x-model="editForm.email" required
                                   class="w-full px-4 py-2.5 text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                        </div>

                        <!-- Input Password Baru -->
                        <div>
                            <label class="block text-xs font-semibold text-gray-600 uppercase mb-1">Password Baru</label>
                            <input type="password" name="password" x-model="editForm.password" placeholder="Kosongkan jika tidak ingin diubah"
                                   class="w-full px-4 py-2.5 text-sm border border-slate-300 rounded-xl focus:ring-2 focus:ring-blue-500/20 focus:border-blue-500 outline-none transition-all">
                            <p class="mt-1 text-xs text-gray-400">*Hanya isi jika ingin mengganti password siswa.</p>
                        </div>
                    </div>

                    <!-- Modal Footer -->
                    <div class="p-4 bg-slate-50 border-t border-slate-200 flex justify-end gap-3">
                        <button type="button" @click="showEdit = false" class="px-4 py-2.5 border border-slate-300 bg-white hover:bg-slate-100 text-slate-700 rounded-xl text-sm font-semibold transition-colors">
                            Batal
                        </button>
                        <button type="submit" class="px-5 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl text-sm font-semibold shadow-sm transition-all focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- Script Alpine.js Manager & Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const questionCatalog = @json($questionCatalog ?? []);

        function studentTableManager() {
            return {
                searchQuery: '',
                showDetail: false,
                showEdit: false,
                activeUser: {},
                activeUserMajor: '',
                activeAnswersExportUrl: '',
                answerRows: [],
                editActionUrl: '',
                editForm: {
                    id: '',
                    name: '',
                    email: '',
                    password: ''
                },

                openDetailModal(user, major, exportUrl) {
                    this.activeUser = user;
                    this.activeUserMajor = major;
                    this.activeAnswersExportUrl = exportUrl;
                    const score = user.assessment_score || user.assessmentScore || {};
                    const answers = score.answers || {};
                    this.answerRows = Object.entries(answers).map(([questionId, answer]) => {
                        const question = questionCatalog.find((item) => String(item.id) === String(questionId));
                        const answerLabel = this.answerLabel(question, answer);
                        return {
                            id: questionId,
                            fase: question?.fase || '-',
                            indikator: question?.kode_indikator || '',
                            question: question?.teks_pertanyaan || 'Pertanyaan tidak ditemukan',
                            answerLabel,
                            status: question && question.fase > 1
                                ? (String(answer) === String(question.kunci_jawaban) ? 'Benar' : 'Salah')
                                : '-'
                        };
                    });
                    this.showDetail = true;
                },

                answerLabel(question, answer) {
                    if (!question) return String(answer);
                    if (Number(question.fase) === 1) {
                        return ({
                            1: 'Sangat Tidak Setuju',
                            2: 'Tidak Setuju',
                            3: 'Netral',
                            4: 'Setuju',
                            5: 'Sangat Setuju'
                        })[Number(answer)] || String(answer);
                    }
                    return question.opsi_jawaban?.[String(answer)]
                        || question.opsi_jawaban?.[Number(answer)]
                        || String(answer);
                },

                openEditModal(siswa) {
                    this.activeUser = siswa;
                    this.editForm = {
                        id: siswa.id,
                        name: siswa.name,
                        email: siswa.email,
                        password: ''
                    };
                    this.editActionUrl = `/admin/users/${siswa.id}`;
                    this.showEdit = true;
                }
            }
        }

        document.addEventListener("DOMContentLoaded", function() {
            // Render Pie Chart: Status Assessment
            const ctxAssessment = document.getElementById('assessmentChart')?.getContext('2d');
            if(ctxAssessment) {
                new Chart(ctxAssessment, {
                    type: 'pie',
                    data: {
                        labels: ['Sudah Assessment', 'Belum Assessment'],
                        datasets: [{
                            data: [{{ $sudahAssessment ?? 0 }}, {{ $belumAssessment ?? 0 }}],
                            backgroundColor: ['#4F46E5', '#E5E7EB'],
                            hoverOffset: 4
                        }]
                    },
                    options: { responsive: true, maintainAspectRatio: false }
                });
            }

            // Render Bar Chart: Rekomendasi Program Keahlian
            const ctxMajor = document.getElementById('majorChart')?.getContext('2d');
            if(ctxMajor) {
                new Chart(ctxMajor, {
                    type: 'bar',
                    data: {
                        labels: @json($chartLabels ?? []),
                        datasets: [{
                            label: 'Jumlah Siswa',
                            data: @json($chartData ?? []),
                            backgroundColor: '#10B981',
                            borderRadius: 6
                        }]
                    },
                    options: { 
                        responsive: true, 
                        maintainAspectRatio: false,
                        scales: { y: { beginAtZero: true, ticks: { stepSize: 1 } } }
                    }
                });
            }
        });
    </script>
</x-admin-layout>