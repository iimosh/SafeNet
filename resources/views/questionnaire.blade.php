<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            {{ $questionnaire->title }}
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-2xl p-8">
                <p class="text-gray-600 mb-6">{{ $questionnaire->description }}</p>

                @if ($errors->any())
                    <div class="mb-4 rounded-lg bg-red-100 text-red-700 p-4">
                        {{ $errors->first() }}
                    </div>
                @endif

                <form method="POST" action="{{ route('assessment.submit') }}">
                    @csrf
                    <input type="hidden" name="questionnaire_id" value="{{ $questionnaire->id }}">

                    <div class="space-y-8">
                        @foreach($questionnaire->questions as $question)
                            <div class="border rounded-xl p-5">
                                <h3 class="font-semibold text-lg mb-4">
                                    {{ $loop->iteration }}. {{ $question->question_text }}
                                </h3>

                                <div class="space-y-3">
                                    @foreach($question->options as $option)
                                        <label class="flex items-center gap-3 cursor-pointer">
                                            <input type="radio"
                                                   name="answers[{{ $question->id }}]"
                                                   value="{{ $option->id }}"
                                                   required>
                                            <span>{{ $option->option_text }}</span>
                                        </label>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-8">
                        <button type="submit"
                                class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition">
                            Submit Assessment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
