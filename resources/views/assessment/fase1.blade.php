<x-app-layout>
    <div class="min-h-screen bg-slate-50 py-5 sm:py-8">
        <div class="mx-auto max-w-5xl px-4 sm:px-6 lg:px-8">
            <div class="mb-5 overflow-hidden rounded-3xl bg-gradient-to-br from-indigo-700 via-blue-700 to-cyan-600 p-5 text-white shadow-lg sm:p-7">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-end sm:justify-between">
                    <div>
                        <span class="inline-flex rounded-full bg-white/15 px-3 py-1 text-xs font-bold uppercase tracking-wider text-blue-50">Tahap 1 dari 4</span>
                        <h1 class="mt-3 text-2xl font-black tracking-tight sm:text-3xl">Profil Minat RIASEC</h1>
                        <p class="mt-2 max-w-2xl text-sm leading-6 text-blue-100">Pilih jawaban yang paling menggambarkan diri Anda. Tidak ada jawaban benar atau salah.</p>
                    </div>
                    <div class="shrink-0 rounded-2xl bg-white/10 px-4 py-3 text-center ring-1 ring-white/20">
                        <p class="text-2xl font-black">{{ $questions->count() }}</p>
                        <p class="text-xs text-blue-100">pertanyaan</p>
                    </div>
                </div>
                <div class="mt-6 grid grid-cols-4 gap-2" aria-label="Progress asesmen">
                    @foreach(['Minat', 'Bakat', 'Lanjutan', 'Final'] as $step => $label)
                        <div>
                            <div class="mb-1.5 h-1.5 rounded-full {{ $step === 0 ? 'bg-white' : 'bg-white/25' }}"></div>
                            <span class="text-[10px] font-semibold sm:text-xs">{{ $step + 1 }}. {{ $label }}</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <form action="{{ route('assessment.storeFase1') }}" method="POST">
                @csrf
                @php $globalIndex = 1; @endphp
                <div class="space-y-4">
                    @foreach($questions as $question)
                        <fieldset class="rounded-2xl border border-slate-200 border-l-4 border-l-blue-500 bg-white p-4 shadow-sm sm:p-6">
                            <div class="w-full rounded-xl border border-blue-100 bg-blue-50/60 px-4 py-3 text-sm font-bold leading-6 text-slate-800 sm:px-5 sm:text-base">
                                <span class="mr-1 text-blue-600">{{ $globalIndex++ }}.</span>
                                <span class="break-words whitespace-pre-line">{{ $question->teks_pertanyaan }}</span>
                            </div>
                            @if($question->gambar)
                                <img src="{{ asset('storage/' . $question->gambar) }}" alt="Ilustrasi pertanyaan" class="mt-4 max-h-56 w-full rounded-xl border border-slate-200 object-contain">
                            @endif
                            <div class="mt-4 grid gap-2 sm:grid-cols-5">
                                @foreach([1=>'Sangat Tidak Setuju', 2=>'Tidak Setuju', 3=>'Netral', 4=>'Setuju', 5=>'Sangat Setuju'] as $nilai => $label)
                                    <label class="group flex min-h-12 cursor-pointer items-center gap-2 rounded-xl border border-slate-200 bg-slate-50 p-3 transition hover:border-blue-400 hover:bg-blue-50 has-[:checked]:border-blue-600 has-[:checked]:bg-blue-50 has-[:checked]:ring-2 has-[:checked]:ring-blue-100">
                                        <input type="radio" name="answers[{{ $question->id }}]" value="{{ $nilai }}" required class="h-5 w-5 shrink-0 border-slate-300 text-blue-600 focus:ring-blue-500">
                                        <span class="text-xs font-semibold leading-4 text-slate-700 sm:text-center">{{ $label }}</span>
                                    </label>
                                @endforeach
                            </div>
                        </fieldset>
                    @endforeach
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="submit" class="w-full rounded-xl bg-blue-600 px-6 py-3.5 text-sm font-bold text-white shadow-lg shadow-blue-600/20 transition hover:bg-blue-700 focus:outline-none focus:ring-4 focus:ring-blue-200 sm:w-auto">
                        Lanjut ke Profil Bakat <span class="ml-1" aria-hidden="true">&rarr;</span>
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-app-layout>
