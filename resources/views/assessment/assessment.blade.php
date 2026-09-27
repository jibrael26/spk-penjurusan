<x-app-layout>
    <div class="py-12 bg-gray-100">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Blok untuk menampilkan pesan error validasi (jika siswa memaksa submit saat form kosong) -->
            @if ($errors->any())
                <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6 rounded-md shadow-sm">
                    <p class="font-bold">Peringatan</p>
                    <ul class="list-disc ml-5 text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('assessment.store') }}" method="POST">
                @csrf
                
                @foreach($questions as $index => $question)
                <div class="bg-white p-6 rounded-lg shadow-md mb-6">
                    <!-- Pastikan kolom di database benar bernama 'teks_pertanyaan' (atau ganti jadi 'question_text' jika berbeda) -->
                    <h3 class="font-semibold text-lg mb-4">{{ $index + 1 }}. {{ $question->teks_pertanyaan ?? $question->question_text }}</h3>

                    <!-- FASE 1: Skala Likert -->
                    @if($question->fase === 1)
                        <div class="flex flex-col sm:flex-row sm:space-x-4 space-y-2 sm:space-y-0">
                            @foreach([1=>'Sangat Tidak Setuju', 2=>'Tidak Setuju', 3=>'Netral', 4=>'Setuju', 5=>'Sangat Setuju'] as $nilai => $label)
                                <label class="flex items-center space-x-2 cursor-pointer p-2 border rounded hover:bg-blue-50 transition-colors">
                                    <!-- Menggunakan name="answers[]" agar sesuai dengan $request->input('answers') di Controller -->
                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $nilai }}" required class="text-blue-600 focus:ring-blue-500">
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
                                    <label class="flex items-center space-x-3 cursor-pointer p-3 border rounded-lg hover:bg-blue-50 transition-colors">
                                        <!-- Menggunakan name="answers[]" agar sesuai dengan $request->input('answers') di Controller -->
                                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $nilai }}" required class="text-blue-600 focus:ring-blue-500">
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

                <div class="flex justify-end mb-8">
                    <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-semibold shadow-md transition-all">
                        Simpan Jawaban
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>