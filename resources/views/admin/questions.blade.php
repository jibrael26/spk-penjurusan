<x-admin-layout>
    <style>[x-cloak] { display: none !important; }</style>

    <div x-data="questionPage()">
    <div class="mb-8 flex flex-col gap-3 sm:flex-row sm:items-end sm:justify-between">
        <div>
            <p class="text-xs font-bold uppercase tracking-[0.18em] text-blue-600">Manajemen Assessment</p>
            <h1 class="mt-1 text-2xl font-bold text-gray-900 sm:text-3xl">Bank Soal</h1>
            <p class="mt-2 max-w-2xl text-sm text-gray-500">Kelola pertanyaan, parameter RIASEC/CHC, opsi jawaban, dan konfigurasi tampilan soal.</p>
        </div>
        <div class="flex flex-wrap items-center gap-3">
            <div class="inline-flex w-fit items-center gap-2 rounded-full bg-blue-50 px-3 py-2 text-xs font-semibold text-blue-700">
                <span class="h-2 w-2 rounded-full bg-blue-500"></span>
                {{ isset($questions) ? $questions->total() : 0 }} soal tersimpan
            </div>
            <button type="button" @click="showCreateModal = true" class="inline-flex items-center gap-2 rounded-xl bg-blue-600 px-4 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Tambah Soal
            </button>
        </div>
    </div>

        <div x-cloak x-show="showCreateModal" x-transition.opacity @click.self="showCreateModal = false" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-slate-950/60 p-4 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="create-question-title">
            <div class="my-4 flex max-h-[calc(100vh-2rem)] w-full max-w-4xl flex-col overflow-hidden rounded-2xl border border-slate-200 bg-white shadow-2xl">
                <div class="flex shrink-0 items-start justify-between border-b border-slate-200 bg-gradient-to-r from-slate-900 to-slate-800 px-5 py-5 text-white sm:px-7">
                    <div class="flex items-start gap-3">
                        <div class="hidden rounded-xl bg-cyan-400/15 p-2.5 text-cyan-300 sm:block">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 6v12m6-6H6"></path></svg>
                        </div>
                        <div>
                            <p class="text-xs font-semibold uppercase tracking-[0.16em] text-cyan-300">Input manual</p>
                            <h3 id="create-question-title" class="mt-1 text-xl font-bold">Tambah Pertanyaan</h3>
                            <p class="mt-1 text-sm text-slate-300">Lengkapi informasi soal sesuai fase tes.</p>
                        </div>
                    </div>
                    <button type="button" @click="showCreateModal = false" class="rounded-lg p-2 text-slate-300 transition hover:bg-white/10 hover:text-white" aria-label="Tutup modal">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                <form action="{{ route('admin.questions.store') }}" method="POST" enctype="multipart/form-data" class="flex min-h-0 flex-1 flex-col">
                    @csrf
                    <div class="flex-1 space-y-6 overflow-y-auto p-5 sm:p-7">
                        <section>
                            <div class="mb-3 flex items-center gap-2">
                                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-700">1</span>
                                <h4 class="text-sm font-bold text-slate-800">Isi pertanyaan</h4>
                            </div>
                            <label for="teks_pertanyaan" class="mb-1.5 block text-sm font-semibold text-slate-700">Pertanyaan <span class="text-rose-500">*</span></label>
                            <textarea id="teks_pertanyaan" name="teks_pertanyaan" rows="4" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" required placeholder="Tuliskan pertanyaan dengan jelas..."></textarea>
                            <p class="mt-1.5 text-xs text-slate-500">Gunakan kalimat yang singkat, jelas, dan mudah dipahami siswa.</p>
                        </section>

                        <section class="border-t border-slate-100 pt-5">
                            <div class="mb-3 flex items-center gap-2">
                                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-700">2</span>
                                <h4 class="text-sm font-bold text-slate-800">Pengaturan penilaian</h4>
                            </div>
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div>
                                    <label for="fase_selector" class="mb-1.5 block text-sm font-semibold text-slate-700">Fase tes <span class="text-rose-500">*</span></label>
                                    <select name="fase" id="fase_selector" x-model="fase" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                        <option value="1">Fase 1 - Minat (RIASEC)</option>
                                        <option value="2">Fase 2 - Bakat dasar</option>
                                        <option value="3">Fase 3 - Bakat lanjutan</option>
                                        <option value="4">Fase 4 - Temporary display</option>
                                    </select>
                                    <p class="mt-1.5 text-xs text-slate-500">Pilih fase sesuai jenis pertanyaan.</p>
                                </div>
                                <div>
                                    <label for="indicator_selector" class="mb-1.5 block text-sm font-semibold text-slate-700">Parameter <span class="text-rose-500">*</span></label>
                                    @php
                                        $kodeInd = isset($question) ? strtolower(preg_replace('/[0-9]+/', '', $question->kode_indikator)) : strtolower(old('kode_indikator'));
                                    @endphp
                                    <select name="kode_indikator" id="indicator_selector" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                                        <option value="">Pilih parameter</option>
                                        <optgroup label="Minat (RIASEC)">
                                            <option value="R" {{ $kodeInd === 'r' ? 'selected' : '' }}>Realistic (R)</option>
                                            <option value="I" {{ $kodeInd === 'i' ? 'selected' : '' }}>Investigative (I)</option>
                                            <option value="A" {{ $kodeInd === 'a' ? 'selected' : '' }}>Artistic (A)</option>
                                            <option value="S" {{ $kodeInd === 's' ? 'selected' : '' }}>Social (S)</option>
                                            <option value="E" {{ $kodeInd === 'e' ? 'selected' : '' }}>Enterprising (E)</option>
                                            <option value="C" {{ $kodeInd === 'c' ? 'selected' : '' }}>Conventional (C)</option>
                                        </optgroup>
                                        <optgroup label="Bakat (CHC)">
                                            <option value="Gf" {{ $kodeInd === 'gf' ? 'selected' : '' }}>Fluid Reasoning (Gf)</option>
                                            <option value="Gc" {{ $kodeInd === 'gc' ? 'selected' : '' }}>Comprehension-Knowledge (Gc)</option>
                                            <option value="Gv" {{ $kodeInd === 'gv' ? 'selected' : '' }}>Visual Processing (Gv)</option>
                                            <option value="Gq" {{ $kodeInd === 'gq' ? 'selected' : '' }}>Quantitative Knowledge (Gq)</option>
                                            <option value="Gwm" {{ $kodeInd === 'gwm' ? 'selected' : '' }}>Short-Term Working Memory (Gwm)</option>
                                            <option value="Gs" {{ $kodeInd === 'gs' ? 'selected' : '' }}>Processing Speed (Gs)</option>
                                        </optgroup>
                                    </select>
                                    <p class="mt-1.5 text-xs text-slate-500">RIASEC untuk fase 1, CHC untuk fase 2-4.</p>
                                </div>
                            </div>
                            <div x-show="fase !== '1'" x-cloak class="mt-4">
                                <label for="kunci_jawaban" class="mb-1.5 block text-sm font-semibold text-slate-700">Kunci jawaban</label>
                                <select id="kunci_jawaban" name="kunci_jawaban" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <option value="">Pilih kunci jawaban</option>
                                    <option value="1">A</option>
                                    <option value="2">B</option>
                                    <option value="3">C</option>
                                    <option value="4">D</option>
                                </select>
                                <p class="mt-1.5 text-xs text-slate-500">Digunakan untuk soal dengan jawaban benar pada fase bakat.</p>
                            </div>
                            <div x-show="fase !== '1'" x-cloak class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4">
                                <div class="mb-3 flex items-center justify-between gap-3">
                                    <div>
                                        <label class="block text-sm font-semibold text-slate-700">Opsi jawaban <span class="font-normal text-slate-400">(opsional)</span></label>
                                        <p class="mt-1 text-xs text-slate-500">Isi teks pilihan jawaban untuk fase 2-4.</p>
                                    </div>
                                    <span class="hidden text-xs font-medium text-slate-400 sm:inline">Maksimal 4 opsi</span>
                                </div>
                                <div class="space-y-2.5">
                                    @foreach([1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D'] as $optionNumber => $optionLabel)
                                        <div class="flex items-center gap-2">
                                            <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border border-slate-200 bg-white text-sm font-bold text-slate-600">{{ $optionLabel }}</span>
                                            <input type="text" name="opsi_jawaban[{{ $optionNumber }}]" class="w-full rounded-lg border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Tulis opsi {{ $optionLabel }}...">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </section>

                        <section class="border-t border-slate-100 pt-5">
                            <div class="mb-3 flex items-center gap-2">
                                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-blue-100 text-xs font-bold text-blue-700">3</span>
                                <h4 class="text-sm font-bold text-slate-800">Tampilan soal <span class="font-normal text-slate-400">(opsional)</span></h4>
                            </div>
                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                                <div x-show="fase === '4'" x-cloak>
                                    <label for="teks_awal" class="mb-1.5 block text-sm font-semibold text-slate-700">Teks awal Fase 4</label>
                                    <textarea id="teks_awal" name="teks_awal" rows="2" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500" placeholder="Contoh: 8-3-5-1-9"></textarea>
                                    <p class="mt-1.5 text-xs text-slate-500">Ditampilkan sebelum pertanyaan utama.</p>
                                </div>
                                <div x-show="fase === '4'" x-cloak>
                                    <label for="bobot" class="mb-1.5 block text-sm font-semibold text-slate-700">Durasi tampil (detik)</label>
                                    <input type="number" name="bobot" id="bobot" min="1" max="3600" value="{{ old('bobot', 12) }}" class="w-full rounded-xl border-slate-300 text-sm shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                    <p class="mt-1.5 text-xs text-slate-500">Berapa lama teks awal ditampilkan.</p>
                                </div>
                            </div>
                            <div class="mt-4 rounded-xl border border-dashed border-slate-300 bg-slate-50 p-4">
                                <label for="gambar" class="mb-1.5 block text-sm font-semibold text-slate-700">Gambar pendukung</label>
                                <input id="gambar" type="file" name="gambar" accept="image/*" class="w-full text-sm text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-blue-50 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-blue-700 hover:file:bg-blue-100">
                                <p class="mt-1.5 text-xs text-slate-500">Format JPG, PNG, atau GIF. Maksimal 3 MB.</p>
                            </div>
                        </section>
                    </div>

                    <div class="flex shrink-0 flex-col-reverse gap-3 border-t border-slate-200 bg-slate-50 px-5 py-4 sm:flex-row sm:justify-end sm:px-7">
                        <button type="button" @click="showCreateModal = false" class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 transition hover:bg-slate-100">Batal</button>
                        <button type="submit" class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Simpan Pertanyaan</button>
                    </div>
                </form>
            </div>
        </div>

        <div x-cloak x-show="showEditModal" x-transition.opacity @click.self="showEditModal = false" class="fixed inset-0 z-50 flex items-center justify-center overflow-y-auto bg-slate-950/60 p-4 backdrop-blur-sm" role="dialog" aria-modal="true" aria-labelledby="edit-question-title">
            <div class="my-4 flex max-h-[calc(100vh-2rem)] w-full max-w-4xl flex-col overflow-hidden rounded-2xl bg-white shadow-2xl">
                <div class="flex items-start justify-between border-b border-slate-200 bg-gradient-to-r from-slate-900 to-slate-800 px-5 py-5 text-white sm:px-7">
                    <div>
                        <p class="text-xs font-semibold uppercase tracking-[0.16em] text-cyan-300">Edit data</p>
                        <h3 id="edit-question-title" class="mt-1 text-xl font-bold">Edit Pertanyaan</h3>
                        <p class="mt-1 text-sm text-slate-300">Perbarui isi dan pengaturan soal.</p>
                    </div>
                    <button type="button" @click="showEditModal = false" class="rounded-lg p-2 text-slate-300 transition hover:bg-white/10 hover:text-white" aria-label="Tutup modal">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                <form :action="editAction" method="POST" enctype="multipart/form-data" class="flex min-h-0 flex-1 flex-col">
                    @csrf
                    @method('PUT')
                    <div class="flex-1 space-y-5 overflow-y-auto p-5 sm:p-7">
                        <div>
                            <label for="edit_teks_pertanyaan" class="mb-1.5 block text-sm font-semibold text-slate-700">Pertanyaan <span class="text-rose-500">*</span></label>
                            <textarea id="edit_teks_pertanyaan" name="teks_pertanyaan" x-model="edit.teks_pertanyaan" rows="4" class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500" required></textarea>
                        </div>
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div>
                                <label for="edit_fase" class="mb-1.5 block text-sm font-semibold text-slate-700">Fase tes <span class="text-rose-500">*</span></label>
                                <select id="edit_fase" name="fase" x-model="edit.fase" @change="syncEditIndicators()" class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <option value="1">Fase 1 - Minat (RIASEC)</option><option value="2">Fase 2 - Bakat dasar</option><option value="3">Fase 3 - Bakat lanjutan</option><option value="4">Fase 4 - Temporary display</option>
                                </select>
                            </div>
                            <div>
                                <label for="edit_indicator" class="mb-1.5 block text-sm font-semibold text-slate-700">Parameter <span class="text-rose-500">*</span></label>
                                <select id="edit_indicator" name="kode_indikator" x-model="edit.kode_indikator" class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500" required>
                                    <option value="">Pilih parameter</option>
                                    <optgroup label="Minat (RIASEC)"><option value="R">Realistic (R)</option><option value="I">Investigative (I)</option><option value="A">Artistic (A)</option><option value="S">Social (S)</option><option value="E">Enterprising (E)</option><option value="C">Conventional (C)</option></optgroup>
                                    <optgroup label="Bakat (CHC)"><option value="Gf">Fluid Reasoning (Gf)</option><option value="Gc">Comprehension-Knowledge (Gc)</option><option value="Gv">Visual Processing (Gv)</option><option value="Gq">Quantitative Knowledge (Gq)</option><option value="Gwm">Short-Term Working Memory (Gwm)</option><option value="Gs">Processing Speed (Gs)</option></optgroup>
                                </select>
                            </div>
                        </div>
                        <div x-show="edit.fase !== '1'" x-cloak class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                            <label class="mb-2 block text-sm font-semibold text-slate-700">Opsi jawaban <span class="font-normal text-slate-400">(opsional)</span></label>
                            <div class="space-y-2.5">
                                <template x-for="option in [1, 2, 3, 4]" :key="option">
                                    <div class="flex items-center gap-2"><span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-lg border bg-white text-sm font-bold text-slate-600" x-text="String.fromCharCode(64 + option)"></span><input type="text" :name="'opsi_jawaban[' + option + ']'" x-model="edit.opsi_jawaban[option]" class="w-full rounded-lg border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500" :placeholder="'Tulis opsi ' + String.fromCharCode(64 + option) + '...'"></div>
                                </template>
                            </div>
                        </div>
                        <div x-show="edit.fase !== '1'" x-cloak>
                            <label for="edit_kunci" class="mb-1.5 block text-sm font-semibold text-slate-700">Kunci jawaban</label>
                            <select id="edit_kunci" name="kunci_jawaban" x-model="edit.kunci_jawaban" class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500"><option value="">Pilih kunci jawaban</option><option value="1">A</option><option value="2">B</option><option value="3">C</option><option value="4">D</option></select>
                        </div>
                        <div x-show="edit.fase === '4'" x-cloak class="grid grid-cols-1 gap-4 md:grid-cols-2">
                            <div><label for="edit_teks_awal" class="mb-1.5 block text-sm font-semibold text-slate-700">Teks awal Fase 4</label><textarea id="edit_teks_awal" name="teks_awal" x-model="edit.teks_awal" rows="2" class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500"></textarea></div>
                            <div><label for="edit_bobot" class="mb-1.5 block text-sm font-semibold text-slate-700">Durasi tampil (detik)</label><input id="edit_bobot" type="number" name="bobot" min="1" max="3600" x-model="edit.bobot" class="w-full rounded-xl border-slate-300 text-sm focus:border-blue-500 focus:ring-blue-500"></div>
                        </div>
                        <div class="rounded-xl border border-dashed border-slate-300 bg-slate-50 p-4">
                            <label for="edit_gambar" class="mb-1.5 block text-sm font-semibold text-slate-700">Gambar pendukung</label>
                            <template x-if="edit.gambar"><img :src="edit.gambar" alt="Gambar soal saat ini" class="mb-3 h-28 w-auto rounded-lg border object-cover"></template>
                            <input id="edit_gambar" type="file" name="gambar" accept="image/*" class="w-full text-sm text-slate-500 file:mr-3 file:rounded-lg file:border-0 file:bg-blue-50 file:px-3 file:py-2 file:text-xs file:font-semibold file:text-blue-700 hover:file:bg-blue-100">
                            <p class="mt-1.5 text-xs text-slate-500">Biarkan kosong jika tidak ingin mengganti gambar.</p>
                        </div>
                    </div>
                    <div class="flex shrink-0 flex-col-reverse gap-3 border-t bg-slate-50 px-5 py-4 sm:flex-row sm:justify-end sm:px-7">
                        <button type="button" @click="showEditModal = false" class="rounded-xl border border-slate-300 bg-white px-5 py-2.5 text-sm font-semibold text-slate-700 hover:bg-slate-100">Batal</button>
                        <button type="submit" class="rounded-xl bg-blue-600 px-5 py-2.5 text-sm font-semibold text-white shadow-sm transition hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Tabel Daftar Soal -->
        <div>
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
                    </div>
                    <div class="flex flex-wrap items-center gap-2 mt-4">
                        <button type="submit" class="inline-flex items-center gap-2 px-4 py-2 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition font-medium text-sm">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-4.35-4.35m1.35-5.65a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                            Terapkan Filter
                        </button>
                        @if(request()->hasAny(['search', 'fase']))
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
                                                <button type="button" data-question="{{ base64_encode(json_encode($q)) }}" @click="openEditModal(JSON.parse(atob($el.dataset.question)))" class="p-1.5 bg-amber-50 text-amber-600 rounded-lg hover:bg-amber-100 transition" title="Edit Soal">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                                </button>
                                                <form action="{{ route('admin.questions.destroy', $q->id) }}" method="POST" @submit.prevent="$dispatch('open-confirmation', { form: $el, title: 'Hapus pertanyaan?', message: 'Pertanyaan ini akan dihapus permanen dari bank soal.' })">
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
                                        <td colspan="3" class="px-6 py-12 text-center text-gray-500">
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
</div>
</x-admin-layout>
<script>
    window.questionPage = function () {
        return {
            showCreateModal: false,
            showEditModal: false,
            fase: '1',
            editAction: '',
            edit: {
                teks_pertanyaan: '',
                fase: '1',
                kode_indikator: '',
                opsi_jawaban: {1: '', 2: '', 3: '', 4: ''},
                kunci_jawaban: '',
                teks_awal: '',
                bobot: 12,
                gambar: ''
            },
            openEditModal(question) {
                const options = question.opsi_jawaban || {};
                const indicator = String(question.kode_indikator || '').replace(/[0-9]+/g, '');

                this.edit = {
                    teks_pertanyaan: question.teks_pertanyaan || '',
                    fase: String(question.fase || 1),
                    kode_indikator: indicator,
                    opsi_jawaban: {
                        1: options[1] || options['1'] || '',
                        2: options[2] || options['2'] || '',
                        3: options[3] || options['3'] || '',
                        4: options[4] || options['4'] || ''
                    },
                    kunci_jawaban: question.kunci_jawaban ? String(question.kunci_jawaban) : '',
                    teks_awal: question.teks_awal || '',
                    bobot: question.bobot || 12,
                    gambar: question.gambar ? '{{ asset('storage') }}/' + question.gambar : ''
                };
                this.editAction = '{{ url('/admin/questions') }}/' + question.id;
                this.showEditModal = true;
                this.$nextTick(() => this.syncEditIndicators());
            },
            syncEditIndicators() {
                const indicator = document.getElementById('edit_indicator');
                if (!indicator) return;
                const groups = indicator.querySelectorAll('optgroup');
                const isRiasec = this.edit.fase === '1';
                groups[0].hidden = !isRiasec;
                groups[1].hidden = isRiasec;
                if (indicator.selectedOptions[0]?.parentElement.hidden) {
                    this.edit.kode_indikator = '';
                };
            }
        };
    }

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