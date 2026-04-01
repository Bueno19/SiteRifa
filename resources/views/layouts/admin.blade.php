<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Painel Administrativo' }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-[#07070b] text-white antialiased">
    @php
        $supportLink = $supportLink ?? '#';
    @endphp

    <div class="fixed inset-0 -z-10 overflow-hidden">
        <div class="absolute inset-0 bg-[radial-gradient(circle_at_top,_rgba(255,123,0,0.14),_transparent_30%),linear-gradient(to_bottom,_#0a0a10,_#07070b)]"></div>
        <div class="absolute inset-0 opacity-[0.10] bg-[linear-gradient(rgba(255,255,255,0.05)_1px,transparent_1px),linear-gradient(90deg,rgba(255,255,255,0.05)_1px,transparent_1px)] bg-[size:34px_34px]"></div>
    </div>

    <div class="min-h-screen lg:grid lg:grid-cols-[300px_1fr]">
        <aside class="border-r border-white/10 bg-black/20 backdrop-blur">
            <div class="p-6">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3">
                    <div class="flex h-11 w-11 items-center justify-center rounded-2xl bg-gradient-to-br from-orange-500 to-red-600 font-black shadow-[0_0_24px_rgba(255,102,0,0.35)]">
                        A
                    </div>
                    <div>
                        <p class="text-xs font-bold uppercase tracking-[0.25em] text-orange-400">Área administrativa</p>
                        <h1 class="text-lg font-black">{{ $campaignTitle ?? 'Mega Rifa Gamer' }}</h1>
                    </div>
                </a>
            </div>

            <nav class="space-y-2 px-4 pb-6">
                <a href="{{ route('admin.dashboard') }}"
                   class="block rounded-2xl border px-4 py-3 transition
                   {{ request()->routeIs('admin.dashboard')
                        ? 'border-orange-400/20 bg-orange-500/10 font-semibold text-white'
                        : 'border-white/10 text-zinc-300 hover:bg-white/5 hover:text-white' }}">
                    Dashboard
                </a>

                <a href="{{ Route::has('admin.reservations') ? route('admin.reservations') : '#' }}"
                   class="block rounded-2xl border px-4 py-3 transition
                   {{ request()->routeIs('admin.reservations*')
                        ? 'border-orange-400/20 bg-orange-500/10 font-semibold text-white'
                        : 'border-white/10 text-zinc-300 hover:bg-white/5 hover:text-white' }}">
                    Reservas
                </a>

                <a href="{{ Route::has('admin.numbers') ? route('admin.numbers') : '#' }}"
                   class="block rounded-2xl border px-4 py-3 transition
                   {{ request()->routeIs('admin.numbers*')
                        ? 'border-orange-400/20 bg-orange-500/10 font-semibold text-white'
                        : 'border-white/10 text-zinc-300 hover:bg-white/5 hover:text-white' }}">
                    Números
                </a>

                <a href="{{ Route::has('admin.users') ? route('admin.users') : '#' }}"
                   class="block rounded-2xl border px-4 py-3 transition
                   {{ request()->routeIs('admin.users*')
                        ? 'border-orange-400/20 bg-orange-500/10 font-semibold text-white'
                        : 'border-white/10 text-zinc-300 hover:bg-white/5 hover:text-white' }}">
                    Usuários
                </a>

                <a href="{{ Route::has('admin.settings') ? route('admin.settings') : '#' }}"
                   class="block rounded-2xl border px-4 py-3 transition
                   {{ request()->routeIs('admin.settings*')
                        ? 'border-orange-400/20 bg-orange-500/10 font-semibold text-white'
                        : 'border-white/10 text-zinc-300 hover:bg-white/5 hover:text-white' }}">
                    Configurações
                </a>

                <a href="{{ Route::has('admin.logs') ? route('admin.logs') : '#' }}"
                   class="block rounded-2xl border px-4 py-3 transition
                   {{ request()->routeIs('admin.logs*')
                        ? 'border-orange-400/20 bg-orange-500/10 font-semibold text-white'
                        : 'border-white/10 text-zinc-300 hover:bg-white/5 hover:text-white' }}">
                    Logs
                </a>

                <a href="{{ route('home') }}"
                   class="block rounded-2xl border border-white/10 px-4 py-3 text-zinc-300 transition hover:bg-white/5 hover:text-white">
                    Voltar ao site
                </a>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf
                    <button
                        type="submit"
                        class="block w-full rounded-2xl border border-white/10 px-4 py-3 text-left text-zinc-300 transition hover:bg-white/5 hover:text-white"
                    >
                        Sair da conta
                    </button>
                </form>
            </nav>
        </aside>

        <div class="flex min-h-screen flex-col">
            <header class="border-b border-white/10 bg-[#090910]/80 px-6 py-4 backdrop-blur">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-zinc-400">Painel administrativo</p>
                        <h2 class="text-2xl font-black text-white">{{ $adminName ?? 'Administrador' }}</h2>
                    </div>

                    <div class="flex flex-wrap items-center gap-3">
                        <a href="{{ Route::has('admin.reservations') ? route('admin.reservations') : '#' }}"
                           class="rounded-xl border border-white/15 px-4 py-2 text-sm font-semibold text-zinc-200 transition hover:bg-white/5">
                            Reservas
                        </a>

                        <a href="{{ Route::has('admin.settings') ? route('admin.settings') : '#' }}"
                           class="rounded-xl border border-white/15 px-4 py-2 text-sm font-semibold text-zinc-200 transition hover:bg-white/5">
                            Configurações
                        </a>

                        <a href="{{ $supportLink }}"
                           target="_blank"
                           rel="noopener noreferrer"
                           class="rounded-xl bg-gradient-to-r from-orange-500 to-red-600 px-4 py-2 text-sm font-bold text-white">
                            Suporte
                        </a>
                    </div>
                </div>
            </header>

            <main class="flex-1 px-6 py-8">
                @yield('content')
            </main>
        </div>
    </div>
</body>
</html>