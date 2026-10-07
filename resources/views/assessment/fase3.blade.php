{{-- resources/views/assessment/fase3.blade.php --}}
<x-app-layout>
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Header dengan Timer Terintegrasi -->
            <div class="bg-indigo-600 text-white px-6 py-4 rounded-t-2xl shadow-md mb-6 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold">Fase 3: Tes Bakat Lanjutan (Berbatas Waktu)</h2>
                    <p class="text-indigo-100 text-sm mt-1">Pilih jawaban yang paling tepat. Waktu berjalan otomatis.</p>
                </div>
                <!-- Kotak Indikator Timer -->
                <div id="timer-display" class="bg-indigo-500 text-white px-4 py-2 rounded-xl text-sm font-bold shadow-sm flex items-center space-x-2 transition-colors">
                    <span>Sisa Waktu:</span>
                    <span id="time" class="font-mono text-base tracking-wider">00:00</span>
                </div>
            </div>

            <form id="assessment-form" action="{{ route('assessment.storeFase3') }}" method="POST">
                @csrf
                @php 
                    $globalIndex = 1; 
                    $hurufOpsi = [1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'E']; 
                @endphp

                @foreach($questions as $question)
                <div class="bg-white p-6 rounded-xl shadow-sm mb-6 border-l-4 border-indigo-500">
                    <h3 class="font-semibold text-base mb-3 text-gray-800 whitespace-pre-line">
                        {{ $globalIndex++ }}. 
                        @if($question->kode_indikator)
                            <span class="text-indigo-600 font-bold">[{{ $question->kode_indikator }}]</span>
                        @endif
                        {{ $question->pertanyaan ?? $question->teks_pertanyaan }}
                    </h3>

                    @if(!empty($question->gambar))
                        <div class="mb-4">
                            <img src="{{ asset('storage/' . $question->gambar) }}" alt="Gambar Soal" class="max-h-56 rounded-lg border object-contain shadow-sm">
                        </div>
                    @endif

                    <div class="flex flex-col space-y-2">
                        @if(!empty($question->opsi_jawaban) && is_array($question->opsi_jawaban))
                            @foreach($question->opsi_jawaban as $key => $kontenOpsi)
                                <label class="flex items-center space-x-3 p-3 border rounded-lg hover:bg-indigo-50 cursor-pointer transition-colors">
                                    <input type="radio" name="jawaban[{{ $question->id }}]" value="{{ $key }}" required class="text-indigo-600 focus:ring-indigo-500">
                                    <span class="font-bold text-gray-800 w-6">{{ $hurufOpsi[$key] ?? $key }}.</span>
                                    <span class="text-gray-700 text-sm">{{ $kontenOpsi }}</span>
                                </label>
                            @endforeach
                        @endif
                    </div>
                </div>
                @endforeach

                <div class="flex justify-end items-center mb-8">
                    <button type="submit" id="submit-btn" class="px-8 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-semibold shadow-md transition-all">
                        Simpan & Selesai &rarr;
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Script Javascript untuk Countdown Timer & Auto-Submit -->
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            let timeLimit = {{ $timeLimitInSeconds ?? 360 }}; 
            let display = document.querySelector('#time');
            let form = document.querySelector('#assessment-form');
            let timerDisplayBox = document.querySelector('#timer-display');
            let submitBtn = document.querySelector('#submit-btn');

            function startTimer(duration, display) {
                let timer = duration, minutes, seconds;
                
                let countdown = setInterval(function () {
                    minutes = parseInt(timer / 60, 10);
                    seconds = parseInt(timer % 60, 10);

                    minutes = minutes < 10 ? "0" + minutes : minutes;
                    seconds = seconds < 10 ? "0" + seconds : seconds;

                    display.textContent = minutes + ":" + seconds;

                    if (timer === 59) {
                        timerDisplayBox.classList.remove('bg-indigo-500');
                        timerDisplayBox.classList.add('bg-red-600', 'animate-pulse');
                    }

                    if (--timer < 0) {
                        clearInterval(countdown);
                        timerDisplayBox.textContent = "Waktu Habis!";
                        
                        submitBtn.disabled = true;
                        submitBtn.innerText = "Memproses Hasil...";
                        
                        const radios = form.querySelectorAll('input[type="radio"]');
                        radios.forEach(radio => radio.removeAttribute('required'));

                        form.submit();
                    }
                }, 1000);
            }

            startTimer(timeLimit, display);
        });
    </script>
</x-app-layout>