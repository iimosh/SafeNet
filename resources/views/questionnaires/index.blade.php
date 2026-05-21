<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>
                <h2 class="font-black text-2xl text-slate-800 leading-tight">
                    Достапни прашалници
                </h2>

                <p class="text-slate-500 text-sm mt-1">
                    MKSafeNet безбедносни проценки
                </p>
            </div>

            <div class="hidden md:flex items-center gap-2 text-sm text-slate-500">
                <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                Активен систем
            </div>

        </div>

    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-cyan-50 py-10">

        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8">

            <!-- HERO -->
            <div class="relative overflow-hidden bg-gradient-to-r from-primary via-secondary to-accent rounded-3xl shadow-2xl p-10 text-white mb-8">

                <div class="absolute top-0 right-0 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>

                <div class="absolute bottom-0 left-0 w-72 h-72 bg-cyan-300/20 rounded-full blur-3xl"></div>

                <div class="relative z-10">

                    <h1 class="text-4xl font-black mb-4">
                        Безбедносни прашалници
                    </h1>

                    <p class="text-white/80 text-lg max-w-3xl leading-relaxed">
                        Избери прашалник и започни проценка на дигиталните ризици,
                        онлајн навиките и безбедноста.
                    </p>

                </div>

            </div>

            <div class="space-y-6">

                @forelse($questionnaires as $questionnaire)

                    <div class="group bg-white/80 backdrop-blur-xl border border-white/40 shadow-xl rounded-3xl p-8 hover:shadow-2xl hover:-translate-y-1 transition duration-300">

                        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between gap-6">

                            <!-- LEFT -->
                            <div class="flex items-start gap-5">

                                <div class="w-16 h-16 rounded-2xl bg-gradient-to-r from-primary to-secondary
                                            flex items-center justify-center text-white text-2xl font-black shadow-lg">

                                    {{ $loop->iteration }}

                                </div>

                                <div>

                                    <h3 class="text-2xl font-black text-slate-800 mb-2">
                                        {{ $questionnaire->title }}
                                    </h3>

                                    <p class="text-slate-500 leading-relaxed max-w-2xl">
                                        {{ $questionnaire->description }}
                                    </p>

                                </div>

                            </div>

                            <!-- RIGHT -->
                            <div class="flex items-center">

                                @if(in_array($questionnaire->id, $completedIds))

                                    <div class="px-5 py-3 bg-emerald-100 text-emerald-700 rounded-2xl font-semibold border border-emerald-200 shadow-sm">
                                        ✓ Завршено
                                    </div>

                                @else

                                    @if(auth()->user()->isParent())

                                        <a href="{{ route('assessment.start', ['questionnaire_id' => $questionnaire->id, 'child_id' => $childId]) }}"
                                           class="inline-flex items-center gap-2 px-6 py-3
                                                  bg-gradient-to-r from-primary to-secondary
                                                  text-white rounded-2xl font-semibold
                                                  hover:scale-105 hover:shadow-xl
                                                  transition duration-300">

                                            Започни проценка

                                        </a>

                                    @else

                                        <a href="{{ route('assessment.start', ['questionnaire_id' => $questionnaire->id]) }}"
                                           class="inline-flex items-center gap-2 px-6 py-3
                                                  bg-gradient-to-r from-primary to-secondary
                                                  text-white rounded-2xl font-semibold
                                                  hover:scale-105 hover:shadow-xl
                                                  transition duration-300">

                                            Започни проценка

                                        </a>

                                    @endif

                                @endif

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
                                      d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>

                            </svg>

                        </div>

                        <h3 class="text-2xl font-black text-slate-800 mb-3">
                            Нема достапни прашалници
                        </h3>

                        <p class="text-slate-500 max-w-xl mx-auto">
                            Во моментов нема активни прашалници за пополнување.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </div>

</x-app-layout>
