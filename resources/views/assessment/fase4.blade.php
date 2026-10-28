<x-app-layout>
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-amber-600 text-white px-6 py-4 rounded-t-2xl shadow-md mb-6">
                <h2 class="text-xl font-bold">Fase 4: Tes Tambahan</h2>
                <p class="text-amber-100 text-sm mt-1">Jawab soal secara berurutan. Soal berikutnya akan muncul di bawah soal sebelumnya.</p>
            </div>

            @php
                $hurufOpsi = [1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'E'];
            @endphp
            <form id="assessment-form" action="{{ route('assessment.storeFase4') }}" method="POST">
                @csrf

                @foreach($answeredQuestions as $answeredQuestion)
                    <div class="question-card bg-white p-6 rounded-xl shadow-sm border-l-4 border-emerald-500 mb-6">
                        <h3 class="font-semibold text-base text-gray-800 whitespace-pre-line">
                            @if($answeredQuestion->kode_indikator)
                                <span class="text-amber-600 font-bold">[{{ $answeredQuestion->kode_indikator }}]</span>
                            @endif
                            {{ $answeredQuestion->teks_pertanyaan }}
                        </h3>
                        <p class="mt-4 rounded-lg bg-emerald-50 px-4 py-3 text-sm text-emerald-800">
                            Jawaban:
                            <strong>{{ $hurufOpsi[$answers[$answeredQuestion->id]] ?? $answers[$answeredQuestion->id] }}</strong>
                            — {{ $answeredQuestion->opsi_jawaban[$answers[$answeredQuestion->id]] ?? '' }}
                        </p>
                    </div>
                @endforeach

                @if($currentQuestion)
                    @php
                        $hasInitialText = filled($currentQuestion->teks_awal);
                        $duration = (int) ($currentQuestion->bobot ?? 12);
                    @endphp
                    @if($showPhase4Warning)
                    <div id="phase4-warning" class="bg-white p-6 rounded-xl shadow-sm border border-amber-300 mb-6">
                        <div class="flex items-start gap-3">
                            <div class="flex h-9 w-9 flex-shrink-0 items-center justify-center rounded-full bg-amber-100 text-amber-700 font-bold">!</div>
                            <div>
                                <h3 class="font-bold text-gray-900">Perhatian sebelum menjawab</h3>
                                <p class="mt-1 text-sm leading-6 text-gray-600">
                                    Setelah dimulai, teks awal akan tampil selama
                                    <strong>{{ $duration }} detik</strong> dan dapat hilang atau berubah menjadi pertanyaan.
                                    Perhatikan teks dengan saksama sebelum menjawab.
                                </p>
                            </div>
                        </div>
                        <button id="start-phase4" type="button" class="mt-5 w-full rounded-xl bg-amber-600 px-6 py-3 font-semibold text-white shadow-md transition hover:bg-amber-700">
                            Saya Mengerti, Mulai Pertanyaan
                        </button>
                    </div>
                    @endif

                    <div id="current-question-card" class="question-card {{ $showPhase4Warning ? 'hidden' : '' }} bg-white p-6 rounded-xl shadow-sm border-l-4 border-amber-500 mb-6" data-duration="{{ $duration }}">
                        <div class="flex items-center justify-between mb-3">
                            <h3 id="question-text" class="font-semibold text-base text-gray-800 whitespace-pre-line">
                                @if($hasInitialText)
                                    {{ $currentQuestion->teks_awal }}
                                @else
                                    @if($currentQuestion->kode_indikator)
                                        <span class="text-amber-600 font-bold">[{{ $currentQuestion->kode_indikator }}]</span>
                                    @endif
                                    {{ $currentQuestion->teks_pertanyaan }}
                                @endif
                            </h3>
                            @if($hasInitialText)
                                <span id="question-timer" class="inline-flex items-center bg-amber-100 text-amber-700 text-xs font-semibold px-3 py-1 rounded-full">
                                    {{ sprintf('%02d:%02d', intdiv($duration, 60), $duration % 60) }}
                                </span>
                            @endif
                        </div>

                        <div id="answer-options" class="{{ $hasInitialText ? 'hidden' : '' }} flex flex-col space-y-2">
                            @foreach($currentQuestion->opsi_jawaban ?? [] as $key => $kontenOpsi)
                                <label class="option-item flex items-center space-x-3 p-3 border rounded-lg hover:bg-amber-50 cursor-pointer transition-colors">
                                    <input type="radio" name="jawaban[{{ $currentQuestion->id }}]" value="{{ $key }}" class="text-amber-600 focus:ring-amber-500" required>
                                    <span class="font-bold text-gray-800 w-6">{{ $hurufOpsi[$key] ?? $key }}.</span>
                                    <span class="text-gray-700 text-sm">{{ $kontenOpsi }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>

                    @error('jawaban')
                        <p class="mb-3 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <div class="flex justify-end mb-8">
                        <button id="next-answer" name="action" value="next" type="submit" class="{{ $showPhase4Warning ? 'hidden' : '' }} px-8 py-3 bg-amber-600 text-white rounded-xl hover:bg-amber-700 font-semibold shadow-md transition-all">
                            Jawab & Tampilkan Berikutnya &rarr;
                        </button>
                    </div>
                @elseif($answeredQuestions->isNotEmpty())
                    <div class="flex justify-end mb-8">
                        <button name="action" value="submit" type="submit" class="px-8 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 font-semibold shadow-md transition-all">
                            Submit Fase 4 &rarr;
                        </button>
                    </div>
                @else
                    <div class="bg-white p-6 rounded-xl shadow-sm border border-gray-200 mb-6">
                        <p class="text-gray-600">Tidak ada pertanyaan Fase 4 yang tersedia.</p>
                    </div>
                    <div class="flex justify-end mb-8">
                        <button name="action" value="submit" type="submit" class="px-8 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 font-semibold shadow-md transition-all">
                            Submit Fase 4 &rarr;
                        </button>
                    </div>
                @endif
            </form>
        </div>
    </div>

    @if($currentQuestion && ($showPhase4Warning || filled($currentQuestion->teks_awal)))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const card = document.getElementById('current-question-card');
                const warningEl = document.getElementById('phase4-warning');
                const startEl = document.getElementById('start-phase4');
                const timerEl = document.getElementById('question-timer');
                const textEl = document.getElementById('question-text');
                const optionsEl = document.getElementById('answer-options');
                const submitEl = document.getElementById('next-answer');
                const hasInitialText = @json(filled($currentQuestion->teks_awal));
                const questionText = @json(($currentQuestion->kode_indikator ? '[' . $currentQuestion->kode_indikator . '] ' : '') . $currentQuestion->teks_pertanyaan);
                let remaining = Number(card.dataset.duration) || 12;

                const updateTimer = function () {
                    if (timerEl) {
                        timerEl.textContent = String(Math.floor(remaining / 60)).padStart(2, '0') + ':' + String(remaining % 60).padStart(2, '0');
                    }
                };

                const revealQuestion = function () {
                    textEl.textContent = questionText;
                    optionsEl.classList.remove('hidden');
                    submitEl.classList.remove('hidden');
                };

                const startQuestion = function () {
                    if (warningEl) warningEl.classList.add('hidden');
                    card.classList.remove('hidden');
                    if (!hasInitialText) {
                        revealQuestion();
                        return;
                    }

                    updateTimer();
                    const interval = setInterval(function () {
                        remaining--;
                        updateTimer();

                        if (remaining <= 0) {
                            clearInterval(interval);
                            revealQuestion();
                        }
                    }, 1000);
                };

                if (startEl) {
                    startEl.addEventListener('click', startQuestion);
                } else {
                    startQuestion();
                }
            });
        </script>
    @endif
</x-app-layout>
