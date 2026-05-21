<x-app-layout>
    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                Контролен панел
            </h2>

            <div class="hidden md:flex items-center gap-2 text-sm text-slate-500">
                <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                Активен профил
            </div>
        </div>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-gradient-to-r from-primary to-secondary text-white shadow-xl rounded-2xl p-8">

                <h1 class="text-3xl font-bold mb-3">
                    Добредојде, {{ auth()->user()->name }}
                </h1>

                <p class="text-white/80 mb-6 max-w-2xl">
                    Од оваа страница можеш да ја започнеш проценката на дигитални ризици
                    и да ги видиш твоите резултати.
                </p>

                <div class="flex flex-wrap gap-4">

                    <a href="{{ route('questionnaires.index') }}"
                       class="inline-block px-6 py-3 bg-white/15 backdrop-blur-md border border-white/20 text-white rounded-xl hover:bg-white/25 transition duration-300 shadow-md">
                        Прашалници
                    </a>

                    <a href="{{ route('assessment.index') }}"
                       class="inline-block px-6 py-3 bg-accent text-slate-900 font-semibold rounded-xl hover:scale-105 hover:shadow-lg transition duration-300">
                        Моите проценки
                    </a>

                </div>

            </div>


            <div class="bg-white/70 backdrop-blur-xl border border-white/40 shadow-xl rounded-3xl overflow-hidden">


                <div class="bg-gradient-to-r from-slate-900 to-slate-800 px-8 py-5 text-white">

                    <div class="flex items-center justify-between">

                        <div>
                            <h2 class="text-2xl font-bold">
                                Претходни проценки
                            </h2>

                            <p class="text-white/70 text-sm mt-1">
                                Историја на твоите дигитални проценки
                            </p>
                        </div>

                        <div class="hidden md:flex items-center gap-2 bg-white/10 px-4 py-2 rounded-2xl border border-white/10">
                            <div class="w-2 h-2 rounded-full bg-emerald-400"></div>

                            <span class="text-sm">
                    {{ auth()->user()->assessments()->count() }} записи
                </span>
                        </div>

                    </div>

                </div>

                <div class="p-8">

                    @forelse(auth()->user()->assessments()->latest()->get() as $assessment)

                        <div class="group bg-gradient-to-r from-slate-50 to-blue-50 border border-slate-100 rounded-2xl p-5 mb-4 hover:shadow-lg hover:-translate-y-1 transition duration-300">

                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4">

                                <div class="flex items-center gap-4">

                                    <div class="w-14 h-14 rounded-2xl bg-gradient-to-r from-primary to-secondary
                                    flex items-center justify-center text-white font-bold shadow-lg">

                                        {{ $loop->iteration }}

                                    </div>

                                    <div>
                                        <p class="text-lg font-bold text-slate-800">
                                            Проценка
                                        </p>

                                        <p class="text-sm text-slate-500 mt-1">
                                            {{ $assessment->created_at->format('d.m.Y') }}
                                        </p>
                                    </div>

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

                        <div class="bg-slate-50 border border-dashed border-slate-200 rounded-3xl p-10 text-center">

                            <div class="w-20 h-20 rounded-full bg-blue-100 mx-auto mb-5 flex items-center justify-center">
                                <svg xmlns="http://www.w3.org/2000/svg"
                                     class="h-10 w-10 text-blue-600"
                                     fill="none"
                                     viewBox="0 0 24 24"
                                     stroke="currentColor">

                                    <path stroke-linecap="round"
                                          stroke-linejoin="round"
                                          stroke-width="2"
                                          d="M9 17v-2a4 4 0 014-4h4"/>

                                </svg>
                            </div>

                            <h3 class="text-xl font-bold text-slate-800 mb-2">
                                Нема проценки
                            </h3>

                            <p class="text-slate-500">
                                Сè уште немаш направено проценка.
                            </p>

                        </div>

                    @endforelse

                </div>

            </div>
        </div>
    </div>
</x-app-layout>
