<x-guest-layout>
    <div class="text-center mb-8">
        <h2 class="text-2xl font-bold text-slate-900">Buat Akun Baru ✨</h2>
        <p class="text-sm text-slate-500 mt-1">Lengkapi data di bawah ini untuk memulai tes.</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Input Nama Lengkap -->
        <div class="mb-4">
            <label for="name" class="block font-medium text-sm text-slate-700 mb-1">Nama Lengkap</label>
            <input id="name" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-colors bg-slate-50 focus:bg-white" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="John Doe" />
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>

        <!-- Input Email -->
        <div class="mb-4">
            <label for="email" class="block font-medium text-sm text-slate-700 mb-1">Alamat Email</label>
            <input id="email" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-colors bg-slate-50 focus:bg-white" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="email@contoh.com" />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <!-- Input Password -->
        <div class="mb-4">
            <label for="password" class="block font-medium text-sm text-slate-700 mb-1">Kata Sandi</label>
            <input id="password" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-colors bg-slate-50 focus:bg-white" type="password" name="password" required autocomplete="new-password" placeholder="Minimal 8 karakter" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
        </div>

        <!-- Konfirmasi Password -->
        <div class="mb-8">
            <label for="password_confirmation" class="block font-medium text-sm text-slate-700 mb-1">Konfirmasi Kata Sandi</label>
            <input id="password_confirmation" class="block w-full rounded-xl border-slate-200 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50 transition-colors bg-slate-50 focus:bg-white" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi" />
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
        </div>

        <!-- Tombol Aksi -->
        <div class="flex flex-col items-center gap-5">
            <button type="submit" class="w-full inline-flex justify-center items-center px-6 py-3.5 bg-indigo-600 border border-transparent rounded-xl font-bold text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all shadow-md hover:shadow-lg hover:-translate-y-0.5">
                Daftar Akun
            </button>
            
            <p class="text-sm text-slate-600">
                Sudah memiliki akun? 
                <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-800 transition-colors">Masuk di sini</a>
            </p>
        </div>
    </form>
</x-guest-layout>