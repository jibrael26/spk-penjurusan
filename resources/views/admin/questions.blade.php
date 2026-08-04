<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Pertanyaan Tes Penjurusan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            
            <!-- Notifikasi Sukses -->
            @if(session('success'))
                <div class="mb-4 p-4 bg-green-100 text-green-700 rounded-md font-medium">
                    {{ session('success') }}
                </div>
            @endif

            <!-- Bagian Form Tambah Pertanyaan -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 mb-8">
                <h3 class="font-bold text-lg text-gray-800 mb-4 border-b pb-2">Tambah Pertanyaan Baru</h3>
                
                <div class="mb-6 p-4 bg-blue-50 text-blue-800 rounded-md text-sm">
                    <strong>Informasi:</strong> Pertanyaan ini akan dijawab oleh siswa menggunakan skala 5 poin (Sangat Tidak Setuju - Sangat Setuju).
                </div>

                <form action="{{ route('admin.questions.store') }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <x-input-label for="kategori" value="Kategori / Indikator Jurusan" />
                        <select id="kategori" name="kategori" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" required>
                            <option value="" disabled selected>-- Pilih Kategori --</option>
                            <option value="IPA">Ilmu Pengetahuan Alam (IPA)</option>
                            <option value="IPS">Ilmu Pengetahuan Sosial (IPS)</option>
                            <option value="BAHASA">Bahasa</option>
                        </select>
                        <x-input-error :messages="$errors->get('kategori')" class="mt-2" />
                    </div>

                    <div class="mb-4">
                        <x-input-label for="question_text" value="Pernyataan / Pertanyaan" />
                        <textarea id="question_text" name="question_text" rows="3" class="block mt-1 w-full border-gray-300 rounded-md shadow-sm" placeholder="Contoh: Saya sangat tertarik melakukan eksperimen di laboratorium..." required></textarea>
                        <x-input-error :messages="$errors->get('question_text')" class="mt-2" />
                    </div>

                    <div class="flex items-center justify-end mt-4">
                        <x-primary-button>
                            {{ __('Simpan Pertanyaan') }}
                        </x-primary-button>
                    </div>
                </form>
            </div>

            <!-- Bagian Tabel Daftar Pertanyaan -->
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6">
                <h3 class="font-bold text-lg text-gray-800 mb-4 border-b pb-2">Daftar Pertanyaan</h3>
                
                <div class="overflow-x-auto">
                    <table class="min-w-full bg-white border border-gray-200">
                        <thead>
                            <tr class="bg-gray-100 text-gray-600 uppercase text-sm leading-normal">
                                <th class="py-3 px-6 text-left w-12">No</th>
                                <th class="py-3 px-6 text-left w-1/4">Kategori</th>
                                <th class="py-3 px-6 text-left">Pernyataan</th>
                            </tr>
                        </thead>
                        <tbody class="text-gray-600 text-sm font-light">
                            @forelse($questions as $index => $question)
                                <tr class="border-b border-gray-200 hover:bg-gray-50">
                                    <td class="py-3 px-6 text-left whitespace-nowrap">
                                        {{ $questions->firstItem() + $index }}
                                    </td>
                                    <td class="py-3 px-6 text-left">
                                        <span class="bg-blue-100 text-blue-700 py-1 px-3 rounded-full text-xs font-semibold">
                                            {{ $question->kategori }}
                                        </span>
                                    </td>
                                    <td class="py-3 px-6 text-left">
                                        {{ $question->question_text }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="3" class="py-4 px-6 text-center text-gray-500 italic">
                                        Belum ada data pertanyaan yang ditambahkan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                
                <!-- Navigasi Pagination -->
                <div class="mt-4">
                    {{ $questions->links() }}
                </div>
            </div>

        </div>
    </div>
</x-app-layout>