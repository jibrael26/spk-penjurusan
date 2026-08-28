<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Manajemen Pertanyaan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <!-- Notifikasi Flash Message -->
            @if (session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ session('success') }}</span>
                </div>
            @endif
            @if (session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                    <span class="block sm:inline">{{ session('error') }}</span>
                </div>
            @endif

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 bg-white border-b border-gray-200">
                    <h3 class="text-lg font-bold text-gray-900 mb-4">🤖 Generate 30 Pertanyaan dengan AI</h3>
                    <p class="text-gray-600 mb-4 text-sm">Masukkan teks referensi (artikel, kriteria jurusan, atau
                        jurnal). Sistem akan otomatis membuat 30 pertanyaan dengan 5 skala jawaban.</p>

                    <form action="{{ route('admin.questions.generate') }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label for="teks_mentah" class="block text-sm font-medium text-gray-700 mb-2">Teks Referensi
                                (Data Mentah)</label>
                            <textarea name="teks_mentah" id="teks_mentah" rows="6" required
                                class="w-full border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm p-3"
                                placeholder="Paste materi referensi di sini..."></textarea>
                        </div>

                        <button type="submit"
                            class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded transition">
                            Mulai Generate Data
                        </button>
                    </form>
                </div>
            </div>

        </div>
    </div>
</x-app-layout>
