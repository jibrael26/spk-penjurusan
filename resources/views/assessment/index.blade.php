<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('assessment.store') }}" method="POST" class="bg-white shadow-sm sm:rounded-lg p-8">
                @csrf
                <h2 class="text-2xl font-bold mb-6 border-b pb-2 text-blue-800">Tes Minat & Bakat</h2>

                <div class="mb-8">
                    <h4 class="font-bold text-gray-700 mb-2 underline">Bagian 1: Minat (Realistic)</h4>
                    <p class="text-sm text-gray-600 mb-4">Sejauh mana Anda menyukai aktivitas praktis yang melibatkan
                        alat atau mesin? [cite: 134, 136]</p>
                    <div class="flex justify-between items-center bg-gray-50 p-4 rounded-lg">
                        <span>Sangat Tidak Suka</span>
                        @for ($i = 1; $i <= 5; $i++)
                            <label class="flex flex-col items-center">
                                <input type="radio" name="realistic" value="{{ $i }}" class="text-blue-600"
                                    required>
                                <span class="text-xs mt-1">{{ $i }}</span>
                            </label>
                        @endfor
                        <span>Sangat Suka</span>
                    </div>
                </div>

                <div class="mb-8 border-t pt-6">
                    <h4 class="font-bold text-gray-700 mb-2 underline">Bagian 2: Bakat (Kemampuan Numerik)</h4>
                    <p class="text-sm text-gray-600 mb-4">Seberapa mudah bagi Anda untuk menyelesaikan soal perhitungan
                        angka atau aritmatika? [cite: 166]</p>
                    <div class="flex justify-between items-center bg-gray-50 p-4 rounded-lg">
                        <span>Sangat Sulit</span>
                        @for ($i = 1; $i <= 5; $i++)
                            <label class="flex flex-col items-center">
                                <input type="radio" name="numerical_ability" value="{{ $i }}"
                                    class="text-blue-600" required>
                                <span class="text-xs mt-1">{{ $i }}</span>
                            </label>
                        @endfor
                        <span>Sangat Mudah</span>
                    </div>
                </div>

                <button type="submit"
                    class="w-full bg-green-600 text-white font-bold py-3 rounded-lg hover:bg-green-700 transition">
                    Simpan & Lihat Rekomendasi
                </button>
            </form>
        </div>
    </div>
</x-app-layout>
