<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-slate-900">Selamat Datang Kembali! 👋</h2>
        <p class="text-sm text-slate-500 mt-1">Silakan masuk ke akun Anda untuk melanjutkan.</p>
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('login') }}">
        @csrf
        
        <!-- Input Email -->
        <div class="mb-5">
            <label for="email" class="block font-medium text-sm text-slate-700 mb-1">Alamat Email</label>
            <input id="email" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-colors bg-slate-50 focus:bg-white" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" placeholder="Masukkan email Anda" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Input Password -->
        <div class="mb-5">
            <label for="password" class="block font-medium text-sm text-slate-700 mb-1">Kata Sandi</label>
            <input id="password" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-colors bg-slate-50 focus:bg-white" type="password" name="password" required autocomplete="current-password" placeholder="••••••••" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Remember Me & Forgot Password -->
        <div class="flex items-center justify-between mb-8">
            <label for="remember_me" class="inline-flex items-center cursor-pointer">
                <input id="remember_me" type="checkbox" class="rounded border-slate-300 text-indigo-600 shadow-sm focus:ring-indigo-500 cursor-pointer" name="remember">
                <span class="ms-2 text-sm text-slate-600 font-medium">Ingat saya</span>
            </label>

            @if (Route::has('password.request'))
                <a class="text-sm font-bold text-indigo-600 hover:text-indigo-800 transition-colors" href="{{ route('password.request') }}">
                    Lupa sandi?
                </a>
            @endif
        </div>

        <!-- Tombol Aksi -->
        <div class="flex flex-col items-center gap-5">
            <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3.5 bg-indigo-600 border border-transparent rounded-xl font-bold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5">
                Masuk ke Sistem
            </button>
            
            <p class="text-sm text-slate-600">
                Belum punya akun? 
                <a href="{{ route('register') }}" class="font-bold text-indigo-600 hover:text-indigo-800 transition-colors">Daftar sekarang</a>
            </p>
        </div>
    </form>
</x-guest-layout>