<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Одбери дете
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-2xl p-8">
                <h1 class="text-xl font-bold mb-6">За кое дете го пополнуваш прашалникот?</h1>

                <div class="space-y-3">
                    @foreach($children as $child)
                        <a href="{{ route('assessment.start', ['child_id' => $child->id]) }}"
                           class="block border rounded-xl p-4 hover:border-blue-500 hover:bg-blue-50 transition">
                            <p class="font-medium">{{ $child->name }}</p>
                            <p class="text-sm text-gray-500">{{ $child->email }}</p>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
