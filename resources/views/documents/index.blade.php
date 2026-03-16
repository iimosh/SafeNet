
<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
Моите документи
</h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-6xl mx-auto sm:px-6 lg:px-8 space-y-6">

@if(session('success'))
    <div class="bg-green-100 text-green-700 p-4 rounded-xl">
        {{ session('success') }}
    </div>
@endif

<div class="bg-white shadow rounded-2xl p-8">
    <h3 class="text-xl font-bold mb-4">Прикачи документ</h3>

    <form action="{{ route('documents.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        <div>
            <label class="block font-medium mb-1">Наслов</label>
            <input type="text" name="title" class="w-full border rounded-xl px-4 py-3" required>
        </div>

        <div>
            <label class="block font-medium mb-1">Фајл</label>
            <input type="file" name="document" class="w-full border rounded-xl px-4 py-3" required>
        </div>

        <button type="submit"
                class="px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition">
            Upload
        </button>
    </form>

    @if($errors->any())
        <div class="mt-4 bg-red-100 text-red-700 p-4 rounded-xl">
            {{ $errors->first() }}
        </div>
    @endif
</div>

<div class="bg-white shadow rounded-2xl p-8">
    <h3 class="text-xl font-bold mb-4">Зачувани документи</h3>

    @if($documents->count())
        <div class="space-y-4">
            @foreach($documents as $document)
                <div class="border rounded-xl p-4 flex flex-col md:flex-row md:items-center md:justify-between gap-4">
                    <div>
                        <p class="font-semibold">{{ $document->title }}</p>
                        <p class="text-sm text-gray-500">{{ $document->original_name }}</p>
                        <p class="text-xs text-gray-400">{{ number_format($document->file_size / 1024, 2) }} KB</p>
                    </div>

                    <div class="flex gap-3">
                        <a href="{{ asset('storage/' . $document->file_path) }}"
                           target="_blank"
                           class="px-4 py-2 bg-emerald-600 text-white rounded-lg hover:bg-emerald-700">
                            View
                        </a>

                        <form action="{{ route('documents.destroy', $document) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700">
                                Delete
                            </button>
                        </form>
                    </div>
                </div>
            @endforeach
        </div>
    @else
        <p class="text-gray-500">Сѐ уште нема прикачени документи.</p>
    @endif
</div>
</div>
</div>
</x-app-layout>
