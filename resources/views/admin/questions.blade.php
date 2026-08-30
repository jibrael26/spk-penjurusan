<x-admin-layout>
    <!-- Pesan Sukses -->
    @if (session('success'))
        <div class="mb-6 bg-emerald-50 border-l-4 border-emerald-500 text-emerald-700 p-4 rounded-r-lg shadow-sm">
            <p class="font-medium">{{ session('success') }}</p>
        </div>
    @endif

    <div class="mb-6">
        <h1 class="text-2xl font-bold text-gray-900">Bank Soal & Integrasi AI</h1>
        <p class="mt-1 text-sm text-gray-500">Kelola instrumen penilaian untuk tes penjurusan siswa.</p>
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
                        <textarea name="question_text" rows="3" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" required placeholder="Tuliskan pertanyaan..."></textarea>
                    </div>
                    <div class="mb-4">
                        <label class="block text-sm font-medium text-gray-700 mb-1">Kategori / Indikator</label>
                        <input type="text" name="category" class="w-full border-gray-300 rounded-lg shadow-sm focus:ring-blue-500 focus:border-blue-500 text-sm" required placeholder="Contoh: Logika Matematika">
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
                    Asisten AI
                </h3>
                <p class="text-indigo-100 text-sm mb-5 leading-relaxed">Buat variasi soal penjurusan secara otomatis menggunakan kecerdasan buatan.</p>
                <form action="{{ route('admin.questions.generate') }}" method="POST">
                    @csrf
                    <button type="submit" class="w-full py-2.5 px-4 bg-white text-indigo-600 rounded-lg hover:bg-gray-50 transition font-bold text-sm shadow">
                        Generate Soal Otomatis
                    </button>
                </form>
            </div>
        </div>

        <!-- Kolom Kanan: Tabel Daftar Soal -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden h-full">
                <div class="px-6 py-5 border-b border-gray-100 bg-gray-50/50 flex justify-between items-center">
                    <h3 class="text-base font-semibold text-gray-900">Daftar Pertanyaan Aktif</h3>
                    <span class="text-xs font-medium bg-blue-100 text-blue-700 px-2.5 py-1 rounded-full">Total: {{ $questions->total() }}</span>
                </div>
                
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-sm text-gray-600">
                        <thead class="bg-gray-50/50 border-b border-gray-100 text-gray-500 uppercase tracking-wider">
                            <tr>
                                <th class="px-6 py-4 font-semibold w-2/3">Pertanyaan</th>
                                <th class="px-6 py-4 font-semibold">Kategori</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @forelse ($questions as $q)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 text-gray-900">{{ $q->question_text }}</td>
                                    <td class="px-6 py-4">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                            {{ $q->category }}
                                        </span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="2" class="px-6 py-12 text-center text-gray-500">
                                        <svg class="mx-auto h-12 w-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                                        Belum ada soal di dalam database.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                @if($questions->hasPages())
                    <div class="px-6 py-4 border-t border-gray-100">
                        {{ $questions->links() }}
                    </div>
                @endif
            </div>
        </div>
    </div>
</x-admin-layout>