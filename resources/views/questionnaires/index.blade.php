<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Достапни прашалници
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8 space-y-4">

            @forelse($questionnaires as $questionnaire)
                <div class="bg-white shadow rounded-2xl p-6 flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold">{{ $questionnaire->title }}</h3>
                        <p class="text-sm text-gray-500 mt-1">{{ $questionnaire->description }}</p>
                    </div>

                    @if(in_array($questionnaire->id, $completedIds))
                        <span class="px-4 py-2 bg-gray-100 text-gray-500 rounded-xl text-sm">
                            ✓ Завршено
                        </span>
                    @else
                        @if(auth()->user()->isParent())
                            <a href="{{ route('assessment.start', ['questionnaire_id' => $questionnaire->id, 'child_id' => $childId]) }}"
                               class="px-5 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition text-sm">
                                Започни
                            </a>
                        @else
                            <a href="{{ route('assessment.start', ['questionnaire_id' => $questionnaire->id]) }}"
                               class="px-5 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition text-sm">
                                Започни
                            </a>
                        @endif
                    @endif
                </div>
            @empty
                <div class="bg-white shadow rounded-2xl p-6">
                    <p class="text-gray-500">Нема достапни прашалници во моментов.</p>
                </div>
            @endforelse

        </div>
    </div>
</x-app-layout>
