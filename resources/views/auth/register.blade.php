<x-guest-layout>
    <div class="mb-8">
        <p class="text-sm font-bold text-indigo-600">Mulai perjalanan Anda</p>
        <h2 class="mt-1 text-2xl font-black tracking-tight text-slate-900 sm:text-3xl">Buat akun baru</h2>
        <p class="mt-2 text-sm leading-6 text-slate-500">Daftarkan diri untuk mengikuti asesmen Program Keahlian.</p>
    </div>

    <form method="POST" action="{{ route('register') }}" class="space-y-4">
        @csrf
        <div>
            <label for="name" class="mb-2 block text-sm font-bold text-slate-700">Nama lengkap</label>
            <input id="name" class="block min-h-12 w-full rounded-xl border-slate-200 bg-slate-50 px-4 text-sm shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-100" type="text" name="name" :value="old('name')" required autofocus autocomplete="name" placeholder="Masukkan nama lengkap">
            <x-input-error :messages="$errors->get('name')" class="mt-2" />
        </div>
        <div>
            <label for="email" class="mb-2 block text-sm font-bold text-slate-700">Alamat email</label>
            <input id="email" class="block min-h-12 w-full rounded-xl border-slate-200 bg-slate-50 px-4 text-sm shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-100" type="email" name="email" :value="old('email')" required autocomplete="username" placeholder="nama@email.com">
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>
        <div class="grid gap-4 sm:grid-cols-2">
            <div>
                <label for="password" class="mb-2 block text-sm font-bold text-slate-700">Kata sandi</label>
                <input id="password" class="block min-h-12 w-full rounded-xl border-slate-200 bg-slate-50 px-4 text-sm shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-100" type="password" name="password" required autocomplete="new-password" placeholder="Min. 8 karakter">
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>
            <div>
                <label for="password_confirmation" class="mb-2 block text-sm font-bold text-slate-700">Konfirmasi sandi</label>
                <input id="password_confirmation" class="block min-h-12 w-full rounded-xl border-slate-200 bg-slate-50 px-4 text-sm shadow-sm transition placeholder:text-slate-400 focus:border-indigo-500 focus:bg-white focus:ring-4 focus:ring-indigo-100" type="password" name="password_confirmation" required autocomplete="new-password" placeholder="Ulangi kata sandi">
                <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
            </div>
        </div>
        <button type="submit" class="mt-2 inline-flex min-h-12 w-full items-center justify-center rounded-xl bg-indigo-600 px-6 text-sm font-bold text-white shadow-lg shadow-indigo-600/20 transition hover:-translate-y-0.5 hover:bg-indigo-700 focus:outline-none focus:ring-4 focus:ring-indigo-200">Buat Akun</button>
    </form>
    <p class="mt-7 border-t border-slate-100 pt-6 text-center text-sm text-slate-600">Sudah memiliki akun? <a href="{{ route('login') }}" class="font-bold text-indigo-600 hover:text-indigo-800">Masuk di sini</a></p>
</x-guest-layout>
