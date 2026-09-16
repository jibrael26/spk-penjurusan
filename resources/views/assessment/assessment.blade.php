<x-app-layout>
    <div class="py-12 bg-gray-100">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('assessment.store') }}" method="POST">
    @csrf
    
    @foreach($questions as $index => $question)
    <div class="bg-white p-6 rounded-lg shadow-md mb-6">
        <h3 class="font-semibold text-lg mb-4">{{ $index + 1 }}. {{ $question->teks_pertanyaan }}</h3>

        <!-- FASE 1: Skala Likert -->
        @if($question->fase === 1)
            <div class="flex flex-col sm:flex-row sm:space-x-4 space-y-2 sm:space-y-0">
                @foreach([1=>'Sangat Tidak Setuju', 2=>'Tidak Setuju', 3=>'Netral', 4=>'Setuju', 5=>'Sangat Setuju'] as $nilai => $label)
                    <label class="flex items-center space-x-2 cursor-pointer p-2 border rounded hover:bg-blue-50">
                        <input type="radio" name="jawaban[{{ $question->id }}]" value="{{ $nilai }}" required class="text-blue-600">
                        <span class="text-gray-700 text-sm">{{ $label }}</span>
                    </label>
                @endforeach
            </div>

        <!-- FASE 2: Pilihan Ganda -->
        @elseif($question->fase === 2)
            @php
                $huruf = [1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'E'];
            @endphp
            
            <div class="flex flex-col space-y-3">
                @if($question->opsi_jawaban)
                    @foreach($question->opsi_jawaban as $nilai => $kontenOpsi)
                        <label class="flex items-center space-x-3 cursor-pointer p-3 border rounded-lg hover:bg-blue-50">
                            <input type="radio" name="jawaban[{{ $question->id }}]" value="{{ $nilai }}" required class="text-blue-600">
                            <span class="font-bold text-gray-800">{{ $huruf[$nilai] }}.</span>
                            
                            <!-- Render Gambar Jika Tipe Opsi = Image -->
                            @if($question->tipe_opsi === 'image')
                                <img src="{{ asset('storage/opsi/' . $kontenOpsi) }}" alt="Opsi {{ $huruf[$nilai] }}" class="w-24 h-24 object-cover rounded shadow-sm">
                            @else
                                <span class="text-gray-700">{{ $kontenOpsi }}</span>
                            @endif
                        </label>
                    @endforeach
                @endif
            </div>
        @endif
    </div>
    @endforeach

    <button type="submit" class="px-6 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 font-semibold">Simpan Jawaban</button>
</form>
        </div>
    </div>
</x-app-layout>
