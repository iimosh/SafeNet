{{--<x-app-layout>--}}
{{--    <x-slot name="header">--}}
{{--        <h2 class="font-semibold text-xl text-gray-800 leading-tight">--}}
{{--            Моите проценки--}}
{{--        </h2>--}}
{{--    </x-slot>--}}

{{--    <div class="py-10">--}}
{{--        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">--}}
{{--            <div class="bg-white shadow rounded-2xl p-8">--}}
{{--                <h1 class="text-xl font-bold mb-6">Историја на проценки</h1>--}}

{{--                @forelse($assessments as $assessment)--}}
{{--                    <div class="border rounded-xl p-4 mb-3 flex items-center justify-between">--}}
{{--                        <div>--}}
{{--                            <p class="font-medium">Проценка #{{ $assessment->id }}</p>--}}
{{--                            <p class="text-sm text-gray-500">{{ $assessment->created_at->format('d.m.Y') }}</p>--}}
{{--                            <span class="text-xs px-2 py-1 rounded-full mt-1 inline-block--}}
{{--                                {{ $assessment->risk_level === 'low' ? 'bg-green-100 text-green-700' : '' }}--}}
{{--                                {{ $assessment->risk_level === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}--}}
{{--                                {{ $assessment->risk_level === 'high' ? 'bg-red-100 text-red-700' : '' }}">--}}
{{--                                {{ ucfirst($assessment->risk_level) }} risk--}}
{{--                            </span>--}}
{{--                        </div>--}}
{{--                        <a href="{{ route('assessment.show', $assessment) }}"--}}
{{--                           class="text-blue-600 hover:underline text-sm">--}}
{{--                            Погледни--}}
{{--                        </a>--}}
{{--                    </div>--}}
{{--                @empty--}}
{{--                    <p class="text-gray-500">Сè уште немаш направено проценка.</p>--}}
{{--                @endforelse--}}
{{--            </div>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</x-app-layout>--}}
<x-app-layout>

    <x-slot name="header">
        <div class="flex items-center justify-between">
            <h2 class="font-bold text-2xl text-slate-800 leading-tight">
                Моите проценки
            </h2>

            <div class="hidden md:flex items-center gap-2 text-sm text-slate-500">
                <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                Историја на резултати
            </div>
        </div>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-cyan-50 py-10">

        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8 space-y-8">

            <!-- HERO -->
            <div class="relative overflow-hidden bg-gradient-to-r from-primary via-secondary to-accent rounded-3xl shadow-2xl p-8 text-white">

                <div class="absolute top-0 right-0 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>

                <div class="absolute bottom-0 left-0 w-72 h-72 bg-cyan-300/20 rounded-full blur-3xl"></div>

                <div class="relative z-10">

                    <h1 class="text-4xl font-black mb-3">
                        Историја на проценки
                    </h1>

                    <p class="text-white/80 text-lg max-w-2xl">
                        Прегледај ги сите претходно пополнети проценки
                        и следи го нивото на дигитален ризик.
                    </p>

                    <div class="mt-8 flex flex-wrap gap-4">

                        <div class="bg-white/15 backdrop-blur-md px-5 py-3 rounded-2xl border border-white/20">
                            <p class="text-sm text-white/70">
                                Вкупно проценки
                            </p>

                            <p class="text-2xl font-bold">
                                {{ $assessments->count() }}
                            </p>
                        </div>

                    </div>

                </div>

            </div>

            <!-- ASSESSMENTS -->
            <div class="space-y-5">

                @forelse($assessments as $assessment)

                    <div class="group bg-white/80 backdrop-blur-xl border border-white/40 shadow-xl rounded-3xl p-6 hover:shadow-2xl transition duration-300">

                        <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-6">

                            <!-- LEFT -->
                            <div>

                                <div class="flex items-center gap-3 mb-3">

                                    <div class="w-12 h-12 rounded-2xl bg-gradient-to-r from-primary to-secondary text-white flex items-center justify-center font-bold text-lg shadow-lg">
                                        {{ $loop->iteration }}
                                    </div>

                                    <div>
                                        <h3 class="text-xl font-bold text-slate-800">
                                            Проценка
                                        </h3>

                                        <p class="text-sm text-slate-500">
                                            {{ $assessment->created_at->format('d.m.Y') }}
                                        </p>
                                    </div>

                                </div>

                                <div class="flex flex-wrap gap-3 mt-4">

                                    <div class="px-4 py-2 rounded-xl bg-slate-100">
                                        <p class="text-xs text-slate-500">
                                            Поени
                                        </p>

                                        <p class="font-bold text-slate-800">
                                            {{ $assessment->total_points }}
                                        </p>
                                    </div>

                                    <div class="px-4 py-2 rounded-xl
                                        {{ $assessment->risk_level === 'low' ? 'bg-green-100 text-green-700' : '' }}
                                        {{ $assessment->risk_level === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                        {{ $assessment->risk_level === 'high' ? 'bg-red-100 text-red-700' : '' }}">

                                        <p class="text-xs opacity-70">
                                            Ризик
                                        </p>

                                        <p class="font-bold capitalize">
                                            {{ $assessment->risk_level }}
                                        </p>

                                    </div>

                                </div>

                            </div>

                            <!-- RIGHT -->
                            <div class="flex flex-wrap gap-3">

                                <a href="{{ route('assessment.show', $assessment) }}"
                                   class="px-6 py-3 bg-gradient-to-r from-primary to-secondary text-white rounded-2xl hover:scale-105 hover:shadow-xl transition duration-300 font-semibold">
                                    Погледни
                                </a>

                                <a href="{{ route('assessment.report', $assessment) }}"
                                   class="px-6 py-3 bg-white border border-slate-200 text-slate-700 rounded-2xl hover:bg-slate-50 transition duration-300 font-semibold">
                                    PDF
                                </a>

                            </div>

                        </div>

                    </div>

                @empty

                    <div class="bg-white/80 backdrop-blur-xl border border-white/40 shadow-xl rounded-3xl p-12 text-center">

                        <div class="w-20 h-20 rounded-full bg-slate-100 mx-auto mb-6 flex items-center justify-center text-4xl">
                            📄
                        </div>

                        <h3 class="text-2xl font-bold text-slate-800 mb-3">
                            Нема проценки
                        </h3>

                        <p class="text-slate-500 max-w-xl mx-auto">
                            Сè уште немаш направено ниту една проценка.
                        </p>

                    </div>

                @endforelse

            </div>

        </div>

    </div>

</x-app-layout>
