<x-app-layout>

    <x-slot name="header">

        <div class="flex items-center justify-between">

            <div>
                <h2 class="font-black text-2xl text-slate-800 leading-tight">
                    Резултат од проценка
                </h2>

                <p class="text-slate-500 text-sm mt-1">
                    MKSafeNet анализа на дигитална безбедност
                </p>
            </div>

            <div class="hidden md:flex items-center gap-2 text-sm text-slate-500">
                <div class="w-2 h-2 rounded-full bg-emerald-400"></div>
                Анализа завршена
            </div>

        </div>

    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-cyan-50 py-10">

        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-8">


            <div class="relative overflow-hidden bg-gradient-to-r from-primary via-secondary to-accent rounded-3xl shadow-2xl p-10 text-white">

                <div class="absolute top-0 right-0 w-72 h-72 bg-white/10 rounded-full blur-3xl"></div>

                <div class="absolute bottom-0 left-0 w-72 h-72 bg-cyan-300/20 rounded-full blur-3xl"></div>

                <div class="relative z-10">

                    <h1 class="text-4xl font-black mb-4">
                        Твој резултат
                    </h1>

                    <p class="text-white/80 text-lg max-w-3xl leading-relaxed">
                        Преглед на ризиците, категориите и препораките
                        добиени од безбедносната проценка.
                    </p>

                </div>

            </div>


            <div class="grid md:grid-cols-2 gap-6">

                <div class="bg-white/80 backdrop-blur-xl border border-white/40 shadow-xl rounded-3xl p-8">

                    <p class="text-slate-500 mb-3 font-medium">
                        Вкупни поени
                    </p>

                    <div class="flex items-end gap-3">

                        <p class="text-5xl font-black text-slate-800">
                            {{ $assessment->total_points }}
                        </p>

                        <span class="text-slate-400 mb-2">
                            points
                        </span>

                    </div>

                </div>

                <div class="bg-white/80 backdrop-blur-xl border border-white/40 shadow-xl rounded-3xl p-8">

                    <p class="text-slate-500 mb-3 font-medium">
                        Ниво на ризик
                    </p>

                    @php
                        $riskClasses = match($assessment->risk_level) {
                            'low' => 'bg-emerald-100 text-emerald-700 border-emerald-200',
                            'medium' => 'bg-yellow-100 text-yellow-700 border-yellow-200',
                            'high' => 'bg-red-100 text-red-700 border-red-200',
                            default => 'bg-slate-100 text-slate-700 border-slate-200'
                        };
                    @endphp

                    <div class="inline-flex items-center px-5 py-3 rounded-2xl border font-bold text-xl {{ $riskClasses }}">

                        {{ ucfirst($assessment->risk_level) }}

                    </div>

                </div>

            </div>

            <div class="bg-white/80 backdrop-blur-xl border border-white/40 shadow-xl rounded-3xl p-8">

                <div class="mb-8">

                    <h2 class="text-2xl font-black text-slate-800 mb-2">
                        Анализа по категории
                    </h2>

                    <p class="text-slate-500">
                        Детален преглед на ризиците по области.
                    </p>

                </div>

                <div class="space-y-6">

                    @forelse($breakdown as $item)

                        @php
                            $percentage = $item['max_score'] > 0
                                ? round(($item['score'] / $item['max_score']) * 100)
                                : 0;

                            $barColor = match($item['risk_level']) {
                                'low' => 'from-emerald-400 to-emerald-500',
                                'medium' => 'from-yellow-400 to-yellow-500',
                                'high' => 'from-red-400 to-red-500',
                                default => 'from-slate-400 to-slate-500'
                            };

                            $badgeClass = match($item['risk_level']) {
                                'low' => 'bg-emerald-100 text-emerald-700',
                                'medium' => 'bg-yellow-100 text-yellow-700',
                                'high' => 'bg-red-100 text-red-700',
                                default => 'bg-slate-100 text-slate-700'
                            };
                        @endphp

                        <div class="bg-gradient-to-r from-slate-50 to-blue-50 border border-slate-100 rounded-3xl p-6">

                            <!-- TOP -->
                            <div class="flex flex-col md:flex-row md:items-center md:justify-between gap-4 mb-5">

                                <div>

                                    <h3 class="text-xl font-bold text-slate-800">
                                        {{ $item['category_name'] }}
                                    </h3>

                                    <p class="text-slate-500 text-sm mt-1">
                                        {{ $item['score'] }} / {{ $item['max_score'] }} поени
                                    </p>

                                </div>

                                <div class="flex items-center gap-3">

                                    <span class="text-lg font-black text-slate-800">
                                        {{ $percentage }}%
                                    </span>

                                    <div class="px-4 py-2 rounded-xl text-sm font-semibold {{ $badgeClass }}">
                                        {{ ucfirst($item['risk_level']) }} risk
                                    </div>

                                </div>

                            </div>

                            <!-- PROGRESS -->
                            <div class="w-full bg-slate-200 rounded-full h-4 overflow-hidden mb-5">

                                <div class="h-4 rounded-full bg-gradient-to-r {{ $barColor }}"
                                     style="width: {{ $percentage }}%">
                                </div>

                            </div>

                            <div class="bg-white rounded-2xl p-5 border border-slate-100">

                                <p class="text-sm font-semibold text-slate-500 mb-2">
                                    Препорака
                                </p>

                                <p class="text-slate-700 leading-relaxed">
                                    {{ $item['recommendation'] }}
                                </p>

                            </div>

                        </div>

                    @empty

                        <div class="bg-slate-50 border border-dashed border-slate-200 rounded-2xl p-8 text-center text-slate-500">

                            Нема податоци по категории.

                        </div>

                    @endforelse

                </div>

            </div>


            <div class="relative overflow-hidden bg-gradient-to-r from-slate-900 to-slate-800 rounded-3xl shadow-2xl p-8 text-white">

                <div class="absolute top-0 right-0 w-72 h-72 bg-white/5 rounded-full blur-3xl"></div>

                <div class="relative z-10">

                    <h3 class="text-2xl font-black mb-4">
                        Глобална препорака
                    </h3>

                    <p class="text-white/80 leading-relaxed text-lg max-w-4xl">
                        {{ $assessment->global_recommendation }}
                    </p>

                </div>

            </div>

            <div class="flex flex-col sm:flex-row gap-4">

                <a href="{{ $backRoute }}"
                   class="inline-flex items-center justify-center px-6 py-4 bg-slate-900 text-white rounded-2xl hover:bg-slate-700 transition duration-300 shadow-lg">

                    Назад

                </a>

                <a href="{{ route('assessment.report', $assessment) }}"
                   class="inline-flex items-center justify-center px-6 py-4 bg-gradient-to-r from-emerald-500 to-emerald-600 text-white rounded-2xl hover:scale-105 hover:shadow-xl transition duration-300 font-semibold">

                    Преземи PDF извештај

                </a>

            </div>

        </div>

    </div>

</x-app-layout>
