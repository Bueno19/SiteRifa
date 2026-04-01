<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Autenticação' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#07070b] text-white antialiased">
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,123,0,0.18),_transparent_30%),radial-gradient(circle_at_right,_rgba(255,0,106,0.10),_transparent_25%),linear-gradient(to_bottom,_#0a0a10,_#07070b)]"></div>
        <div class="absolute inset-0 opacity-[0.12] bg-[linear-gradient(rgba(255,255,255,0.06)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.06)_1px,transparent_1px)] bg-[size:36px_36px]"></div>
        <div class="absolute top-0 left-1/2 h-72 w-72 -translate-x-1/2 rounded-full bg-orange-500/20 blur-3xl"></div>
    </div>

    <main class="flex min-h-screen items-center justify-center px-4 py-10">
        @yield('content')
    </main>
</body>
</html>