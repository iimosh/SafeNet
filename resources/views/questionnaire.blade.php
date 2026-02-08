<x-app-layout>
    <div class="max-w-3xl mx-auto p-6">
        <h1 class="text-2xl font-bold">{{ $questionnaire->title }}</h1>
        <p class="text-gray-600 mb-6">{{ $questionnaire->description }}</p>

        @if ($errors->any())
            <div class="bg-red-100 text-red-700 p-3 rounded mb-4">
                {{ $errors->first() }}
            </div>
        @endif

        <form method="POST" action="{{ route('assessment.submit') }}">
            @csrf
            <input type="hidden" name="questionnaire_id" value="{{ $questionnaire->id }}"/>

            @foreach($questionnaire->questions as $q)
                <div class="mb-6 p-4 border rounded">
                    <div class="font-semibold mb-2">{{ $q->question_text }}</div>

                    @foreach($q->options as $opt)
                        <label class="block">
                            <input type="radio"
                                   name="answers[{{ $q->id }}]"
                                   value="{{ $opt->id }}"
                                   class="mr-2"
                                   required>
                            {{ $opt->option_text }}
                        </label>
                    @endforeach
                </div>
            @endforeach

            <button class="bg-blue-600 text-white px-4 py-2 rounded">Submit</button>
        </form>
    </div>
</x-app-layout>
