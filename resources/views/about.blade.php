<!DOCTYPE html>
<html lang="mk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>За платформата - SafeNet</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-white min-h-screen">
<div class="max-w-5xl mx-auto px-6 py-16">
    <h1 class="text-4xl font-bold mb-6">За платформата</h1>
    <p class="text-slate-300 text-lg mb-6">
        SafeNet е веб апликација наменета за проценка на дигитални ризици кај ученици
        преку прашалник за онлајн навики, користење на социјални мрежи, заштита на лозинки
        и изложеност на онлајн закани.
    </p>
    <p class="text-slate-400 mb-4">
        Целта на системот е да помогне на ученици, родители и наставници подобро да ги
        разберат ризиците поврзани со дигиталната средина.
    </p>
    <a href="{{ route('home') }}" class="inline-block mt-6 px-6 py-3 bg-blue-600 rounded-xl hover:bg-blue-700">
        Назад
    </a>
</div>
</body>
</html><?php
