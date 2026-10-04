<x-app-layout>
    <div class="py-12 bg-gray-100 min-h-screen">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Blok Pesan Error Validasi -->
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
                
                @php $globalIndex = 1; @endphp

                <!-- ================= FASE 1: PENILAIAN MINAT (SKALA LIKERT) ================= -->
                <div class="mb-8">
                    <div class="bg-blue-600 text-white px-6 py-4 rounded-lg shadow-md mb-6">
                        <h2 class="text-xl font-bold">Fase 1: Penilaian Minat (Teori RIASEC)</h2>
                        <p class="text-blue-100 text-sm mt-1">Silakan berikan tanggapan yang sesuai dengan diri Anda.</p>
                    </div>

                    @foreach($fase1Questions as $question)
                    <div class="bg-white p-6 rounded-lg shadow-md mb-6 border-l-4 border-blue-500">
                        <h3 class="font-semibold text-lg mb-3 text-gray-800">
                            {{ $globalIndex++ }}. 
                            @if($question->kode_indikator)
                                <span class="text-sm font-bold text-blue-600">[{{ $question->kode_indikator }}]</span> 
                            @endif
                            {!! nl2br(e($question->teks_pertanyaan)) !!}
                        </h3>

                        <!-- Tampilkan Gambar Soal Jika Ada -->
                        @if($question->gambar)
                            <div class="mb-4">
                                <img src="{{ asset('storage/' . $question->gambar) }}" alt="Gambar Soal" class="max-w-full h-auto max-h-72 rounded-lg border border-gray-200 shadow-sm object-contain">
                            </div>
                        @endif

                        <div class="flex flex-col sm:flex-row sm:space-x-4 space-y-2 sm:space-y-0">
                            @foreach([1=>'Sangat Tidak Setuju', 2=>'Tidak Setuju', 3=>'Netral', 4=>'Setuju', 5=>'Sangat Setuju'] as $nilai => $label)
                                <label class="flex items-center space-x-2 cursor-pointer p-2 border rounded hover:bg-blue-50 transition-colors flex-1">
                                    <input type="radio" name="answers[{{ $question->id }}]" value="{{ $nilai }}" required class="text-blue-600 focus:ring-blue-500">
                                    <span class="text-gray-700 text-sm">{{ $label }}</span>
                                </label>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                </div>


                <!-- ================= FASE 2: TES BAKAT (PILIHAN GANDA) ================= -->
                <div class="mb-8">
                    <div class="bg-indigo-600 text-white px-6 py-4 rounded-lg shadow-md mb-6">
                        <h2 class="text-xl font-bold">Fase 2: Tes Bakat / Kemampuan Kognitif (Teori CHC)</h2>
                        <p class="text-indigo-100 text-sm mt-1">Pilih salah satu jawaban yang paling tepat menurut Anda.</p>
                    </div>

                    @php
                        $hurufOpsi = [1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'E'];
                    @endphp

                    @foreach($fase2Questions as $question)
                    <div class="bg-white p-6 rounded-lg shadow-md mb-6 border-l-4 border-indigo-500">
                        <h3 class="font-semibold text-lg mb-3 text-gray-800">
                            {{ $globalIndex++ }}. 
                            @if($question->kode_indikator)
                                <span class="text-sm font-bold text-indigo-600">[{{ $question->kode_indikator }}]</span> 
                            @endif
                            {!! nl2br(e($question->teks_pertanyaan)) !!}
                        </h3>

                        <!-- Tampilkan Gambar Soal Jika Ada -->
                        @if($question->gambar)
                            <div class="mb-4">
                                <img src="{{ asset('storage/' . $question->gambar) }}" alt="Gambar Soal" class="max-w-full h-auto max-h-72 rounded-lg border border-gray-200 shadow-sm object-contain">
                            </div>
                        @endif

                        <!-- Render Opsi Jawaban Pilihan Ganda Berdasarkan Database -->
                        <div class="flex flex-col space-y-3">
                            @if(!empty($question->opsi_jawaban) && is_array($question->opsi_jawaban))
                                @foreach($question->opsi_jawaban as $key => $kontenOpsi)
                                    <label class="flex items-center space-x-3 cursor-pointer p-3 border rounded-lg hover:bg-indigo-50 transition-colors">
                                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $key }}" required class="text-indigo-600 focus:ring-indigo-500">
                                        
                                        <span class="font-bold text-gray-800">
                                            {{ $hurufOpsi[$key] ?? $key }}.
                                        </span>
                                        
                                        <span class="text-gray-700">{{ $kontenOpsi }}</span>
                                    </label>
                                @endforeach
                            @else
                                <p class="text-sm text-gray-400 italic">Opsi jawaban belum diatur atau kosong.</p>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Tombol Submit -->
                <div class="flex justify-end mb-8">
                    <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 font-semibold shadow-md transition-all">
                        Simpan Jawaban & Proses Penjurusan
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>