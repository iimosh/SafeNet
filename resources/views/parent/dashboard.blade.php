<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                Контролен панел за родител
            </h2>

            <div class="hidden md:flex items-center gap-2 text-sm text-slate-500">
                <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                Активен профил
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-cyan-50 py-10">

        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <div class="relative overflow-hidden bg-gradient-to-r from-primary via-secondary to-accent rounded-3xl shadow-2xl p-10 text-white">

                <div class="absolute top-0 right-0 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>

                <div class="relative z-10">
                    <h1 class="text-4xl font-black mb-4">
                        Добредојде, {{ auth()->user()->name }}
                    </h1>

                    <p class="text-white/80 text-lg max-w-3xl leading-relaxed">
                        Следи ги активностите, проценките и дигиталната безбедност
                        на твоите деца преку централизираниот MKSafeNet систем.
                    </p>

                    <div class="flex flex-wrap gap-4 mt-8">

                        <div class="bg-white/15 backdrop-blur-md px-5 py-3 rounded-2xl border border-white/20">
                            <p class="text-sm text-white/70">Вкупно деца</p>
                            <p class="text-2xl font-bold">
                                {{ auth()->user()->children->count() }}
                            </p>
                        </div>

                        <div class="bg-white/15 backdrop-blur-md px-5 py-3 rounded-2xl border border-white/20">
                            <p class="text-sm text-white/70">Вкупно проценки</p>
                            <p class="text-2xl font-bold">
                                {{ auth()->user()->children->sum(fn($child) => $child->assessments->count()) }}
                            </p>
                        </div>

                    </div>
                </div>
            </div>

            @forelse(auth()->user()->children as $child)

                <div class="bg-white/80 backdrop-blur-xl border border-white/40 shadow-xl rounded-3xl overflow-hidden">

                    <div class="bg-gradient-to-r from-slate-900 to-slate-800 p-6 text-white">

                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                            <div class="flex items-center gap-4">

                                <div class="w-14 h-14 rounded-2xl bg-white/10 flex items-center justify-center text-xl font-bold">
                                    {{ strtoupper(substr($child->name, 0, 1)) }}
                                </div>

                                <div>
                                    <h2 class="text-2xl font-bold">
                                        {{ $child->name }}
                                    </h2>

                                    <p class="text-white/70 text-sm">
                                        {{ $child->email }}
                                    </p>
                                </div>


                            </div>

                            @if(auth()->user()->children->count() > 1)

                                <form method="POST" action="{{ route('parent.remove-child', $child) }}">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit"
                                            onclick="return confirm('Сигурно сакаш да го отстраниш {{ $child->name }}?')"
                                            class="px-4 py-2 rounded-xl bg-red-500/20 text-red-200 hover:bg-red-500/30 transition">
                                        Отстрани
                                    </button>
                                </form>

                            @endif

                        </div>

                    </div>

                    <div class="p-8">

                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-slate-800">
                                Проценки
                            </h3>

                            <span class="text-sm text-slate-500">
                                {{ $child->assessments()->count() }} проценки
                            </span>
                        </div>

                        @forelse($child->assessments()->latest()->get() as $assessment)

                            <div class="group bg-gradient-to-r from-slate-50 to-blue-50 border border-slate-100 rounded-2xl p-5 mb-4 hover:shadow-lg transition duration-300">

                                <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                                    <div>
                                        <p class="text-lg font-bold text-slate-800">
                                            Проценка {{ $loop->iteration }}
                                        </p>

                                        <p class="text-sm text-slate-500 mt-1">
                                            {{ $assessment->created_at->format('d.m.Y') }}
                                        </p>
                                    </div>

                                    <div class="flex flex-wrap gap-3">

                                        <a href="{{ route('assessment.show', $assessment) }}"
                                           class="px-5 py-2.5 bg-primary text-white rounded-xl hover:scale-105 transition duration-300 shadow">
                                            Погледни
                                        </a>

                                        <a href="{{ route('assessment.report', $assessment) }}"
                                           class="px-5 py-2.5 bg-accent text-slate-900 font-semibold rounded-xl hover:scale-105 transition duration-300 shadow">
                                            PDF Report
                                        </a>

                                    </div>

                                </div>

                            </div>

                        @empty

                            <div class="bg-slate-50 border border-dashed border-slate-200 rounded-2xl p-6 text-center text-slate-500">
                                Детето сè уште нема направено проценка.
                            </div>

                        @endforelse

                        <div class="mt-8">
                            <a href="{{ route('questionnaires.index', ['child_id' => $child->id]) }}"
                               class="inline-flex items-center gap-2 px-6 py-3 bg-gradient-to-r from-primary to-secondary text-white rounded-2xl hover:shadow-xl hover:scale-105 transition duration-300">

                                Започни прашалник
                            </a>
                        </div>

                    </div>

                </div>

            @empty

                <div class="bg-white/80 backdrop-blur-xl border border-white/40 shadow-xl rounded-3xl p-12 text-center">

                    <div class="w-20 h-20 rounded-full bg-blue-100 mx-auto mb-6 flex items-center justify-center">
                        <svg xmlns="http://www.w3.org/2000/svg"
                             class="h-10 w-10 text-blue-600"
                             fill="none"
                             viewBox="0 0 24 24"
                             stroke="currentColor">
                            <path stroke-linecap="round"
                                  stroke-linejoin="round"
                                  stroke-width="2"
                                  d="M17 20h5V4H2v16h5m10 0v-4a3 3 0 00-3-3H10a3 3 0 00-3 3v4m10 0H7"/>
                        </svg>
                    </div>

                    <h3 class="text-2xl font-bold text-slate-800 mb-3">
                        Нема поврзани ученици
                    </h3>

                    <p class="text-slate-500 max-w-xl mx-auto">
                        Додај ученик преку е-маил адреса за да можеш да ги следиш
                        неговите проценки и активности.
                    </p>

                </div>

            @endforelse

            <div class="relative overflow-hidden bg-gradient-to-r from-primary via-secondary to-accent rounded-3xl shadow-2xl p-8 text-white">

                <div class="absolute top-0 right-0 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>

                <div class="relative z-10">

                    <div class="mb-6">

                        <h2 class="text-3xl font-black mb-2">
                            Додај ученик
                        </h2>

                        <p class="text-white/80 max-w-2xl">
                            Поврзи постоечки ученик преку неговата е-маил адреса
                            за да можеш да ги следиш неговите проценки.
                        </p>

                    </div>

                    @if(session('success'))

                        <div class="mb-6 bg-emerald-400/20 border border-emerald-300/20 text-white rounded-2xl p-4 backdrop-blur-md">
                            {{ session('success') }}
                        </div>

                    @endif

                    <form method="POST" action="{{ route('parent.add-child') }}">

                        @csrf

                        <div class="flex flex-col lg:flex-row gap-4">

                            <div class="flex-1">

                                <x-text-input
                                    type="email"
                                    name="child_email"
                                    placeholder="Внеси е-маил адреса"
                                    class="w-full rounded-2xl border-white/20 bg-white/10
                               backdrop-blur-md text-white placeholder:text-white/60
                               focus:border-white focus:ring-white py-4"
                                    :value="old('child_email')" />

                                <x-input-error :messages="$errors->get('child_email')" class="mt-2 text-red-200" />

                            </div>

                            <button type="submit"
                                    class="px-8 py-4 bg-white text-slate-900 font-bold rounded-2xl
                               hover:scale-105 hover:shadow-2xl transition duration-300">

                                Додај ученик

                            </button>

                        </div>

                    </form>

                </div>

            </div>
        </div>

    </div>
</x-app-layout>
