<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Rifa Gamer' }}</title>
    <meta name="description" content="Participe da rifa e reserve seus números com segurança.">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    @stack('styles')
</head>
<body class="min-h-screen bg-[#07070b] text-white antialiased">
    <div class="fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,123,0,0.18),_transparent_30%),radial-gradient(circle_at_right,_rgba(255,0,106,0.10),_transparent_25%),linear-gradient(to_bottom,_#0a0a10,_#07070b)]"></div>
        <div class="absolute inset-0 opacity-[0.12] bg-[linear-gradient(rgba(255,255,255,0.06)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.06)_1px,transparent_1px)] bg-[size:36px_36px]"></div>
        <div class="absolute -top-24 left-1/2 h-72 w-72 -translate-x-1/2 rounded-full bg-orange-500/20 blur-3xl"></div>
        <div class="absolute bottom-0 right-0 h-80 w-80 rounded-full bg-pink-500/10 blur-3xl"></div>
    </div>

    <header class="sticky top-0 z-40 border-b border-white/10 bg-[#090910]/80 backdrop-blur">
        <div class="mx-auto flex max-w-7xl items-center justify-between px-4 py-4 sm:px-6 lg:px-8">
            <a href="{{ route('home') }}" class="flex items-center gap-3">
                <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-orange-500 to-red-600 shadow-[0_0_24px_rgba(255,102,0,0.35)]">
                    <span class="text-lg font-black">R</span>
                </div>
                <div>
                    <p class="text-sm font-semibold uppercase tracking-[0.25em] text-orange-400">Rifa Gamer</p>
                    <h1 class="text-base font-black sm:text-lg">{{ $campaignTitle ?? 'Mega Rifa Gamer' }}</h1>
                </div>
            </a>

            <nav class="hidden items-center gap-8 md:flex">
                <a href="#premio" class="text-sm font-medium text-zinc-300 transition hover:text-white">Prêmio</a>
                <a href="#como-funciona" class="text-sm font-medium text-zinc-300 transition hover:text-white">Como funciona</a>
                <a href="#estatisticas" class="text-sm font-medium text-zinc-300 transition hover:text-white">Estatísticas</a>
                <a href="#data-sorteio" class="text-sm font-medium text-zinc-300 transition hover:text-white">Sorteio</a>
            </nav>

            <div class="flex items-center gap-3">
                @auth
                    <a href="{{ route('dashboard') }}"
                       class="hidden rounded-xl border border-white/15 px-4 py-2 text-sm font-semibold text-zinc-200 transition hover:border-white/30 hover:bg-white/5 sm:inline-flex">
                        Meu painel
                    </a>

                    <form action="{{ route('logout') }}" method="POST" class="inline-flex">
                        @csrf
                        <button
                            type="submit"
                            class="rounded-xl bg-gradient-to-r from-orange-500 to-red-600 px-4 py-2 text-sm font-bold text-white shadow-[0_10px_30px_rgba(255,90,31,0.25)] transition hover:scale-[1.02]"
                        >
                            Sair
                        </button>
                    </form>
                @else
                    <a href="{{ route('login') }}"
                       class="hidden rounded-xl border border-white/15 px-4 py-2 text-sm font-semibold text-zinc-200 transition hover:border-white/30 hover:bg-white/5 sm:inline-flex">
                        Entrar
                    </a>

                    <a href="{{ route('register') }}"
                       class="rounded-xl bg-gradient-to-r from-orange-500 to-red-600 px-4 py-2 text-sm font-bold text-white shadow-[0_10px_30px_rgba(255,90,31,0.25)] transition hover:scale-[1.02]">
                        Participar
                    </a>
                @endauth
            </div>
        </div>
    </header>

    <main>
        @yield('content')
    </main>

    <footer class="border-t border-white/10 bg-black/20">
        <div class="mx-auto flex max-w-7xl flex-col gap-4 px-4 py-8 text-sm text-zinc-400 sm:px-6 lg:px-8 md:flex-row md:items-center md:justify-between">
            <div>
                <p class="font-semibold text-zinc-200">{{ $campaignTitle ?? 'Mega Rifa Gamer' }}</p>
                <p>Reserva de números com confirmação manual pelo organizador.</p>
            </div>

            <div class="text-left md:text-right">
                <p>Desenvolvido para divulgação da campanha.</p>
                <p class="text-zinc-500">Pagamento e confirmação fora do site.</p>
            </div>
        </div>
    </footer>
</body>
</html>