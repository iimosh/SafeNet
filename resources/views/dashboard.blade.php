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
                    Од оваа страница можеш да ја започнеш проценката на дигитални ризици,
                    да ги видиш резултатите и да прикачиш документи.
                </p>

                <div class="flex flex-wrap gap-4">
                    <a href="{{ route('assessment.start') }}"
                       class="inline-block px-6 py-3 bg-blue-600 text-white rounded-xl hover:bg-blue-700 transition">
                        Започни прашалник
                    </a>

                    <a href="{{ route('documents.index') }}"
                       class="inline-block px-6 py-3 bg-emerald-600 text-white rounded-xl hover:bg-emerald-700 transition">
                        Моите документи
                    </a>

                    @if(auth()->user()->role === 'admin')
                        <a href="{{ url('/admin') }}"
                           class="inline-block px-6 py-3 bg-slate-900 text-white rounded-xl hover:bg-slate-700 transition">
                            Admin Panel
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
