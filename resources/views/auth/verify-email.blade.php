<x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        Ти благодариме за регистрацијата! За да продолжиш, потврди ја твојата е-маил адреса со кликнување на линкот што ти го пративме на е-маил. Ако не си ја добил пораката, со задоволство ќе ти пратиме нова.
    </div>

    @if (session('status') == 'verification-link-sent')
        <div class="mb-4 font-medium text-sm text-emerald-600">
            Нов линк за потврда е испратен на твојата е-маил адреса.
        </div>
    @endif

    <div class="mt-4 flex items-center justify-between">
        <form method="POST" action="{{ route('verification.send') }}">
            @csrf

            <div>
                <x-primary-button>
                    Прати ми пак линк за потврда
                </x-primary-button>
            </div>
        </form>

        <form method="POST" action="{{ route('logout') }}">
            @csrf

            <button type="submit" class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Одјави се
            </button>
        </form>
    </div>
</x-guest-layout>
