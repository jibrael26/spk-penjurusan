<x-app-layout>
    <div class="py-12 bg-gray-100">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <form action="{{ route('assessment.store') }}" method="POST"
                class="bg-white shadow-xl rounded-2xl overflow-hidden">
                @csrf
                <div class="p-8 bg-blue-600">
                    <h2 class="text-2xl font-bold text-white">Instrumen Minat & Bakat</h2>
                    <p class="text-blue-100 text-sm">SMK Negeri 1 Tatapaan - Identifikasi Potensi Diri</p>
                </div>

                <div class="p-8 space-y-8">
                    <div>
                        <h3 class="text-lg font-bold text-gray-800 border-l-4 border-blue-600 pl-3 mb-4">I. Tes Minat
                            (Holland Theory)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            @foreach (['realistic', 'investigative', 'artistic', 'social', 'enterprising', 'conventional'] as $type)
                                <div class="p-4 border rounded-lg bg-gray-50">
                                    <label
                                        class="block font-medium text-gray-700 capitalize mb-2">{{ $type }}</label>
                                    <div class="flex justify-between gap-2">
                                        @for ($i = 1; $i <= 5; $i++)
                                            <label class="flex-1 text-center">
                                                <input type="radio" name="{{ $type }}"
                                                    value="{{ $i }}" class="sr-only peer" required>
                                                <div
                                                    class="p-2 border rounded cursor-pointer peer-checked:bg-blue-600 peer-checked:text-white hover:bg-blue-100 transition">
                                                    {{ $i }}
                                                </div>
                                            </label>
                                        @endfor
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div>
                        <h3 class="text-lg font-bold text-gray-800 border-l-4 border-green-600 pl-3 mb-4">II. Tes Bakat
                            (DAT)</h3>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            @foreach (['numerical_ability', 'verbal_reasoning', 'mechanical_reasoning'] as $aptitude)
                                <div class="p-4 border rounded-lg bg-gray-50">
                                    <label
                                        class="block font-medium text-gray-700 mb-2">{{ str_replace('_', ' ', ucfirst($aptitude)) }}</label>
                                    <select name="{{ $aptitude }}"
                                        class="w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                        <option value="1">Sangat Rendah</option>
                                        <option value="2">Rendah</option>
                                        <option value="3">Cukup</option>
                                        <option value="4">Tinggi</option>
                                        <option value="5">Sangat Tinggi</option>
                                    </select>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    <div class="pt-6">
                        <button type="submit"
                            class="w-full bg-blue-600 text-white font-bold py-4 rounded-xl shadow-lg hover:bg-blue-700 transition duration-300 transform hover:-translate-y-1">
                            Proses Rekomendasi Penjurusan
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
