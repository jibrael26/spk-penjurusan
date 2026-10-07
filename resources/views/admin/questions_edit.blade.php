<x-admin-layout>
    <div class="mb-6">
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.pertanyaan') }}" class="text-gray-400 hover:text-blue-600 transition bg-white p-2 rounded-full shadow-sm border border-gray-100">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
            </a>
            <div>
                <h1 class="text-2xl font-bold text-gray-900">Edit Pertanyaan</h1>
                <p class="mt-1 text-sm text-gray-500">Perbarui detail pertanyaan dan opsi jawaban untuk bank soal penjurusan.</p>
            </div>
        </div>
    </div>

    <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100 max-w-3xl">
        
        <!-- PENTING: Tambahkan enctype="multipart/form-data" agar form dapat memproses upload file -->
        <form action="{{ route('admin.questions.update', $question->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Teks Pertanyaan</label>
                <textarea name="teks_pertanyaan" rows="4" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" required placeholder="Tuliskan pertanyaan...">{{ old('teks_pertanyaan', $question->teks_pertanyaan) }}</textarea>
                @error('teks_pertanyaan')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- BAGIAN BARU: UPLOAD DAN PREVIEW GAMBAR OPSIONAL -->
            <div class="mb-5 p-5 bg-gray-50 rounded-xl border border-gray-200">
                <label class="block text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                    Gambar Pendukung (Opsional)
                </label>
                
                <!-- Tampilkan preview jika soal sudah memiliki gambar di database -->
                @if($question->gambar)
                    <div class="mb-4">
                        <p class="text-xs text-gray-500 mb-2">Gambar saat ini:</p>
                        <img src="{{ asset('storage/' . $question->gambar) }}" alt="Preview Gambar" class="h-32 w-auto object-cover rounded-lg border border-gray-300 shadow-sm">
                    </div>
                @endif

                <input type="file" name="gambar" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2.5 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100 transition-colors bg-white border border-gray-300 rounded-lg">
                <p class="text-xs text-gray-400 mt-2">Format: JPG, PNG, GIF. Maksimal 2MB. Biarkan kosong jika tidak ingin mengubah gambar.</p>
                @error('gambar')
                    <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kriteria / Jurusan</label>
                    <select name="criteria_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                        <option value="">Pilih Kriteria / Jurusan</option>
                        @isset($criteria)
                            @foreach($criteria as $crit)
                                <option value="{{ $crit->id }}" {{ (old('criteria_id', $question->criteria_id) == $crit->id) ? 'selected' : '' }}>
                                    {{ $crit->kode_kriteria }} - {{ $crit->nama_kriteria }}
                                </option>
                            @endforeach
                        @endisset
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Fase Tes</label>
                    <select name="fase" id="fase_selector" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                        <option value="1" {{ (old('fase', $question->fase) == 1) ? 'selected' : '' }}>Fase 1 (Angket Likert)</option>
                        <option value="2" {{ (old('fase', $question->fase) == 2) ? 'selected' : '' }}>Fase 2 (Pilihan Ganda A-E)</option>
                        <option value="3" {{ (isset($question) && $question->fase == 3) ? 'selected' : '' }}>Fase 3 (Soal Berwaktu)</option>
                    </select>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-5 mb-5">
                <div class="mb-4">
    <label class="block text-sm font-medium text-gray-700 mb-1">Kode Indikator</label>
    
    @php
        // Normalisasi data lama (misal "R1" atau "Gf2" menjadi "r" atau "gf") agar otomatis terpilih saat proses Edit
        $kodeInd = isset($question) ? strtolower(preg_replace('/[0-9]+/', '', $question->kode_indikator)) : strtolower(old('kode_indikator'));
    @endphp

    <select name="kode_indikator" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
        <option value="">-- Kosongkan / Tidak Ada --</option>
        
        <optgroup label="Fase 1: Minat (RIASEC)">
            <option value="R" {{ $kodeInd === 'r' ? 'selected' : '' }}>Realistic (R)</option>
            <option value="I" {{ $kodeInd === 'i' ? 'selected' : '' }}>Investigative (I)</option>
            <option value="A" {{ $kodeInd === 'a' ? 'selected' : '' }}>Artistic (A)</option>
            <option value="S" {{ $kodeInd === 's' ? 'selected' : '' }}>Social (S)</option>
            <option value="E" {{ $kodeInd === 'e' ? 'selected' : '' }}>Enterprising (E)</option>
            <option value="C" {{ $kodeInd === 'c' ? 'selected' : '' }}>Conventional (C)</option>
        </optgroup>
        
        <optgroup label="Fase 2 & 3: Bakat Dasar & Lanjutan">
            <option value="Gf" {{ $kodeInd === 'gf' ? 'selected' : '' }}>Fluid Reasoning (Gf)</option>
            <option value="Gc" {{ $kodeInd === 'gc' ? 'selected' : '' }}>Comprehension-Knowledge (Gc)</option>
            <option value="Gv" {{ $kodeInd === 'gv' ? 'selected' : '' }}>Visual Processing (Gv)</option>
            <option value="Gq" {{ $kodeInd === 'gq' ? 'selected' : '' }}>Quantitative Knowledge (Gq)</option>
            <option value="Gwm" {{ $kodeInd === 'gwm' ? 'selected' : '' }}>Short-Term Working Memory (Gwm)</option>
            <option value="Gs" {{ $kodeInd === 'gs' ? 'selected' : '' }}>Processing Speed (Gs)</option>
        </optgroup>
    </select>
