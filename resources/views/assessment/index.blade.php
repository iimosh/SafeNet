<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Моите проценки
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-2xl p-8">
                <h1 class="text-xl font-bold mb-6">Историја на проценки</h1>

                @forelse($assessments as $assessment)
                    <div class="border rounded-xl p-4 mb-3 flex items-center justify-between">
                        <div>
                            <p class="font-medium">Проценка #{{ $assessment->id }}</p>
                            <p class="text-sm text-gray-500">{{ $assessment->created_at->format('d.m.Y') }}</p>
                            <span class="text-xs px-2 py-1 rounded-full mt-1 inline-block
                                {{ $assessment->risk_level === 'low' ? 'bg-green-100 text-green-700' : '' }}
                                {{ $assessment->risk_level === 'medium' ? 'bg-yellow-100 text-yellow-700' : '' }}
                                {{ $assessment->risk_level === 'high' ? 'bg-red-100 text-red-700' : '' }}">
                                {{ ucfirst($assessment->risk_level) }} risk
                            </span>
                        </div>
                        <a href="{{ route('assessment.show', $assessment) }}"
                           class="text-blue-600 hover:underline text-sm">
                            Погледни
                        </a>
                    </div>
                @empty
                    <p class="text-gray-500">Сè уште немаш направено проценка.</p>
                @endforelse
            </div>
        </div>
    </div>
</x-app-layout>
