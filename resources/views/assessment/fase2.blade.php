<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-5 sm:py-8">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="mb-5 overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-700 via-blue-700 to-cyan-600 p-5 text-white shadow-lg sm:p-7">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <span class="inline-flex rounded-full bg-white/15 px-3 py-1 text-xs font-bold uppercase tracking-wider text-blue-50">Tahap 2 dari 4</span>
                        <h1 class="mt-3 text-2xl font-black tracking-tight sm:text-3xl">Profil Bakat Dasar CHC</h1>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-blue-100">Pilih satu jawaban yang paling tepat untuk setiap pertanyaan.</p>
                    </div>
                    <div class="shrink-0 rounded-2xl bg-white/10 px-4 py-3 text-center ring-1 ring-white/20">
                        <p class="text-2xl font-black">{{ $questions->count() }}</p><p class="text-xs text-blue-100">pertanyaan</p>
                    </div>
                </div>
                <div class="mt-6 grid grid-cols-4 gap-2" aria-label="Progress asesmen">
                    @foreach(['Minat', 'Bakat', 'Lanjutan', 'Final'] as $step => $label)
                        <div><div class="mb-1.5 h-1.5 rounded-full {{ $step <= 1 ? 'bg-white' : 'bg-white/25' }}"></div><span class="text-[10px] font-semibold sm:text-xs">{{ $step + 1 }}. {{ $label }}</span></div>
                    @endforeach
                </div>
            </div>

            <form action="{{ route('assessment.storeFase2') }}" method="POST">
                @csrf
                @php $globalIndex = 1; $hurufOpsi = [1 => 'A', 2 => 'B', 3 => 'C', 4 => 'D', 5 => 'E']; @endphp
                <div class="space-y-4">
                    @foreach($questions as $question)
                        <fieldset class="rounded-2xl border border-slate-200 border-l-4 border-l-indigo-500 bg-white p-4 shadow-sm sm:p-6">
                            <div class="w-full rounded-xl border border-indigo-100 bg-indigo-50/60 px-4 py-3 text-sm font-bold leading-6 text-slate-800 sm:px-5 sm:text-base">
                                <span class="mr-1 text-indigo-600">{{ $globalIndex++ }}.</span>
                                <span class="break-words whitespace-pre-line">{{ $question->teks_pertanyaan }}</span>
                            </div>
                            @if($question->gambar)<img src="{{ asset('storage/' . $question->gambar) }}" alt="Ilustrasi pertanyaan" class="mt-4 max-h-56 w-full rounded-xl border border-slate-200 object-contain">@endif
                            <div class="mt-4 space-y-2">
                                @foreach($question->opsi_jawaban ?? [] as $key => $kontenOpsi)
                                    <label class="flex min-h-12 cursor-pointer items-center gap-3 rounded-xl border border-slate-200 bg-slate-50 p-3 transition hover:border-indigo-400 hover:bg-indigo-50 has-[:checked]:border-indigo-600 has-[:checked]:bg-indigo-50 has-[:checked]:ring-2 has-[:checked]:ring-indigo-100">
                                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $key }}" required class="h-5 w-5 border-slate-300 text-indigo-600 focus:ring-indigo-500">
                                        <span class="flex h-7 w-7 shrink-0 items-center justify-center rounded-lg bg-white text-xs font-black text-indigo-700 ring-1 ring-slate-200">{{ $hurufOpsi[$key] ?? $key }}</span>
                                        <span class="text-sm leading-5 text-slate-700">{{ $kontenOpsi }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>
                    @endforeach
                </div>
                <div class="mt-6 flex flex-col-reverse gap-3 sm:flex-row sm:justify-between">
                    <a href="{{ route('assessment.fase1') }}" class="inline-flex justify-center rounded-xl border border-slate-300 bg-white px-6 py-3.5 text-sm font-bold text-slate-700 transition hover:bg-slate-100">&larr; Kembali</a>
                    <button type="submit" class="rounded-xl bg-blue-600 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200">Lanjut ke Fase 3 <span class="ml-1" aria-hidden="true">&rarr;</span></button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
