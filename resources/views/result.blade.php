<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Assessment Result
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-2xl p-8">
                <h1 class="text-2xl font-bold mb-4">Твој резултат</h1>

                <div class="grid md:grid-cols-2 gap-6 mb-8">
                    <div class="p-6 rounded-xl bg-slate-100">
                        <p class="text-gray-500 mb-2">Вкупни поени</p>
                        <p class="text-3xl font-bold">{{ $assessment->total_points }}</p>
                    </div>

                    <div class="p-6 rounded-xl bg-slate-100">
                        <p class="text-gray-500 mb-2">Ниво на ризик</p>
                        <p class="text-3xl font-bold capitalize">{{ $assessment->risk_level }}</p>
                    </div>
                </div>

                <h2 class="text-xl font-semibold mb-4">Распределба по категории</h2>

                <div class="space-y-3 mb-8">

                    <div class="mb-8 space-y-4">
                        @foreach($breakdown as $item)
                            @php
                                $percentage = $item['max_score'] > 0
                                    ? round(($item['score'] / $item['max_score']) * 100)
                                    : 0;
                            @endphp

                            <div>
                                <div class="flex justify-between mb-1">
                                    <span class="font-medium">{{ $item['category_name'] }}</span>
                                    <span class="text-sm text-gray-600">
                    {{ $percentage }}%
                </span>
                                </div>

                                <div class="w-full bg-gray-200 rounded-full h-4">
                                    @php
                                        $color = match($item['risk_level']) {
                                            'low' => '#22c55e',
                                            'medium' => '#eab308',
                                            'high' => '#ef4444',
                                            default => '#9ca3af'
                                        };
                                    @endphp

                                    <div class="w-full bg-gray-200 rounded-full h-4">
                                        <div
                                            class="h-4 rounded-full"
                                            style="width: {{ $percentage }}%; background-color: {{ $color }};">
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    @forelse($breakdown as $item)
                        <div class="border rounded-lg p-4 mb-4">
                            <div class="flex items-center justify-between mb-2">
                                <span class="font-semibold">{{ $item['category_name'] }}</span>
                                <span class="font-bold">
                {{ $item['score'] }} / {{ $item['max_score'] }}
            </span>
                            </div>

                            <div class="mb-2">
                                <span class="text-sm text-gray-500">Ниво на ризик:</span>
                                <span class="font-medium capitalize">{{ $item['risk_level'] }}</span>
                            </div>

                            <div class="text-gray-700 text-sm">
                                {{ $item['recommendation'] }}
                            </div>
                        </div>
                    @empty
                        <p class="text-gray-500">Нема податоци по категории.</p>
                    @endforelse
                </div>
                <div class="rounded-xl bg-blue-50 p-6">
                    <h3 class="font-semibold text-lg mb-3">Глобална препорака</h3>
                    <p class="text-gray-700">
                        {{ $assessment->global_recommendation }}
                    </p>
                </div>

                <div class="mt-6 flex gap-3">
                    <a href="{{ $backRoute }}"
                       class="inline-block px-5 py-3 bg-slate-900 text-white rounded-xl hover:bg-slate-700 transition">
                        Back to dashboard
                    </a>

                    <a href="{{ route('assessment.report', $assessment) }}"
                       class="inline-block px-5 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition">
                        Преземи извештај (PDF)
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
