<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Dashboard
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow rounded-2xl p-8">
                <h1 class="text-2xl font-bold mb-3">Добредојде, {{ auth()->user()->name }}</h1>
                <p class="text-gray-600 mb-6">
                    Од оваа страница можеш да ја започнеш проценката на дигитални ризици
                    и да ги видиш твоите резултати.
                </p>

                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('questionnaires.index') }}"
                       class="inline-block px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition">
                        Прашалници
                    </a>

                    <a href="{{ route('assessment.index') }}"
                       class="inline-block px-6 py-3 bg-indigo-600 text-white rounded-xl hover:bg-indigo-700 transition">
                        Моите проценки
                    </a>
                </div>
            </div>

            <!-- Previous assessments -->
            <div class="bg-white shadow rounded-2xl p-8">
                <h2 class="text-lg font-semibold mb-4">Претходни проценки</h2>

                @forelse(auth()->user()->assessments()->latest()->get() as $assessment)
                    <div class="border rounded-lg p-4 mb-3 flex items-center justify-between">
                        <div>
                            <p class="font-medium">Проценка #{{ $assessment->id }}</p>
                            <p class="text-sm text-gray-500">{{ $assessment->created_at->format('d.m.Y') }}</p>
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
