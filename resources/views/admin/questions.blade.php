<x-admin-layout>
    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Bank Soal & Integrasi AI</h1>
        <p class="mt-1 text-sm text-gray-500">Kelola instrumen penilaian untuk tes penjurusan siswa SMK Negeri 1 Tatapaan.</p>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Kolom Kiri: Form & AI -->
        <div class="space-y-6">
            <!-- Form Manual -->
            <div class="bg-white p-6 rounded-2xl shadow-sm border border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 mb-4">Tambah Soal Manual</h3>
                <form action="{{ route('admin.questions.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pertanyaan</label>
                        <textarea name="teks_pertanyaan" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" required placeholder="Tuliskan pertanyaan..."></textarea>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kriteria / Jurusan</label>
                        <select name="criteria_id" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                            <option value="">Pilih Kriteria / Jurusan</option>
                            @isset($criteria)
                                @foreach($criteria as $crit)
                                    <option value="{{ $crit->id }}">{{ $crit->kode_kriteria }} - {{ $crit->nama_kriteria }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Fase Tes</label>
                        <select name="fase" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" required>
                            <option value="1">Fase 1 (Angket Likert)</option>
                            <option value="2">Fase 2 (Pilihan Ganda A-E)</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full py-2.5 px-4 bg-gray-900 text-white rounded-lg hover:bg-gray-800 transition font-medium text-sm">
                        Simpan Pertanyaan
                    </button>
                </form>
            </div>

            <!-- Panel Generate AI -->
            <div class="bg-gradient-to-br from-indigo-500 to-purple-600 p-6 rounded-2xl shadow-md text-white">
                <h3 class="text-lg font-bold mb-2 flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Asisten AI (Gemini)
                </h3>
                <p class="text-indigo-100 text-sm mb-4 leading-relaxed">Generate soal otomatis berdasarkan referensi teks materi kompetensi.</p>
                
                <form action="{{ route('admin.questions.generate') }}" method="POST">
                    @csrf
                    <div class="mb-3">
                        <label class="block text-xs font-semibold text-indigo-100 mb-1 uppercase tracking-wider">Target Jurusan</label>
                        <select name="criteria_id" class="w-full border-transparent bg-white/20 text-white rounded-lg focus:ring-2 focus:ring-white focus:border-transparent text-sm placeholder-indigo-200" required>
                            <option value="" class="text-gray-800">Pilih Jurusan Target AI</option>
                            @isset($criteria)
                                @foreach($criteria as $crit)
                                    <option value="{{ $crit->id }}" class="text-gray-800">{{ $crit->kode_kriteria }} - {{ $crit->nama_kriteria }}</option>
                                @endforeach
                            @endisset
                        </select>
                    </div>

                    <div class="mb-4">
                        <textarea name="teks_mentah" rows="3" class="w-full border-transparent bg-white/10 text-white placeholder-indigo-200 rounded-lg focus:ring-2 focus:ring-white focus:border-transparent text-sm" required placeholder="Paste teks materi atau ringkasan kompetensi di sini..."></textarea>
                    </div>
                    <button type="submit" class="w-full py-2.5 px-4 bg-white text-indigo-600 rounded-lg hover:bg-gray-50 transition font-bold text-sm shadow">
                        Generate Soal Otomatis
                    </button>
                </form>
            </div>
        </div>

        <!-- Kolom Kanan: Tabel Daftar Soal -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden h-full flex flex-col">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <h3 class="text-base font-semibold text-gray-900">Daftar Pertanyaan Aktif</h3>
                    <span class="text-xs font-medium bg-blue-100 text-blue-700 px-2.5 py-1 rounded-full">
                        Total: {{ isset($questions) ? $questions->total() : 0 }}
                    </span>
                </div>
                
                <div class="overflow-x-auto flex-grow">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50/50 border-b border-gray-100 text-gray-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4 font-semibold w-1/2">Pertanyaan</th>
                                <th class="px-6 py-4 font-semibold">Kriteria / Jurusan</th>
                                <th class="px-6 py-4 font-semibold text-center">Fase</th>
                                <th class="px-6 py-4 font-semibold text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @isset($questions)
                                @forelse ($questions as $q)
                                    <tr class="hover:bg-gray-50 transition-colors">
                                        <td class="px-6 py-4 text-gray-900">
                                            <div class="line-clamp-2" title="{{ $q->teks_pertanyaan }}">
                                                {{ $q->teks_pertanyaan }}
                                            </div>
                                        </td>
                                        <td class="px-6 py-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-semibold bg-indigo-50 text-indigo-700">
                                                {{ $q->criteria->kode_kriteria ?? 'Umum' }}
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 text-center">
                                            @if($q->fase == 1)
                                                <span class="bg-emerald-50 text-emerald-700 py-1 px-2.5 rounded-full text-xs font-medium">Fase 1</span>
                                            @else
                                                <span class="bg-amber-50 text-amber-700 py-1 px-2.5 rounded-full text-xs font-medium">Fase 2</span>
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