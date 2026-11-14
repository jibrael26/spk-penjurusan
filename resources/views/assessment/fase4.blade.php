<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-5 sm:py-8">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="mb-5 overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-700 via-blue-700 to-cyan-600 p-5 text-white shadow-lg sm:p-7">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <span class="inline-flex rounded-full bg-white/15 px-3 py-1 text-xs font-bold uppercase tracking-wider text-blue-50">Tahap 4 dari 4</span>
                        <h1 class="mt-3 text-2xl font-black tracking-tight sm:text-3xl">Tes Final Program Keahlian</h1>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-blue-100">Jawab pertanyaan secara berurutan untuk menyelesaikan asesmen.</p>
                    </div>
                    <div class="shrink-0 rounded-2xl bg-white/10 px-4 py-3 text-center ring-1 ring-white/20">
                        <p class="text-2xl font-black">{{ $answeredQuestions->count() }}</p><p class="text-xs text-blue-100">sudah dijawab</p>
                    </div>
                </div>
                <div class="mt-6 grid grid-cols-4 gap-2" aria-label="Progress asesmen">
                    @foreach(['Minat', 'Bakat', 'Lanjutan', 'Final'] as $step => $label)
                        <div><div class="mb-1.5 h-1.5 rounded-full bg-white"></div><span class="text-[10px] font-semibold sm:text-xs">{{ $step + 1 }}. {{ $label }}</span></div>
                    @endforeach
                </div>
            </div>

            @php $hurufOpsi = [1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'E']; @endphp
            <form id="assessment-form" action="{{ route('assessment.storeFase4') }}" method="POST">
                @csrf
                @if($answeredQuestions->isNotEmpty())
                    <section class="mb-5 rounded-2xl border border-emerald-200 bg-emerald-50 p-4 sm:p-5">
                        <h2 class="text-sm font-bold text-emerald-900">Jawaban tersimpan</h2>
                        <div class="mt-3 grid gap-2 sm:grid-cols-2">
                            @foreach($answeredQuestions as $answeredQuestion)
                                <div class="rounded-xl border border-emerald-100 bg-white px-3 py-2 text-xs text-emerald-800">
                                    <span class="font-bold">{{ $loop->iteration }}.</span> {{ \Illuminate\Support\Str::limit($answeredQuestion->teks_pertanyaan, 70) }}
                                    <span class="mt-1 block font-semibold">Jawaban: {{ $hurufOpsi[$answers[$answeredQuestion->id]] ?? $answers[$answeredQuestion->id] }}</span>
                                </div>
                            @endforeach
                        </div>
                    </section>
                @endif

                @if($currentQuestion)
                    @php $hasInitialText = filled($currentQuestion->teks_awal); $duration = (int) ($currentQuestion->bobot ?? 12); @endphp
                    @if($showPhase4Warning)
                        <div id="phase4-warning" class="mb-4 rounded-2xl border border-amber-200 bg-amber-50 p-5 sm:p-6">
                            <div class="flex items-start gap-3">
                                <span class="flex h-9 w-9 shrink-0 items-center justify-center rounded-full bg-amber-200 font-black text-amber-800">!</span>
                                <div><h2 class="font-bold text-amber-950">Perhatikan instruksi</h2><p class="mt-1 text-sm leading-6 text-amber-900/80">Teks awal akan tampil selama <strong>{{ $duration }} detik</strong>. Simak baik-baik sebelum pertanyaan muncul.</p></div>
                            </div>
                            <button id="start-phase4" type="button" class="mt-5 w-full rounded-xl bg-amber-600 px-6 py-3.5 text-sm font-bold text-white shadow-md transition hover:bg-amber-700">Saya Mengerti, Mulai</button>
                        </div>
                    @endif
                    <fieldset id="current-question-card" class="{{ $showPhase4Warning ? 'hidden' : '' }} rounded-2xl border border-slate-200 border-l-4 border-l-blue-500 bg-white p-4 shadow-sm sm:p-6" data-duration="{{ $duration }}">
                        <div class="flex items-start justify-between gap-3 rounded-xl border border-blue-100 bg-blue-50/60 px-4 py-3 sm:px-5">
                            <div id="question-text" class="w-full break-words whitespace-pre-line text-sm font-bold leading-6 text-slate-800 sm:text-base">
                                @if($hasInitialText){{ $currentQuestion->teks_awal }}@else{{ $currentQuestion->teks_pertanyaan }}@endif
                            </div>
                            @if($hasInitialText)<span id="question-timer" class="shrink-0 rounded-full bg-amber-100 px-3 py-1 text-xs font-bold text-amber-700">{{ sprintf('%02d:%02d', intdiv($duration, 60), $duration % 60) }}</span>@endif
                        </div>
                        <div id="answer-options" class="{{ $hasInitialText ? 'hidden' : '' }} mt-5 space-y-2">
                            @foreach($currentQuestion->opsi_jawaban ?? [] as $key => $kontenOpsi)
                                <label class="option-item flex min-h-12 cursor-pointer items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 p-3 transition hover:border-blue-400 hover:bg-blue-50 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 has-[:checked]:ring-2 has-[:checked]:ring-blue-100">
                                    <input type="radio" name="jawaban[{{ $currentQuestion->id }}]" value="{{ $key }}" class="h-5 w-5 border-slate-300 text-blue-600 focus:ring-blue-500" required>
                                    <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-white text-xs font-black text-blue-700 ring-1 ring-slate-200">{{ $hurufOpsi[$key] ?? $key }}</span>
                                    <span class="text-sm text-slate-700">{{ $kontenOpsi }}</span>
                                </label>
                            @endforeach
                        </div>
                    </fieldset>
                    @error('jawaban')<p class="mt-3 text-sm font-semibold text-red-600">{{ $message }}</p>@enderror
                    <button id="next-answer" name="action" value="next" type="submit" class="{{ $showPhase4Warning ? 'hidden' : '' }} mt-5 w-full rounded-xl bg-blue-600 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700 sm:w-auto sm:float-right">Simpan & Lanjut <span aria-hidden="true">&rarr;</span></button>
                    <div class="clear-both"></div>
                @else
                    <div class="rounded-2xl border border-slate-200 bg-white p-6 text-center shadow-sm"><p class="text-sm text-slate-600">Tidak ada pertanyaan Fase 4 yang tersedia.</p></div>
                    <button name="action" value="submit" type="submit" class="mt-5 w-full rounded-xl bg-emerald-600 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-emerald-600/20 sm:w-auto sm:float-right">Selesaikan Asesmen <span aria-hidden="true">&rarr;</span></button>
                @endif
            </form>
        </div>
    </div>
    @if($currentQuestion && ($showPhase4Warning || filled($currentQuestion->teks_awal)))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const card = document.getElementById('current-question-card'), warning = document.getElementById('phase4-warning'), start = document.getElementById('start-phase4'), timer = document.getElementById('question-timer'), text = document.getElementById('question-text'), options = document.getElementById('answer-options'), submit = document.getElementById('next-answer');
                const hasInitialText = @json(filled($currentQuestion->teks_awal)), questionText = @json($currentQuestion->teks_pertanyaan);
                let remaining = Number(card.dataset.duration) || 12;
                const update = () => { if (timer) timer.textContent = String(Math.floor(remaining / 60)).padStart(2, '0') + ':' + String(remaining % 60).padStart(2, '0'); };
                const reveal = () => { text.textContent = questionText; options.classList.remove('hidden'); submit.classList.remove('hidden'); };
                const begin = () => { if (warning) warning.classList.add('hidden'); card.classList.remove('hidden'); if (!hasInitialText) return reveal(); update(); const interval = setInterval(() => { remaining--; update(); if (remaining <= 0) { clearInterval(interval); reveal(); } }, 1000); };
                if (start) start.addEventListener('click', begin); else begin();
            });
        </script>
    @endif
</x-app-layout>
