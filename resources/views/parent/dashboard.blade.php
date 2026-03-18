<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Parent Dashboard
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">
            <div class="bg-white shadow rounded-2xl p-8">
                <h1 class="text-2xl font-bold mb-3">Добредојде, {{ auth()->user()->name }}</h1>
                <p class="text-gray-600">
                    Од оваа страница можеш да ги следиш проценките на твоите деца.
                </p>
            </div>

            @forelse(auth()->user()->children as $child)
                <div class="bg-white shadow rounded-2xl p-8">
                    <h2 class="text-lg font-semibold mb-1">{{ $child->name }}</h2>
                    <p class="text-sm text-gray-500 mb-4">{{ $child->email }}</p>

                    <h3 class="text-md font-medium mb-3">Проценки</h3>

                    @forelse($child->assessments()->latest()->get() as $assessment)
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
                        <p class="text-gray-500 text-sm">Детето сè уште нема направено проценка.</p>
                    @endforelse
                </div>
            @empty
                <div class="bg-white shadow rounded-2xl p-8">
                    <p class="text-gray-500">Немаш поврзано дете со твојот профил.</p>
                </div>
            @endforelse

            <div class="bg-white shadow rounded-2xl p-8">
                <h2 class="text-lg font-semibold mb-4">Додај друг ученик</h2>

                @if(session('success'))
                    <div class="mb-4 rounded-lg bg-green-100 text-green-700 p-4">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="POST" action="{{ route('parent.add-child') }}">
                    @csrf
                    <div class="flex gap-3 items-start">
                        <div class="flex-1">
                            <x-text-input
                                type="email"
                                name="child_email"
                                placeholder="Внеси е-маил на веќе регистриран ученик"
                                class="w-full"
                                :value="old('child_email')" />
                            <x-input-error :messages="$errors->get('child_email')" class="mt-2" />
                        </div>
                        <button type="submit"
                                class="px-5 py-2 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition">
                            Додај
                        </button>
                    </div>
                </form>
            </div>

        </div>
    </div>
</x-app-layout>
