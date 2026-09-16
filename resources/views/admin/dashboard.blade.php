<x-admin-layout>
    <!-- Header Halaman -->
    <div class="mb-8 flex flex-col lg:flex-row lg:items-end lg:justify-between gap-5">
        <div>
            <p class="text-sm font-semibold text-cyan-600 mb-2">Selamat datang kembali, {{ explode(' ', Auth::user()->name)[0] }}</p>
            <h1 class="text-3xl font-black tracking-tight text-slate-900">Pantau sistem penjurusan</h1>
            <p class="mt-2 max-w-2xl text-sm leading-6 text-slate-500">Kelola data siswa dan instrumen tes dari satu ruang kerja yang ringkas.</p>
        </div>
        
        <!-- Tombol Aksi Cepat -->
        <div class="flex flex-wrap gap-3">
            <a href="{{ route('admin.siswa') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-slate-200 text-sm font-semibold text-slate-700 rounded-xl hover:border-cyan-300 hover:text-cyan-700 shadow-sm transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v6m3-3h-6M9 11a4 4 0 100-8 4 4 0 000 8zm-7 9a7 7 0 0114 0"></path></svg>
                Data siswa
            </a>
            <a href="{{ route('admin.pertanyaan') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-slate-900 text-white text-sm font-semibold rounded-xl hover:bg-cyan-700 shadow-sm transition-all">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                Kelola bank soal
            </a>
        </div>
    </div>

    <!-- Grid Statistik Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-5 mb-8">
        
        <!-- Widget 1: Total Siswa -->
        <a href="{{ route('admin.siswa') }}" class="group bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex items-center justify-between hover:-translate-y-0.5 hover:shadow-lg hover:border-cyan-200 transition-all">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Total siswa</p>
                <h3 class="text-4xl font-black text-slate-900">{{ $totalSiswa }}</h3>
                <p class="mt-2 text-xs font-medium text-cyan-700">Lihat semua data <span class="group-hover:ml-1 transition-all">→</span></p>
            </div>
            <div class="w-14 h-14 bg-cyan-50 rounded-2xl flex items-center justify-center text-cyan-600">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
            </div>
        </a>

        <!-- Widget 2: Bank Soal -->
        <a href="{{ route('admin.pertanyaan') }}" class="group bg-white p-6 rounded-2xl shadow-sm border border-slate-200 flex items-center justify-between hover:-translate-y-0.5 hover:shadow-lg hover:border-violet-200 transition-all">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Bank pertanyaan</p>
                <h3 class="text-4xl font-black text-slate-900">{{ $totalSoal }}</h3>
                <p class="mt-2 text-xs font-medium text-violet-700">Kelola instrumen <span class="group-hover:ml-1 transition-all">→</span></p>
            </div>
            <div class="w-14 h-14 bg-violet-50 rounded-2xl flex items-center justify-center text-violet-600">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
            </div>
        </a>

        <!-- Widget 3: Tes Selesai -->
        <div class="bg-slate-900 p-6 rounded-2xl shadow-sm border border-slate-800 flex items-center justify-between">
            <div>
                <p class="text-xs font-bold text-slate-400 uppercase tracking-wider mb-2">Tes diselesaikan</p>
                <h3 class="text-4xl font-black text-white">{{ $totalSudahTes }}</h3>
                <p class="mt-2 text-xs font-medium text-emerald-300">Siswa unik yang selesai</p>
            </div>
            <div class="w-14 h-14 bg-emerald-400/15 rounded-2xl flex items-center justify-center text-emerald-300">
                <svg class="w-7 h-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Area Konten Tambahan (Tabel Aktivitas Terbaru / Pengumuman) -->
    <div class="grid grid-cols-1 lg:grid-cols-[1.4fr_1fr] gap-5">
        <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">
            <div class="px-6 py-5 border-b border-slate-100 flex items-center justify-between">
                <div>
                    <h3 class="text-base font-bold text-slate-900">Ruang kerja cepat</h3>
                    <p class="mt-1 text-xs text-slate-500">Pilih langkah berikutnya untuk menyiapkan tes.</p>
                </div>
                <span class="w-9 h-9 rounded-xl bg-cyan-50 text-cyan-600 flex items-center justify-center">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                </span>
            </div>
            <div class="p-6 grid sm:grid-cols-2 gap-4">
                <a href="{{ route('admin.siswa') }}" class="group p-4 rounded-xl border border-slate-200 hover:border-cyan-300 hover:bg-cyan-50/50 transition-colors">
                    <p class="font-bold text-sm text-slate-800">Periksa data siswa</p>
                    <p class="mt-1 text-xs leading-5 text-slate-500">Pastikan peserta sudah terdaftar sebelum tes dimulai.</p>
                    <span class="inline-block mt-3 text-xs font-bold text-cyan-700 group-hover:translate-x-1 transition-transform">Buka data →</span>
                </a>
                <a href="{{ route('admin.pertanyaan') }}" class="group p-4 rounded-xl border border-slate-200 hover:border-violet-300 hover:bg-violet-50/50 transition-colors">
                    <p class="font-bold text-sm text-slate-800">Siapkan pertanyaan</p>
                    <p class="mt-1 text-xs leading-5 text-slate-500">Tambah soal manual atau gunakan bantuan Gemini AI.</p>
                    <span class="inline-block mt-3 text-xs font-bold text-violet-700 group-hover:translate-x-1 transition-transform">Buka bank soal →</span>
                </a>
            </div>
        </div>
        <div class="bg-slate-900 rounded-2xl shadow-sm p-6 text-white relative overflow-hidden">
            <div class="relative z-10">
                <p class="text-xs font-bold uppercase tracking-[0.16em] text-cyan-300">Status sistem</p>
                <h3 class="mt-3 text-xl font-bold">Semua layanan siap</h3>
                <p class="mt-2 text-sm leading-6 text-slate-400">Panel admin aktif dan dapat digunakan untuk mengelola proses penjurusan.</p>
                <div class="mt-6 flex items-center gap-2 text-sm font-semibold text-emerald-300"><span class="w-2 h-2 rounded-full bg-emerald-400"></span> Sistem online</div>
            </div>
            <div class="absolute -right-10 -bottom-12 w-40 h-40 rounded-full border-[18px] border-cyan-400/10"></div>
        </div>
    </div>
</x-admin-layout>