</div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Kunci Jawaban</label>
                    <select name="kunci_jawaban" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        <option value="">-- Tidak Ada Kunci (Untuk Fase 1) --</option>
                        <option value="1" {{ (old('kunci_jawaban', $question->kunci_jawaban) == '1') ? 'selected' : '' }}>A</option>
                        <option value="2" {{ (old('kunci_jawaban', $question->kunci_jawaban) == '2') ? 'selected' : '' }}>B</option>
                        <option value="3" {{ (old('kunci_jawaban', $question->kunci_jawaban) == '3') ? 'selected' : '' }}>C</option>
                        <option value="4" {{ (old('kunci_jawaban', $question->kunci_jawaban) == '4') ? 'selected' : '' }}>D</option>
                    </select>
                    <p class="text-xs text-gray-500 mt-1">*Pilih kunci jawaban khusus untuk soal Fase 2.</p>
                </div>
            </div>

            <!-- BAGIAN EDIT OPSI JAWABAN -->
            @if(is_array($question->opsi_jawaban) || is_object($question->opsi_jawaban))
            <div class="mb-8 p-5 bg-gray-50 rounded-xl border border-gray-200">
                <label class="block text-sm font-semibold text-gray-800 mb-3 flex items-center gap-2">
                    <svg class="w-4 h-4 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path></svg>
                    Opsi Jawaban
                </label>
                
                <div class="space-y-3">
                    @foreach($question->opsi_jawaban as $key => $value)
                        <div class="flex items-center space-x-3">
                            <span class="flex-shrink-0 w-10 h-10 flex items-center justify-center font-bold text-gray-600 bg-white border border-gray-300 rounded shadow-sm">
                                {{ $key }}
                            </span>
                            <input type="text" name="opsi_jawaban[{{ $key }}]" value="{{ old('opsi_jawaban.'.$key, $value) }}" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                        </div>
                    @endforeach
                </div>
            </div>
            @endif

            <div class="flex justify-end items-center space-x-3 pt-4 border-t border-gray-100">
                <a href="{{ route('admin.pertanyaan') }}" class="py-2.5 px-5 bg-white border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition font-medium text-sm shadow-sm">
                    Batal
                </a>
                <button type="submit" class="py-2.5 px-6 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition font-medium text-sm shadow-sm">
                    Simpan Perubahan
                </button>
            </div>
        </form>

    </div>
</x-admin-layout>