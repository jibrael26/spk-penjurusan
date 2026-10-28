<x-admin-layout>
    <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-blue-600">Manajemen Assessment</p>
            <h1 class="mt-1 text-2xl font-bold text-gray-900 sm:text-3xl">Bank Soal</h1>
            <p class="mt-2 max-w-2xl text-sm text-gray-500">Kelola pertanyaan, parameter RIASEC/CHC, opsi jawaban, dan konfigurasi tampilan soal.</p>
        </div>
        <div class="inline-flex w-fit items-center gap-2 rounded-full bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700">
            <span class="h-2 w-2 rounded-full bg-blue-500"></span>
            {{ isset($questions) ? $questions->total() : 0 }} soal tersimpan
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri: Form & AI -->
        <div class="space-y-6">
            <!-- Form Manual -->
            <div class="overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="border-b border-gray-100 bg-gradient-to-r from-slate-900 to-slate-800 px-6 py-5 text-white">
                    <p class="text-xs font-semibold uppercase tracking-wider text-blue-200">Input manual</p>
                    <h3 class="mt-1 text-lg font-bold">Tambah Soal</h3>
                    <p class="mt-1 text-xs text-slate-300">Isi parameter penilaian agar soal masuk ke perhitungan rekomendasi.</p>
                </div>
                <form action="{{ route('admin.questions.store') }}" method="POST" enctype="multipart/form-data" class="space-y-5 p-6">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pertanyaan</label>
                        <textarea name="teks_pertanyaan" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" required placeholder="Tuliskan pertanyaan..."></textarea>
                    </div>

                    <div class="rounded-xl border border-amber-100 bg-amber-50/60 p-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Teks awal Fase 4 (opsional)</label>
                        <textarea name="teks_awal" rows="2" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" placeholder="Contoh: 8-3-5-1-9"></textarea>
                        <p class="text-xs text-gray-500 mt-1">Teks ini tampil terlebih dahulu lalu diganti dengan pertanyaan setelah durasi berakhir.</p>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kelompok Soal (Metadata)</label>
                        <select name="criteria_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                            <option value="">Pilih kelompok soal</option>
                            @isset($criteria)
                                @foreach($criteria as $crit)
                                    <option value="{{ $crit->id }}">{{ $crit->kode_kriteria }} - {{ $crit->nama_kriteria }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fase Tes</label>
                        <select name="fase" id="fase_selector" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                            <option value="1">Fase 1 (Angket Likert - Minat)</option>
                            <option value="2">Fase 2 (Pilihan Ganda A-D - Bakat)</option>
                            <option value="3">Fase 3 (Bakat Lanjutan - Berwaktu)</option>
                            <option value="4">Fase 4 (Temporary Display)</option>
                        </select>
                    </div>

                    <!-- BARU: Input Kode Indikator -->
                    <div>
    <label class="mb-1 block text-sm font-medium text-gray-700">Parameter Penilaian RIASEC / CHC</label>
    
    @php
        // Normalisasi data lama (misal "R1" atau "Gf2" menjadi "r" atau "gf") agar otomatis terpilih saat proses Edit
        $kodeInd = isset($question) ? strtolower(preg_replace('/[0-9]+/', '', $question->kode_indikator)) : strtolower(old('kode_indikator'));
    @endphp

    <select name="kode_indikator" id="indicator_selector" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" required>
        <option value="">-- Pilih parameter sesuai fase --</option>
        
        <optgroup label="Fase 1: Minat (RIASEC)">
            <option value="R" {{ $kodeInd === 'r' ? 'selected' : '' }}>Realistic (R)</option>
            <option value="I" {{ $kodeInd === 'i' ? 'selected' : '' }}>Investigative (I)</option>
            <option value="A" {{ $kodeInd === 'a' ? 'selected' : '' }}>Artistic (A)</option>
            <option value="S" {{ $kodeInd === 's' ? 'selected' : '' }}>Social (S)</option>
            <option value="E" {{ $kodeInd === 'e' ? 'selected' : '' }}>Enterprising (E)</option>
            <option value="C" {{ $kodeInd === 'c' ? 'selected' : '' }}>Conventional (C)</option>
        </optgroup>
        
        <optgroup label="Fase 2–4: Bakat">
            <option value="Gf" {{ $kodeInd === 'gf' ? 'selected' : '' }}>Fluid Reasoning (Gf)</option>
            <option value="Gc" {{ $kodeInd === 'gc' ? 'selected' : '' }}>Comprehension-Knowledge (Gc)</option>
            <option value="Gv" {{ $kodeInd === 'gv' ? 'selected' : '' }}>Visual Processing (Gv)</option>
            <option value="Gq" {{ $kodeInd === 'gq' ? 'selected' : '' }}>Quantitative Knowledge (Gq)</option>
            <option value="Gwm" {{ $kodeInd === 'gwm' ? 'selected' : '' }}>Short-Term Working Memory (Gwm)</option>
            <option value="Gs" {{ $kodeInd === 'gs' ? 'selected' : '' }}>Processing Speed (Gs)</option>
        </optgroup>
    </select>
    <p class="mt-1 text-xs leading-5 text-gray-500">RIASEC untuk Fase 1; CHC untuk Fase 2, 3, dan 4.</p>
</div>

                    <!-- BARU: Input Kunci Jawaban -->
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kunci Jawaban</label>
                        <select name="kunci_jawaban" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                            <option value="">-- Tidak Ada Kunci (Untuk Fase 1) --</option>
                            <option value="1">A</option>
                            <option value="2">B</option>
                            <option value="3">C</option>
                            <option value="4">D</option>
                        </select>
                        <p class="text-xs text-gray-500 mt-1">*Pilih kunci jawaban khusus untuk soal Fase 2.</p>
                    </div>

                    <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                        <label for="bobot" class="block text-sm font-medium text-gray-700">Durasi tampil per soal (detik)</label>
                        <input type="number" name="bobot" id="bobot" min="1" max="3600" value="{{ old('bobot', 12) }}" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <p class="text-xs text-gray-500 mt-1">Digunakan khusus untuk Fase 4. Default: 12 detik.</p>
                    </div>

                    <div class="rounded-xl border border-dashed border-gray-300 bg-gray-50 p-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Upload Gambar Pendukung (Opsional)</label>
        <input type="file" name="gambar" accept="image/*" class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
        <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, GIF. Maksimal ukuran 2MB.</p>
    </div>

                    <button type="submit" class="w-full rounded-xl bg-blue-600 px-4 py-3 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                        Simpan Pertanyaan
                    </button>
                </form>
            </div>

            <!-- Panel Generate AI -->
           
        </div>

        <!-- Kolom Kanan: Tabel Daftar Soal -->
        <div class="lg:col-span-2">
            <div class="flex h-full flex-col overflow-hidden rounded-2xl border border-gray-100 bg-white shadow-sm">
                <div class="flex flex-col gap-3 border-b border-gray-100 bg-slate-50/80 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h3 class="text-base font-semibold text-gray-900">Daftar Pertanyaan Aktif</h3>
                        <p class="text-xs text-gray-500 mt-1">Gunakan filter untuk menemukan soal dengan cepat.</p>
                    </div>
                    <span class="text-xs font-medium bg-blue-100 text-blue-700 px-2.5 py-1 rounded-full whitespace-nowrap">
                        Total: {{ isset($questions) ? $questions->total() : 0 }}
                    </span>
                </div>

                <form method="GET" action="{{ route('admin.pertanyaan') }}" class="p-5 border-b border-gray-100 bg-white">
                    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-3">
                        <div class="xl:col-span-2">
                            <label for="question-search" class="block text-xs font-semibold text-gray-600 mb-1.5">Cari soal</label>
                            <input id="question-search" type="search" name="search" value="{{ request('search') }}"
                                   placeholder="Cari teks pertanyaan atau kode indikator..."
                                   class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                        </div>
                        <div>
                            <label for="question-phase" class="block text-xs font-semibold text-gray-600 mb-1.5">Filter fase</label>
                            <select id="question-phase" name="fase"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="">Semua fase</option>
                                <option value="1" @selected(request('fase') === '1')>Fase 1 - Minat</option>
                                <option value="2" @selected(request('fase') === '2')>Fase 2 - Bakat Dasar</option>
                                <option value="3" @selected(request('fase') === '3')>Fase 3 - Bakat Lanjutan</option>
                                <option value="4" @selected(request('fase') === '4')>Fase 4 - Temporary Display</option>
                            </select>
                        </div>
                        <div>
                            <label for="question-criteria" class="block text-xs font-semibold text-gray-600 mb-1.5">Filter kelompok soal</label>
                            <select id="question-criteria" name="criteria_id"
                                    class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm">
                                <option value="">Semua kriteria</option>
                                @foreach($criteria as $crit)
                                    <option value="{{ $crit->id }}" @selected((string) request('criteria_id') === (string) $crit->id)>
                                        {{ $crit->kode_kriteria }} - {{ $crit->nama_kriteria }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="flex flex-wrap items-center gap-2 mt-4">
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition font-medium text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.35-5.65a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Terapkan Filter
                        </button>
                        @if(request()->hasAny(['search', 'fase', 'criteria_id']))
                            <a href="{{ route('admin.pertanyaan') }}" class="inline-flex items-center px-4 py-2 border border-gray-300 text-gray-600 rounded-lg hover:bg-gray-50 transition font-medium text-sm">
                                Reset
                            </a>
                        @endif
                    </div>
                </form>
                
                <div class="overflow-x-auto flex-grow">
                    <table class="min-w-[760px] w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50/50 border-b border-gray-100 text-gray-500 uppercase tracking-wider">
                            <tr>
                                <th class="w-[46%] px-6 py-4 font-semibold">Pertanyaan & parameter</th>
                                <th class="px-6 py-4 font-semibold">Kelompok</th>
                                <th class="px-6 py-4 text-center font-semibold">Fase</th>
                                <th class="px-6 py-4 text-center font-semibold">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @isset($questions)
                                @forelse ($questions as $q)
                                    <tr class="transition-colors hover:bg-blue-50/30">
                                        <td class="px-6 py-4 text-gray-900">
                                            <div class="mb-2 flex flex-wrap items-center gap-2">
                                                @if($q->kode_indikator)
                                                    <span class="rounded-md bg-blue-50 px-2 py-1 text-[11px] font-bold text-blue-700">{{ $q->kode_indikator }}</span>
                                                @endif
                                                @if($q->fase == 4 && $q->bobot)
                                                    <span class="rounded-md bg-amber-50 px-2 py-1 text-[11px] font-semibold text-amber-700">{{ $q->bobot }} detik</span>
                                                @endif
                                            </div>
                                            <div class="line-clamp-2 font-medium leading-6" title="{{ $q->teks_pertanyaan }}">
                                                {{ $q->teks_pertanyaan }}
                                            </div>
                                            @if($q->teks_awal)
                                                <div class="mt-1 line-clamp-1 text-xs text-gray-400">Teks awal: {{ $q->teks_awal }}</div>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center rounded-full bg-indigo-50 px-2.5 py-1 text-xs font-semibold text-indigo-700">
                                                {{ $q->criteria->kode_kriteria ?? 'Umum' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($q->fase == 1)
                                                <span class="bg-emerald-50 text-emerald-700 py-1 px-2.5 rounded-full text-xs font-medium">Fase 1</span>
                                            @elseif($q->fase == 2)
                                                <span class="bg-amber-50 text-amber-700 py-1 px-2.5 rounded-full text-xs font-medium">Fase 2</span>
                                            @elseif($q->fase == 3)
                                                <span class="px-2 py-1 text-xs font-bold bg-blue-100 text-blue-700 rounded-full">Fase 3</span>
                                            @elseif($q->fase == 4)
                                                <span class="px-2 py-1 text-xs font-bold bg-rose-100 text-rose-700 rounded-full">Fase 4</span>
                                            @endif
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            <div class="flex items-center justify-center space-x-2">
                                                <a href="{{ route('admin.questions.edit', $q->id) }}" class="p-1.5 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition" title="Edit Soal">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                </a>
                                                <form action="{{ route('admin.questions.destroy', $q->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pertanyaan ini?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="p-1.5 bg-red-50 text-red-600 rounded-lg hover:bg-red-100 transition" title="Hapus Soal">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                    </button>
                                                </form>
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="px-6 py-12 text-center text-gray-500">
                                            Belum ada soal di dalam database.
                                        </td>
                                    </tr>
                                @endforelse
                            @endisset
                        </tbody>
                    </table>
                </div>

                @isset($questions)
                    @if($questions->hasPages())
                        <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50">
                            {{ $questions->links() }}
                        </div>
                    @endif
                @endisset
            </div>
        </div>
    </div>
</x-admin-layout>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const phase = document.getElementById('fase_selector');
        const indicator = document.getElementById('indicator_selector');
        if (!phase || !indicator) return;

        const groups = indicator.querySelectorAll('optgroup');
        const syncIndicators = function () {
            const isRiasec = phase.value === '1';
            groups[0].hidden = !isRiasec;
            groups[1].hidden = isRiasec;
            const selected = indicator.options[indicator.selectedIndex];
            if (selected && selected.parentElement.hidden) {
                indicator.value = '';
            }
        };

        phase.addEventListener('change', syncIndicators);
        syncIndicators();
    });
</script>