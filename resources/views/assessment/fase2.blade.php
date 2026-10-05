<x-app-layout>
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-indigo-600 text-white px-6 py-4 rounded-t-2xl shadow-md mb-6 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold">Fase 2: Tes Bakat / Kognitif (Teori CHC)</h2>
                    <p class="text-indigo-100 text-sm mt-1">Langkah 2 dari 2 — Pilih jawaban yang paling tepat.</p>
                </div>
                <span class="bg-indigo-500 text-white px-3 py-1 rounded-lg text-xs font-bold shadow-sm">Progress 100%</span>
            </div>

            <form action="{{ route('assessment.storeFase2') }}" method="POST">
                @csrf
                @php $globalIndex = 1; $hurufOpsi = [1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'E']; @endphp

                @foreach($questions as $question)
                <div class="bg-white p-6 rounded-xl shadow-sm mb-6 border-l-4 border-indigo-500">
                    <h3 class="font-semibold text-base mb-3 text-gray-800 whitespace-pre-line">
                        {{ $globalIndex++ }}. 
                        @if($question->kode_indikator)
                            <span class="text-indigo-600 font-bold">[{{ $question->kode_indikator }}]</span>
                        @endif
                        {{ $question->teks_pertanyaan }}
                    </h3>

                    @if($question->gambar)
                        <div class="mb-4">
                            <img src="{{ asset('storage/' . $question->gambar) }}" alt="Gambar Soal" class="max-h-56 rounded-lg border object-contain shadow-sm">
                        </div>
                    @endif

                    <div class="flex flex-col space-y-2">
                        @if(!empty($question->opsi_jawaban) && is_array($question->opsi_jawaban))
                            @foreach($question->opsi_jawaban as $key => $kontenOpsi)
                                <label class="flex items-center space-x-3 p-3 border rounded-lg hover:bg-indigo-50 cursor-pointer transition-colors">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $key }}" required class="text-indigo-600">
                                    <span class="font-bold text-gray-800 w-6">{{ $hurufOpsi[$key] ?? $key }}.</span>
                                    <span class="text-gray-700 text-sm">{{ $kontenOpsi }}</span>
                                </label>
                            @endforeach
                        @endif
                    </div>
                </div>
                @endforeach

                <div class="flex justify-between items-center mb-8">
                    <a href="{{ route('assessment.fase1') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl hover:bg-gray-300 font-semibold transition-all">
                        &larr; Kembali ke Fase 1
                    </a>
                    <button type="submit" class="px-8 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 font-semibold shadow-md transition-all">
                        Simpan Jawaban & Proses Penjurusan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>