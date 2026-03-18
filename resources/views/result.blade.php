<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Assessment Result
        </h2>
    </x-slot>

    <div class="py-10">
        <div class="max-w-5xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white shadow rounded-2xl p-8">
                <h1 class="text-2xl font-bold mb-4">Твој резултат</h1>

                <div class="grid md:grid-cols-2 gap-6 mb-8">
                    <div class="p-6 rounded-xl bg-slate-100">
                        <p class="text-gray-500 mb-2">Вкупни поени</p>
                        <p class="text-3xl font-bold">{{ $assessment->total_points }}</p>
                    </div>

                    <div class="p-6 rounded-xl bg-slate-100">
                        <p class="text-gray-500 mb-2">Ниво на ризик</p>
                        <p class="text-3xl font-bold capitalize">{{ $assessment->risk_level }}</p>
                    </div>
                </div>

                <h2 class="text-xl font-semibold mb-4">Распределба по категории</h2>

                <div class="space-y-3 mb-8">
                    @forelse($breakdown as $category => $points)
                        <div class="flex items-center justify-between border rounded-lg p-4">
                            <span class="font-medium capitalize">{{ $category }}</span>
                            <span class="font-bold">{{ $points }} pts</span>
                        </div>
                    @empty
                        <p class="text-gray-500">Нема податоци по категории.</p>
                    @endforelse
                </div>

                <div class="rounded-xl bg-blue-50 p-6">
                    <h3 class="font-semibold text-lg mb-3">Препорака</h3>

                    @if($assessment->risk_level === 'low')
                        <p class="text-gray-700">
                            Имаш ниско ниво на ризик. Продолжи со добри дигитални навики
                            и внимавај на приватност, лозинки и безбедна комуникација.
                        </p>
                    @elseif($assessment->risk_level === 'medium')
                        <p class="text-gray-700">
                            Имаш средно ниво на ризик. Потребно е подобрување на лозинките,
                            внимателност на социјални мрежи и подобра заштита на личните податоци.
                        </p>
                    @else
                        <p class="text-gray-700">
                            Имаш високо ниво на ризик. Потребно е веднаш да се подобрат
                            навиките за приватност, комуникација со непознати лица и
                            препознавање на опасни онлајн ситуации.
                        </p>
                    @endif
                </div>

                <div class="mt-6">
                    <a href="{{ $backRoute }}"
                       class="inline-block px-5 py-3 bg-slate-900 text-white rounded-xl hover:bg-slate-700 transition">
                        Back to dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
