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
                    <div id="current-question-card" class="question-card bg-white p-6 rounded-xl shadow-sm border-l-4 border-amber-500 mb-6" data-duration="{{ $duration }}">
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
                        <button id="next-answer" name="action" value="next" type="submit" class="{{ $hasInitialText ? 'hidden' : '' }} px-8 py-3 bg-amber-600 text-white rounded-xl hover:bg-amber-700 font-semibold shadow-md transition-all">
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

    @if($currentQuestion && filled($currentQuestion->teks_awal))
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const card = document.getElementById('current-question-card');
                const timerEl = document.getElementById('question-timer');
                const textEl = document.getElementById('question-text');
                const optionsEl = document.getElementById('answer-options');
                const submitEl = document.getElementById('next-answer');
                let remaining = Number(card.dataset.duration) || 12;

                const updateTimer = function () {
                    timerEl.textContent = String(Math.floor(remaining / 60)).padStart(2, '0') + ':' + String(remaining % 60).padStart(2, '0');
                };

                updateTimer();
                const interval = setInterval(function () {
                    remaining--;
                    updateTimer();

                    if (remaining <= 0) {
                        clearInterval(interval);
                        textEl.textContent = @json(($currentQuestion->kode_indikator ? '[' . $currentQuestion->kode_indikator . '] ' : '') . $currentQuestion->teks_pertanyaan);
                        optionsEl.classList.remove('hidden');
                        submitEl.classList.remove('hidden');
                    }
                }, 1000);
            });
        </script>
    @endif
</x-app-layout>
