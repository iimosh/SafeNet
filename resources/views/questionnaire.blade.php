<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>
                <h2 class="font-black text-2xl text-slate-800 leading-tight">
                    {{ $questionnaire->title }}
                </h2>

                <p class="text-slate-500 text-sm mt-1">
                    MKSafeNet безбедносен прашалник
                </p>
            </div>

            <div class="hidden md:flex items-center gap-2 text-sm text-slate-500">
                <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                Активна проценка
            </div>

        </div>

    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-cyan-50 py-10">

        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">

            <div class="relative overflow-hidden bg-gradient-to-r from-primary via-secondary to-accent rounded-3xl shadow-2xl p-10 text-white mb-8">

                <div class="absolute top-0 right-0 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>

                <div class="absolute bottom-0 left-0 w-72 h-72 bg-cyan-300/20 rounded-full blur-3xl"></div>

                <div class="relative z-10">

                    <h1 class="text-4xl font-black mb-4">
                        {{ $questionnaire->title }}
                    </h1>

                    <p class="text-white/80 text-lg leading-relaxed max-w-3xl">
                        {{ $questionnaire->description }}
                    </p>

                </div>

            </div>

            <div class="bg-white/80 backdrop-blur-xl border border-white/40 shadow-xl rounded-3xl p-8">

                @if ($errors->any())

                    <div class="mb-6 rounded-2xl bg-red-100 border border-red-200 text-red-700 p-5">
                        {{ $errors->first() }}
                    </div>

                @endif

                <form method="POST" action="{{ route('assessment.submit') }}">

                    @csrf

                    <input type="hidden"
                           name="questionnaire_id"
                           value="{{ $questionnaire->id }}">

                    <input type="hidden"
                           name="filled_for_user_id"
                           value="{{ $selectedChildId }}">

                    <div class="space-y-10">

                        @foreach($questionnaire->categories as $category)

                            <div class="bg-gradient-to-r from-slate-50 to-blue-50 border border-slate-100 rounded-3xl p-8 shadow-sm">

                                <div class="mb-8">

                                    <div class="flex items-center gap-4 mb-3">

                                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-r from-primary to-secondary
                                                    text-white flex items-center justify-center font-bold shadow-lg">

                                            {{ $loop->iteration }}

                                        </div>

                                        <div>

                                            <h2 class="text-2xl font-black text-slate-800">
                                                {{ $category->name }}
                                            </h2>

                                            @if($category->description)

                                                <p class="text-slate-500 mt-1">
                                                    {{ $category->description }}
                                                </p>

                                            @endif

                                        </div>

                                    </div>

                                </div>

                                <div class="space-y-8">

                                    @foreach($category->questions as $question)

                                        <div class="bg-white border border-slate-100 rounded-2xl p-6 shadow-sm">

                                            <h3 class="font-bold text-lg text-slate-800 mb-5 leading-relaxed">
                                                {{ $question->question_text }}
                                            </h3>

                                            <div class="space-y-3">

                                                @foreach($question->options as $option)

                                                    <label class="group flex items-center gap-4 cursor-pointer border border-slate-200 rounded-2xl px-5 py-4 hover:border-primary hover:bg-blue-50 transition duration-200">

                                                        <input type="radio"
                                                               name="answers[{{ $question->id }}]"
                                                               value="{{ $option->id }}"
                                                               class="w-5 h-5 text-primary border-slate-300 focus:ring-primary"
                                                               required>

                                                        <span class="text-slate-700 group-hover:text-slate-900 font-medium">
                                                            {{ $option->option_text }}
                                                        </span>

                                                    </label>

                                                @endforeach

                                            </div>

                                        </div>

                                    @endforeach

                                </div>

                            </div>

                        @endforeach

                    </div>


                    <div class="mt-10 flex justify-end">

                        <button type="submit"
                                class="inline-flex items-center gap-3 px-8 py-4
                                       bg-gradient-to-r from-primary to-secondary
                                       text-white rounded-2xl font-bold shadow-xl
                                       hover:scale-105 hover:shadow-2xl
                                       transition duration-300">

                            Заврши проценка

                        </button>

                    </div>

                </form>

            </div>

        </div>

    </div>

</x-app-layout>
