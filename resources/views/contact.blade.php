<!DOCTYPE html>
<html lang="mk">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Контакт - SafeNet</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-slate-950 text-white min-h-screen">
<div class="max-w-4xl mx-auto px-6 py-16">
    <h1 class="text-4xl font-bold mb-6">Контакт</h1>
    <p class="text-slate-300 mb-4">
        За дополнителни информации околу платформата SafeNet можете да не контактирате.
    </p>

    <div class="bg-slate-900 rounded-2xl p-8 mt-8 space-y-4">
        <p><strong>Email:</strong> support@safenet.local</p>
        <p><strong>Телефон:</strong> +389 70 000 000</p>
        <p><strong>Локација:</strong> Скопје, Северна Македонија</p>
    </div>

    <a href="{{ route('home') }}" class="inline-block mt-6 px-6 py-3 bg-blue-600 rounded-xl hover:bg-blue-700">
        Назад
    </a>
</div>
</body>
</html>
