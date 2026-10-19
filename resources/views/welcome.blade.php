<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Sistem Rekomendasi Jurusan SMK Negeri 1 Tatapaan untuk membantu siswa mengenali minat dan bakatnya.">
    <title>SPK Penjurusan | SMK Negeri 1 Tatapaan</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600,700,800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-50 font-[Figtree] text-slate-900 antialiased selection:bg-indigo-600 selection:text-white">
    <nav class="fixed inset-x-0 top-0 z-50 border-b border-slate-200/70 bg-white/90 backdrop-blur-xl">
        <div class="mx-auto flex h-[4.5rem] max-w-7xl items-center justify-between px-4 sm:px-6 lg:px-8">
            <a href="{{ route('landing') }}" class="flex items-center gap-3" aria-label="Beranda SPK Penjurusan">
                <img src="{{ asset('logo-smk.png') }}" alt="Logo SMK Negeri 1 Tatapaan" class="h-11 w-11 object-contain">
                <span class="leading-tight">
                    <span class="block text-sm font-extrabold tracking-tight text-slate-900 sm:text-base">SMK Negeri 1 Tatapaan</span>
                    <span class="mt-0.5 block text-[10px] font-bold uppercase tracking-[0.18em] text-indigo-500">Sistem Penjurusan</span>
                </span>
            </a>

            <div class="hidden items-center gap-7 md:flex">
                <a href="#tentang" class="text-sm font-semibold text-slate-600 transition hover:text-indigo-600">Tentang Sistem</a>
                <a href="#proses" class="text-sm font-semibold text-slate-600 transition hover:text-indigo-600">Alur Asesmen</a>
                <a href="#fitur" class="text-sm font-semibold text-slate-600 transition hover:text-indigo-600">Keunggulan</a>
                <span class="h-6 w-px bg-slate-200"></span>
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-indigo-200 transition hover:-translate-y-0.5 hover:bg-indigo-700">Buka Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="text-sm font-bold text-slate-600 transition hover:text-indigo-600">Masuk</a>
                    @if (Route::has('register'))
                        <a href="{{ route('register') }}" class="rounded-xl bg-indigo-600 px-4 py-2.5 text-sm font-bold text-white shadow-lg shadow-indigo-200 transition hover:-translate-y-0.5 hover:bg-indigo-700">Daftar Sekarang</a>
                    @endif
                @endauth
            </div>

            <div class="flex items-center gap-2 md:hidden">
                @auth
                    <a href="{{ route('dashboard') }}" class="rounded-lg bg-indigo-600 px-3 py-2 text-xs font-bold text-white">Dashboard</a>
                @else
                    <a href="{{ route('login') }}" class="rounded-lg border border-slate-200 px-3 py-2 text-xs font-bold text-slate-700">Masuk</a>
                @endauth
            </div>
        </div>
    </nav>

    <main>
        <section class="relative isolate overflow-hidden pt-32 sm:pt-36 lg:pt-44">
            <div class="absolute -left-32 top-20 -z-10 h-80 w-80 rounded-full bg-indigo-200/50 blur-3xl"></div>
            <div class="absolute -right-32 top-28 -z-10 h-96 w-96 rounded-full bg-blue-200/50 blur-3xl"></div>
            <div class="mx-auto grid max-w-7xl items-center gap-12 px-4 pb-20 sm:px-6 lg:grid-cols-[1.1fr_0.9fr] lg:px-8 lg:pb-28">
                <div>
                    <div class="mb-6 inline-flex items-center gap-2 rounded-full border border-indigo-100 bg-indigo-50 px-3.5 py-2 text-xs font-bold text-indigo-700 sm:text-sm">
                        <span class="h-2 w-2 rounded-full bg-indigo-500 ring-4 ring-indigo-100"></span>
                        Platform resmi penjurusan siswa
                    </div>
                    <h1 class="max-w-3xl text-4xl font-extrabold leading-[1.1] tracking-tight text-slate-950 sm:text-5xl lg:text-6xl">
                        Kenali potensimu,
                        <span class="text-indigo-600">tentukan langkahmu.</span>
                    </h1>
                    <p class="mt-6 max-w-2xl text-base leading-8 text-slate-600 sm:text-lg">
                        SPK Penjurusan membantu siswa SMK Negeri 1 Tatapaan menemukan bidang yang paling sesuai melalui asesmen minat, bakat, dan kemampuan secara terarah.
                    </p>
                    <div class="mt-8 flex flex-col gap-3 sm:flex-row">
                        @auth
                            <a href="{{ route('assessment.index') }}" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-6 py-3.5 text-sm font-extrabold text-white shadow-xl shadow-indigo-200 transition hover:-translate-y-0.5 hover:bg-indigo-700">
                                Lanjutkan Asesmen
                                <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                            </a>
                        @else
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="inline-flex items-center justify-center rounded-xl bg-indigo-600 px-6 py-3.5 text-sm font-extrabold text-white shadow-xl shadow-indigo-200 transition hover:-translate-y-0.5 hover:bg-indigo-700">
                                    Mulai Asesmen
                                    <svg class="ml-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/></svg>
                                </a>
                            @endif
                            <a href="#proses" class="inline-flex items-center justify-center rounded-xl border border-slate-200 bg-white px-6 py-3.5 text-sm font-extrabold text-slate-700 transition hover:border-indigo-200 hover:bg-indigo-50 hover:text-indigo-700">Pelajari Caranya</a>
                        @endauth
                    </div>
                    <div class="mt-9 flex flex-wrap items-center gap-x-6 gap-y-3 text-xs font-semibold text-slate-500 sm:text-sm">
                        <span class="inline-flex items-center gap-2"><svg class="h-5 w-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4 4L19 6"/></svg>Berbasis data</span>
                        <span class="inline-flex items-center gap-2"><svg class="h-5 w-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4 4L19 6"/></svg>Hasil mudah dipahami</span>
                        <span class="inline-flex items-center gap-2"><svg class="h-5 w-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4 4L19 6"/></svg>Akses aman</span>
                    </div>
                </div>

                <div class="relative mx-auto w-full max-w-md lg:max-w-none">
                    <div class="absolute -inset-4 rounded-[2rem] bg-gradient-to-br from-indigo-500/20 to-blue-500/10 blur-2xl"></div>
                    <div class="relative overflow-hidden rounded-[2rem] border border-white/70 bg-gradient-to-br from-indigo-700 via-indigo-600 to-blue-600 p-6 shadow-2xl shadow-indigo-200 sm:p-8">
                        <div class="absolute -right-16 -top-16 h-48 w-48 rounded-full border-[24px] border-white/10"></div>
                        <div class="absolute -bottom-20 -left-16 h-48 w-48 rounded-full bg-white/10 blur-2xl"></div>
                        <div class="relative">
                            <div class="flex items-center justify-between">
                                <div class="flex h-12 w-12 items-center justify-center rounded-2xl bg-white/15 text-white backdrop-blur"><svg class="h-7 w-7" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.7" d="M9 3v2m6-2v2M4 9h16M5 5h14a1 1 0 011 1v13a1 1 0 01-1 1H5a1 1 0 01-1-1V6a1 1 0 011-1Z"/></svg></div>
                                <span class="rounded-full bg-emerald-400/20 px-3 py-1.5 text-xs font-bold text-emerald-100">Profil aktif</span>
                            </div>
                            <p class="mt-10 text-sm font-semibold text-indigo-100">Ringkasan asesmen</p>
                            <h2 class="mt-1 text-2xl font-extrabold text-white">Pahami dirimu lebih baik</h2>
                            <div class="mt-7 space-y-4">
                                <div class="rounded-2xl bg-white/10 p-4 backdrop-blur-sm"><div class="flex items-center justify-between text-sm font-bold text-white"><span>Minat (RIASEC)</span><span>Terarah</span></div><div class="mt-3 h-2 rounded-full bg-white/20"><div class="h-2 w-4/5 rounded-full bg-cyan-300"></div></div></div>
                                <div class="rounded-2xl bg-white/10 p-4 backdrop-blur-sm"><div class="flex items-center justify-between text-sm font-bold text-white"><span>Bakat dasar (CHC)</span><span>Terukur</span></div><div class="mt-3 h-2 rounded-full bg-white/20"><div class="h-2 w-3/4 rounded-full bg-blue-200"></div></div></div>
                            </div>
                            <div class="mt-7 flex items-center gap-3 border-t border-white/15 pt-5 text-sm text-indigo-100"><span class="flex h-8 w-8 items-center justify-center rounded-full bg-white text-indigo-600"><svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m5 12 4 4L19 6"/></svg></span>Rekomendasi jurusan yang lebih percaya diri</div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <section id="tentang" class="border-y border-slate-200 bg-white py-16 sm:py-20">
            <div class="mx-auto grid max-w-7xl gap-10 px-4 sm:px-6 lg:grid-cols-[0.8fr_1.2fr] lg:items-center lg:px-8">
                <div>
                    <p class="text-sm font-extrabold uppercase tracking-[0.18em] text-indigo-600">Mengapa sistem ini?</p>
                    <h2 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Pilihan jurusan dimulai dari pemahaman diri.</h2>
                </div>
                <p class="max-w-3xl text-base leading-8 text-slate-600">Setiap siswa memiliki minat dan kekuatan yang berbeda. Sistem ini menyajikan proses penjurusan yang lebih terstruktur agar keputusan akademik tidak hanya berdasarkan tren, tetapi juga berdasarkan profil dan potensi diri.</p>
            </div>
        </section>

        <section id="proses" class="py-20 sm:py-24">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="mx-auto max-w-2xl text-center"><p class="text-sm font-extrabold uppercase tracking-[0.18em] text-indigo-600">Alur sederhana</p><h2 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Dari asesmen menuju arah yang jelas</h2><p class="mt-4 leading-7 text-slate-600">Ikuti prosesnya dengan tenang. Hasil dirancang untuk menjadi bahan pertimbangan, bukan batasan.</p></div>
                <div class="mt-12 grid gap-5 md:grid-cols-3">
                    @foreach ([
                        ['01', 'Buat akun siswa', 'Daftarkan akun dan lengkapi data diri untuk memulai proses asesmen.'],
                        ['02', 'Kerjakan asesmen', 'Jawab pertanyaan minat dan bakat sesuai kondisi serta dirimu yang sebenarnya.'],
                        ['03', 'Lihat rekomendasi', 'Pelajari hasil analisis dan rekomendasi bidang jurusan yang paling sesuai.'],
                    ] as [$number, $title, $description])
                        <div class="relative rounded-2xl border border-slate-200 bg-white p-7 shadow-sm transition hover:-translate-y-1 hover:border-indigo-200 hover:shadow-xl hover:shadow-indigo-100/50">
                            <span class="text-4xl font-extrabold text-indigo-100">{{ $number }}</span>
                            <h3 class="mt-5 text-lg font-extrabold text-slate-900">{{ $title }}</h3>
                            <p class="mt-2 text-sm leading-7 text-slate-600">{{ $description }}</p>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <section id="fitur" class="bg-white py-20 sm:py-24">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="max-w-2xl"><p class="text-sm font-extrabold uppercase tracking-[0.18em] text-indigo-600">Keunggulan</p><h2 class="mt-3 text-3xl font-extrabold tracking-tight text-slate-900 sm:text-4xl">Dirancang untuk mendampingi perjalanan siswa</h2></div>
                <div class="mt-12 grid gap-6 md:grid-cols-3">
                    @foreach ([
                        ['bg-indigo-50 text-indigo-600', 'Analisis terarah', 'Profil minat RIASEC dan bakat dasar CHC membantu memberikan gambaran yang lebih menyeluruh.'],
                        ['bg-blue-50 text-blue-600', 'Hasil transparan', 'Lihat ringkasan hasil dan alasan rekomendasi dengan bahasa yang mudah dipahami.'],
                        ['bg-emerald-50 text-emerald-600', 'Data terlindungi', 'Informasi asesmen tersimpan di akun masing-masing dan hanya dapat diakses dengan aman.'],
                    ] as [$iconClass, $title, $description])
                        <div class="rounded-2xl border border-slate-200 p-7"><div class="flex h-12 w-12 items-center justify-center rounded-xl {{ $iconClass }}"><svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z"/></svg></div><h3 class="mt-5 text-lg font-extrabold text-slate-900">{{ $title }}</h3><p class="mt-2 text-sm leading-7 text-slate-600">{{ $description }}</p></div>
                    @endforeach
                </div>
            </div>
        </section>

        <section class="px-4 py-16 sm:px-6 sm:py-24 lg:px-8">
            <div class="relative mx-auto max-w-7xl overflow-hidden rounded-[2rem] bg-gradient-to-r from-indigo-700 to-blue-600 px-6 py-12 text-center shadow-2xl shadow-indigo-200 sm:px-12 sm:py-16">
                <div class="absolute inset-0 opacity-10" style="background-image: radial-gradient(#fff 1px, transparent 1px); background-size: 20px 20px;"></div>
                <div class="relative"><h2 class="text-3xl font-extrabold text-white sm:text-4xl">Siap mengenali potensimu?</h2><p class="mx-auto mt-4 max-w-xl leading-7 text-indigo-100">Mulai asesmen dan jadikan hasilnya sebagai langkah awal untuk merencanakan masa depanmu.</p>
                    @guest
                        @if (Route::has('register'))<a href="{{ route('register') }}" class="mt-8 inline-flex rounded-xl bg-white px-6 py-3.5 text-sm font-extrabold text-indigo-700 transition hover:bg-indigo-50">Daftar dan Mulai Asesmen</a>@endif
                    @else
                        <a href="{{ route('assessment.index') }}" class="mt-8 inline-flex rounded-xl bg-white px-6 py-3.5 text-sm font-extrabold text-indigo-700 transition hover:bg-indigo-50">Buka Halaman Asesmen</a>
                    @endguest
                </div>
            </div>
        </section>
    </main>

    <footer class="border-t border-slate-200 bg-white">
        <div class="mx-auto flex max-w-7xl flex-col gap-5 px-4 py-8 sm:px-6 md:flex-row md:items-center md:justify-between lg:px-8">
            <div class="flex items-center gap-3"><img src="{{ asset('logo-smk.png') }}" alt="Logo SMK Negeri 1 Tatapaan" class="h-9 w-9 object-contain"><div><p class="text-sm font-extrabold text-slate-900">SMK Negeri 1 Tatapaan</p><p class="text-xs font-semibold text-indigo-500">Sistem Rekomendasi Jurusan</p></div></div>
            <p class="text-xs font-medium text-slate-500">&copy; {{ date('Y') }} SMK Negeri 1 Tatapaan. Semua hak dilindungi.</p>
        </div>
    </footer>
</body>
</html>
