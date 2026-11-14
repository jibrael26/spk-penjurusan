@if (session('success') || session('error') || session('status'))
    @php
        $isError = session('error');
        $message = session('error') ?? session('success') ?? session('status');
    @endphp

    <style>[x-cloak] { display: none !important; }</style>

    <div
        x-data="{ visible: true }"
        x-init="setTimeout(() => visible = false, 4500)"
        x-cloak
        x-show="visible"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 translate-y-3 scale-95"
        x-transition:enter-end="opacity-100 translate-y-0 scale-100"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
        class="fixed right-4 top-4 z-[100] w-[calc(100%-2rem)] max-w-sm overflow-hidden rounded-2xl border bg-white shadow-2xl sm:right-6 sm:top-6 {{ $isError ? 'border-rose-200' : 'border-emerald-200' }}"
        role="alert"
        aria-live="polite"
    >
        <div class="flex items-start gap-3 p-4">
            <div class="mt-0.5 flex h-9 w-9 shrink-0 items-center justify-center rounded-xl {{ $isError ? 'bg-rose-100 text-rose-600' : 'bg-emerald-100 text-emerald-600' }}">
                @if ($isError)
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M12 9v3.75m0 3.75h.008M10.29 3.86l-7.2 12.48A1.5 1.5 0 004.39 18.6h15.22a1.5 1.5 0 001.3-2.26l-7.2-12.48a1.5 1.5 0 00-2.6 0z"></path></svg>
                @else
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M5 13l4 4L19 7"></path></svg>
                @endif
            </div>
            <div class="min-w-0 flex-1">
                <p class="text-sm font-bold {{ $isError ? 'text-rose-700' : 'text-emerald-700' }}">{{ $isError ? 'Terjadi kesalahan' : 'Berhasil' }}</p>
                <p class="mt-0.5 break-words text-sm text-slate-600">{{ $message }}</p>
            </div>
            <button type="button" @click="visible = false" class="rounded-lg p-1 text-slate-400 transition hover:bg-slate-100 hover:text-slate-700" aria-label="Tutup notifikasi">
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="M6 18L18 6M6 6l12 12"></path></svg>
            </button>
        </div>
        <div class="h-1 {{ $isError ? 'bg-rose-500' : 'bg-emerald-500' }}" x-show="visible"></div>
    </div>
@endif
