<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Pusat Penjurusan SMK N 1 Tatapaan') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg p-6 text-gray-900">
                <h3 class="text-lg font-bold mb-4">Selamat Datang, {{ Auth::user()->name }}!</h3>
                <p class="mb-6">Sistem ini akan membantu Anda menentukan jurusan yang paling sesuai berdasarkan **Minat
                    (RIASEC)** dan **Bakat (DAT)** Anda[cite: 132, 163].</p>

                <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
                    <p class="text-sm text-blue-700">
                        <strong>Info:</strong> Pastikan menjawab dengan jujur sesuai kondisi diri Anda saat ini agar
                        rekomendasi akurat[cite: 122].
                    </p>
                </div>

                <a href="{{ route('assessment.index') }}"
                    class="inline-flex items-center px-4 py-2 bg-blue-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-blue-700 focus:bg-blue-700 active:bg-blue-900 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition ease-in-out duration-150">
                    Mulai Tes Pengetahuan Diri
                </a>
            </div>
        </div>
    </div>
</x-app-layout>
