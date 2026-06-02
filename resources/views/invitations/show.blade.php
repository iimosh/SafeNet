<x-app-layout>
    <x-slot name="header">
        <h2 class="font-bold text-2xl text-slate-800 leading-tight">
            Покана за поврзување со родител
        </h2>
    </x-slot>

    <div class="min-h-screen bg-gradient-to-br from-slate-50 via-blue-50 to-cyan-50 py-10">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">

            <div class="bg-white/80 backdrop-blur-xl border border-white/40 shadow-xl rounded-3xl p-10">

                <div class="mb-6">
                    <h1 class="text-3xl font-black text-slate-800 mb-2">
                        {{ $invitation->parent->name }} те поканува
                    </h1>
                    <p class="text-slate-500">
                        Со прифаќање, родителот ќе може да пополнува прашалници за тебе
                        и да ги гледа резултатите за твојата дигитална безбедност.
                    </p>
                </div>

                <div class="bg-slate-50 border border-slate-100 rounded-2xl p-5 mb-8 text-sm text-slate-600 space-y-1">
                    <p><span class="font-semibold text-slate-800">Родител:</span> {{ $invitation->parent->name }} ({{ $invitation->parent->email }})</p>
                    @if($invitation->expires_at)
                        <p><span class="font-semibold text-slate-800">Важи до:</span> {{ $invitation->expires_at->format('d.m.Y H:i') }}</p>
                    @endif
                </div>

                <div class="flex flex-col sm:flex-row gap-4">
                    <form method="POST" action="{{ route('invitation.accept', $invitation->token) }}" class="flex-1">
                        @csrf
                        <button type="submit"
                                class="w-full inline-flex items-center justify-center gap-2 px-6 py-4
                                       bg-gradient-to-r from-emerald-500 to-emerald-600
                                       text-white rounded-2xl font-bold shadow-lg
                                       hover:scale-105 transition duration-300">
                            Прифати поканата
                        </button>
                    </form>

                    <form method="POST" action="{{ route('invitation.decline', $invitation->token) }}" class="flex-1">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                                onclick="return confirm('Сигурно сакаш да ја одбиеш?')"
                                class="w-full inline-flex items-center justify-center gap-2 px-6 py-4
                                       bg-slate-200 text-slate-700 rounded-2xl font-bold
                                       hover:bg-slate-300 transition duration-300">
                            Одбиј
                        </button>
                    </form>
                </div>

            </div>

        </div>
    </div>
</x-app-layout>
