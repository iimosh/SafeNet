<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name', 'Laravel') }}</title>


        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />


        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body class="font-sans antialiased">
    <div class="bg-gradient-to-br from-slate-50 via-blue-50 to-cyan-50 min-h-screen">
            @include('layouts.navigation')


            @isset($header)
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                        {{ $header }}
                    </div>
                </header>
            @endisset


            <main>
                {{ $slot }}
            </main>
        </div>
    </body>
    <footer class="mt-16 border-t border-white/10 bg-slate-950/90 backdrop-blur-xl">
        <div class="max-w-7xl mx-auto px-6 py-8">

            <div class="flex flex-col md:flex-row items-center justify-between gap-6">

                <!-- LEFT -->
                <div>
                    <h3 class="text-xl font-bold text-white">
                        MKSafeNet
                    </h3>

                    <p class="text-slate-400 text-sm mt-2 max-w-md">
                        Платформа за проценка и подигнување на свеста
                        за дигитална безбедност кај ученици и родители.
                    </p>
                </div>

                <!-- CENTER -->
                <div class="flex items-center gap-6 text-sm">

                    <a href="{{ auth()->user()->isParent() ? route('parent.dashboard') : route('dashboard') }}"
                       class="text-slate-400 hover:text-white transition">
                        Почетна
                    </a>

                    <a href="{{ route('questionnaires.index') }}"
                       class="text-slate-400 hover:text-white transition">
                        Прашалници
                    </a>

                    <a href="{{ route('assessment.index') }}"
                       class="text-slate-400 hover:text-white transition">
                        Проценки
                    </a>

                </div>

                <!-- RIGHT -->
                <div class="text-sm text-slate-500 text-center md:text-right">
                    © {{ date('Y') }} MKSafeNet <br>
                    Сите права се задржани.
                </div>

            </div>

        </div>
    </footer>
</html>
