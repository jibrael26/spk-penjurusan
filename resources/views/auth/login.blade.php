<x-guest-layout>
    <div class="mb-8">
        <p class="text-sm font-bold text-indigo-600">Selamat datang kembali</p>
        <h2 class="mt-1 text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">Masuk ke akun Anda</h2>
        <p class="mt-2 text-sm leading-6 text-slate-500">Lanjutkan asesmen dan lihat rekomendasi Program Keahlian Anda.</p>
    </div>

    <x-auth-session-status class="mb-5" :status="session('status')" />
    <form method="POST" action="{{ route('login') }}" class="space-y-5">
        @csrf
        <div>
            <label for="email" class="mb-2 block text-sm font-bold text-slate-700">Alamat email</label>
            <input id="email" class="block min-h-12 w-full rounded-xl border-slate-200 bg-slate-50 px-4 text-sm shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-100" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="nama@email.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div>
            <div class="mb-2 flex items-center justify-between gap-3">
                <label for="password" class="block text-sm font-bold text-slate-700">Kata sandi</label>
                @if (Route::has('password.request'))
                    <a class="text-xs font-bold text-indigo-600 hover:text-indigo-800" href="{{ route('password.request') }}">Lupa sandi?</a>
                @endif
            </div>
            <input id="password" class="block min-h-12 w-full rounded-xl border-slate-200 bg-slate-50 px-4 text-sm shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-100" type="password" name="password" required autocomplete="current-password" placeholder="Masukkan kata sandi">
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>
        <label for="remember_me" class="inline-flex cursor-pointer items-center gap-2">
            <input id="remember_me" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500" name="remember">
            <span class="text-sm font-medium text-slate-600">Ingat saya di perangkat ini</span>
        </label>
        <button type="submit" class="inline-flex min-h-12 w-full items-center justify-center rounded-xl bg-indigo-600 px-6 text-sm font-bold text-white shadow-lg shadow-indigo-600/20 transition hover:-translate-y-0.5 hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-200">Masuk ke Sistem</button>
    </form>
    <p class="mt-7 border-t border-slate-100 pt-6 text-center text-sm text-slate-600">Belum punya akun? <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-800">Daftar sekarang</a></p>
</x-guest-layout>
