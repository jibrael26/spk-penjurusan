<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-8 sm:py-12">
        <div class="mx-auto max-w-6xl px-4 sm:px-6 lg:px-8">
            @if (session('success'))
                <div class="mb-6 flex items-start gap-3 rounded-2xl border border-emerald-200 bg-emerald-50 px-5 py-4 text-emerald-800 shadow-sm" role="alert">
                    <svg class="mt-0.5 h-5 w-5 shrink-0 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7" />
                    </svg>
                    <p class="text-sm font-medium">{{ session('success') }}</p>
                </div>
            @endif

            @if(isset($latestScore) && $latestScore)
                @php
                    $riasecDimensions = [
                        'r' => ['label' => 'Realistic', 'short' => 'R'],
                        'i' => ['label' => 'Investigative', 'short' => 'I'],
                        'a' => ['label' => 'Artistic', 'short' => 'A'],
                        's' => ['label' => 'Social', 'short' => 'S'],
                        'e' => ['label' => 'Enterprising', 'short' => 'E'],
                        'c' => ['label' => 'Conventional', 'short' => 'C'],
                    ];
                    $chcIndicators = [
                        'gf' => ['label' => 'Fluid Reasoning', 'short' => 'Gf'],
                        'gc' => ['label' => 'Comprehension-Knowledge', 'short' => 'Gc'],
                        'gv' => ['label' => 'Visual Processing', 'short' => 'Gv'],
                        'gq' => ['label' => 'Quantitative Knowledge', 'short' => 'Gq'],
                        'gwm' => ['label' => 'Short-Term Working Memory', 'short' => 'Gwm'],
                        'gs' => ['label' => 'Processing Speed', 'short' => 'Gs'],
                    ];
                @endphp

                <div class="mb-8 overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-700 via-blue-700 to-blue-600 p-6 text-white shadow-xl sm:p-8">
                    <div class="flex flex-col gap-6 sm:flex-row sm:items-center sm:justify-between">
                        <div>
                            <p class="mb-2 inline-flex items-center gap-2 rounded-full bg-white/15 px-3 py-1 text-xs font-semibold uppercase tracking-wide text-blue-50">
                                <span class="h-2 w-2 rounded-full bg-emerald-300"></span>
                                Asesmen selesai
                            </p>
                            <h1 class="text-2xl font-bold tracking-tight sm:text-3xl">Hasil Asesmen Penjurusan</h1>
                            <p class="mt-2 max-w-2xl text-sm leading-relaxed text-blue-100">
                                Hasil ini dirangkum dari minat RIASEC dan kemampuan bakat dasar Anda sebagai bahan pertimbangan memilih jurusan.
                            </p>
                        </div>
                        <div class="rounded-2xl bg-white/10 px-5 py-4 text-left ring-1 ring-white/20 sm:min-w-64">
                            <p class="text-xs font-semibold uppercase tracking-wide text-blue-100">Rekomendasi jurusan</p>
                            <p class="mt-1 text-lg font-bold leading-snug">{{ $latestScore->recommended_cluster }}</p>
                        </div>
                    </div>
                </div>

                <div class="grid gap-6 lg:grid-cols-2">
                    <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                        <div class="mb-5 flex items-start gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-blue-100 text-blue-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19V6l12-3v13M9 19c0 1.105-1.567 2-3.5 2S2 20.105 2 19s1.567-2 3.5-2 3.5.895 3.5 2zm12-3c0 1.105-1.567 2-3.5 2s-3.5-.895-3.5-2 1.567-2 3.5-2 3.5.895 3.5 2zM9 10l12-3" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-slate-900">Profil Minat RIASEC</h2>
                                <p class="mt-1 text-sm text-slate-500">Semakin tinggi persentase, semakin kuat kecenderungan minat Anda.</p>
                            </div>
                        </div>

                        @if($latestScore->riasec_scores)
                            <div class="space-y-4">
                                @foreach($riasecDimensions as $dimensi => $item)
                                    @php $nilaiRiasec = (float) ($latestScore->riasec_scores[$dimensi] ?? 0); @endphp
                                    <div>
                                        <div class="mb-1.5 flex items-center justify-between gap-3 text-sm">
                                            <span class="font-semibold text-slate-700">{{ $item['label'] }} <span class="font-normal text-slate-400">({{ $item['short'] }})</span></span>
                                            <span class="font-bold text-blue-700">{{ $nilaiRiasec }}%</span>
                                        </div>
                                        <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">
                                            <div class="h-full rounded-full bg-gradient-to-r from-blue-500 to-indigo-600 transition-all" style="width: {{ min(100, max(0, $nilaiRiasec)) }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="rounded-xl bg-slate-50 p-4 text-sm text-slate-500">Rincian minat belum tersedia. Silakan ulangi asesmen.</p>
                        @endif
                    </section>

                    <section class="rounded-3xl border border-slate-200 bg-white p-5 shadow-sm sm:p-6">
                        <div class="mb-5 flex items-start gap-3">
                            <div class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-indigo-100 text-indigo-700">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.75 3.75h4.5m-7.5 3h10.5m-12 3h13.5m-12 3h10.5m-7.5 3h4.5M6 21h12a2 2 0 002-2V5a2 2 0 00-2-2H6a2 2 0 00-2 2v14a2 2 0 002 2z" />
                                </svg>
                            </div>
                            <div>
                                <h2 class="text-lg font-bold text-slate-900">Profil Bakat Dasar</h2>
                                <p class="mt-1 text-sm text-slate-500">Persentase menunjukkan jumlah jawaban benar pada setiap kemampuan.</p>
                            </div>
                        </div>

                        @if($latestScore->chc_scores)
                            <div class="space-y-4">
                                @foreach($chcIndicators as $indikator => $item)
                                    @php $nilaiChc = (float) ($latestScore->chc_scores[$indikator] ?? 0); @endphp
                                    <div>
                                        <div class="mb-1.5 flex items-center justify-between gap-3 text-sm">
                                            <span class="font-semibold text-slate-700">{{ $item['label'] }} <span class="font-normal text-slate-400">({{ $item['short'] }})</span></span>
                                            <span class="font-bold text-indigo-700">{{ $nilaiChc }}%</span>
                                        </div>
                                        <div class="h-2.5 overflow-hidden rounded-full bg-slate-100">
                                            <div class="h-full rounded-full bg-gradient-to-r from-indigo-500 to-violet-600 transition-all" style="width: {{ min(100, max(0, $nilaiChc)) }}%"></div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <p class="rounded-xl bg-slate-50 p-4 text-sm text-slate-500">Rincian bakat dasar belum tersedia. Silakan ulangi asesmen.</p>
                        @endif
                    </section>
                </div>

                @if($riasecInsight)
                    <section class="mt-6 rounded-3xl border border-indigo-100 bg-indigo-50 p-5 shadow-sm sm:p-6">
                        <div class="flex flex-col gap-4 sm:flex-row sm:items-start sm:justify-between">
                            <div>
                                <p class="text-xs font-bold uppercase tracking-wider text-indigo-500">Insight hasil asesmen</p>
                                <h2 class="mt-1 text-lg font-bold text-indigo-950">Karakteristik Minat Utama</h2>
                            </div>
                            <span class="inline-flex w-fit rounded-full bg-white px-4 py-2 text-sm font-bold text-indigo-700 shadow-sm ring-1 ring-indigo-100">
                                {{ $riasecInsight['label'] }} {{ $riasecInsight['score'] }}%
                            </span>
                        </div>
                        <p class="mt-4 max-w-4xl text-sm leading-relaxed text-indigo-900/80">{{ $riasecInsight['description'] }}</p>
                    </section>
                @endif

                <div class="mt-8 flex flex-col-reverse gap-3 sm:flex-row sm:justify-end">
                    <a href="{{ route('assessment.fase1') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-5 py-3 text-sm font-semibold text-slate-700 shadow-sm transition hover:border-blue-300 hover:bg-blue-50 hover:text-blue-700">
                        <svg class="mr-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h5M20 20v-5h-5M5.05 9A7.002 7.002 0 0117.9 6.1L20 9M4 15l2.1 2.9A7.002 7.002 0 0018.95 15" />
                        </svg>
                        Ulangi asesmen
                    </a>
                    <a href="{{ url('dashboard') }}" class="inline-flex items-center justify-center rounded-xl bg-slate-900 px-5 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-slate-700">
                        Kembali ke beranda
                        <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 12h15" />
                        </svg>
                    </a>
                </div>
            @else
                <div class="mx-auto max-w-2xl rounded-3xl border border-slate-200 bg-white px-6 py-12 text-center shadow-sm sm:px-12">
                    <div class="mx-auto flex h-16 w-16 items-center justify-center rounded-2xl bg-blue-100 text-blue-700">
                        <svg class="h-8 w-8" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h6.586a1 1 0 01.707.293l4.414 4.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                    </div>
                    <h1 class="mt-5 text-2xl font-bold text-slate-900">Mulai Kenali Potensi Anda</h1>
                    <p class="mx-auto mt-3 max-w-lg text-sm leading-relaxed text-slate-500">
                        Ikuti asesmen penjurusan untuk mendapatkan rekomendasi jurusan berdasarkan minat dan bakat Anda.
                    </p>
                    <div class="mt-7 flex flex-col justify-center gap-3 sm:flex-row">
                        <a href="{{ route('assessment.create') }}" class="inline-flex items-center justify-center rounded-xl bg-blue-600 px-6 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700">
                            Mulai asesmen
                            <svg class="ml-2 h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 5l7 7-7 7M5 12h15" />
                            </svg>
                        </a>
                        <a href="{{ url('/') }}" class="inline-flex items-center justify-center rounded-xl border border-slate-300 bg-white px-6 py-3 text-sm font-semibold text-slate-700 transition hover:bg-slate-50">
                            Kembali ke beranda
                        </a>
                    </div>
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
