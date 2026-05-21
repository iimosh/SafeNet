<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>SafeNet</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-white min-h-screen">
<div class="min-h-screen flex items-center justify-center px-6">
    <div class="max-w-5xl w-full text-center">
        <h1 class="text-5xl font-bold mb-6">SafeNet</h1>

        <p class="text-xl text-slate-300 mb-4">
            Платформа за проценка на дигитални ризици кај ученици
        </p>

        <p class="text-slate-400 mb-10 max-w-3xl mx-auto">
            Ученици и родители одговараат на прашалник за онлајн навики,
            а системот автоматски генерира извештај за потенцијални ризици
            и препораки за подобрување на дигиталната безбедност.
        </p>


        <div class="flex flex-wrap justify-center gap-4">
            @guest
                <a href="{{ route('register') }}"
                   class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 transition font-semibold">
                    Регистрирај се
                </a>

                <a href="{{ route('login') }}"
                   class="px-6 py-3 rounded-xl border border-slate-600 hover:bg-slate-800 transition font-semibold">
                    Најави се
                </a>
            @endguest

            @auth
                <a href="{{ route('dashboard') }}"
                   class="px-6 py-3 rounded-xl bg-blue-600 hover:bg-blue-700 transition font-semibold">
                    Dashboard
                </a>

                @if(auth()->user()->role === 'admin')
                    <a href="{{ url('/admin') }}"
                       class="px-6 py-3 rounded-xl bg-amber-500 hover:bg-amber-600 transition font-semibold text-black">
                        Admin Panel
                    </a>
                @endif
            @endauth
                <div></div>
                <div class="mt-8 flex justify-center gap-6 text-sm text-slate-400">
                    <a href="{{ route('about') }}" class="hover:text-white">За платформата</a>
                    <a href="{{ route('contact') }}" class="hover:text-white">Контакт</a>
                </div>
        </div>
    </div>
</div>
</body>
</html>


