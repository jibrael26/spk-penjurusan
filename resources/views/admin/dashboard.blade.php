<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ __('Admin Panel - SMK N 1 Tatapaan') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-blue-500">
                    <div class="text-sm font-medium text-gray-500 uppercase">Total Siswa Terdaftar</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $totalSiswa }}</div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-green-500">
                    <div class="text-sm font-medium text-gray-500 uppercase">Siswa Sudah Tes</div>
                    <div class="text-3xl font-bold text-gray-800">{{ $totalSudahTes }}</div>
                </div>
                <div class="bg-white p-6 rounded-xl shadow-sm border-l-4 border-purple-500">
                    <div class="text-sm font-medium text-gray-500 uppercase">Status Sistem K-Means</div>
                    <div class="text-sm font-bold text-purple-600">AKTIF (Python Engine)</div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 text-gray-900">
                    <h3 class="text-lg font-bold mb-4">Monitoring Hasil Penjurusan</h3>
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nama Siswa
                                </th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Dominasi
                                    Minat</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Hasil
                                    Cluster</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Aksi</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">Contoh Nama Siswa</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-blue-600">Realistic
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <span class="px-2 py-1 bg-green-100 text-green-800 rounded-full text-xs">TKRO</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm">
                                    <button class="text-blue-600 hover:text-blue-900">Detail</button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
