<x-app-layout>
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-blue-600 text-white px-6 py-4 rounded-t-2xl shadow-md mb-6 flex justify-between items-center">
                <div>
                    <h2 class="text-xl font-bold">Fase 1: Penilaian Minat (Teori RIASEC)</h2>
                    <p class="text-blue-100 text-sm mt-1">Langkah 1 dari 2 — Jawablah sesuai dengan preferensi diri Anda.</p>
                </div>
                <span class="bg-blue-500 text-white px-3 py-1 rounded-lg text-xs font-bold shadow-sm">Progress 50%</span>
            </div>

            <form action="{{ route('assessment.storeFase1') }}" method="POST">
                @csrf
                @php $globalIndex = 1; @endphp

                @foreach($questions as $question)
                <div class="bg-white p-6 rounded-xl shadow-sm mb-6 border-l-4 border-blue-500">
                    <h3 class="font-semibold text-base mb-4 text-gray-800">
                        {{ $globalIndex++ }}. 
                        @if($question->kode_indikator)
                            <span class="text-blue-600 font-bold">[{{ $question->kode_indikator }}]</span>
                        @endif
                        {{ $question->teks_pertanyaan }}
                    </h3>

                    @if($question->gambar)
                        <div class="mb-4">
                            <img src="{{ asset('storage/' . $question->gambar) }}" alt="Gambar Soal" class="max-h-48 rounded-lg border object-contain shadow-sm">
                        </div>
                    @endif

                    <!-- PERUBAHAN TATA LETAK: Menggunakan Grid 5 Kolom ke Samping -->
                  <!-- Container Flex: Akan membagi rata ruang ke samping (horizontal) pada layar PC/Tablet -->
<div class="flex flex-col md:flex-row gap-3 w-full mt-4">
    @foreach([1=>'Sangat Tidak Setuju', 2=>'Tidak Setuju', 3=>'Netral', 4=>'Setuju', 5=>'Sangat Setuju'] as $nilai => $label)
        <!-- flex-1 memastikan setiap kotak memakan porsi lebar yang sama rata di dalam 1 baris -->
        <label class="flex items-center flex-1 p-3 border border-gray-200 rounded-lg hover:bg-blue-50 cursor-pointer transition-all shadow-sm bg-white">
            
            <!-- Tombol Radio di sebelah Kiri -->
            <input type="radio" name="answers[{{ $question->id }}]" value="{{ $nilai }}" required class="text-blue-600 focus:ring-blue-500 w-5 h-5 border-gray-300 shrink-0 cursor-pointer">
            
            <!-- Teks Opsi di sebelah Kanan -->
            <span class="ml-2 text-sm text-gray-700 font-medium leading-tight">{{ $label }}</span>
            
        </label>
    @endforeach
</div>
                </div>
                @endforeach

                <div class="flex justify-end mb-8">
                    <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 font-semibold shadow-md transition-all">
                        Lanjut ke Tes Bakat (Fase 2) &rarr;
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>