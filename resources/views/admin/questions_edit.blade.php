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
        
        <form action="{{ route('admin.questions.update', $question->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-5">
                <label class="block text-sm font-semibold text-gray-700 mb-2">Teks Pertanyaan</label>
                <textarea name="teks_pertanyaan" rows="4" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" required placeholder="Tuliskan pertanyaan...">{{ old('teks_pertanyaan', $question->teks_pertanyaan) }}</textarea>
                @error('teks_pertanyaan')
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
                    <select name="fase" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                        <option value="1" {{ (old('fase', $question->fase) == 1) ? 'selected' : '' }}>Fase 1 (Angket Likert)</option>
                        <option value="2" {{ (old('fase', $question->fase) == 2) ? 'selected' : '' }}>Fase 2 (Pilihan Ganda A-E)</option>
                    </select>
                </div>
            </div>

            <!-- BAGIAN BARU: EDIT OPSI JAWABAN -->
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