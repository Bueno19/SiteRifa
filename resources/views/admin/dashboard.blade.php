@extends('layouts.admin', ['title' => 'Dashboard Admin'])

@section('content')
    <div class="space-y-8">
        @if (session('success'))
            <div class="rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-5 py-4 text-emerald-300">
                <p class="font-bold">Sucesso</p>
                <p class="mt-1 text-sm">{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="rounded-2xl border border-red-400/20 bg-red-500/10 px-5 py-4 text-red-300">
                <p class="font-bold">Erro</p>
                <p class="mt-1 text-sm">{{ session('error') }}</p>
            </div>
        @endif

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-5">
            <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400">Total</p>
                <p class="mt-3 text-3xl font-black text-white">{{ number_format($totalNumbers, 0, ',', '.') }}</p>
            </div>

            <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400">Disponíveis</p>
                <p class="mt-3 text-3xl font-black text-emerald-400">{{ number_format($availableNumbers, 0, ',', '.') }}</p>
            </div>

            <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400">Reservados</p>
                <p class="mt-3 text-3xl font-black text-amber-400">{{ number_format($reservedNumbers, 0, ',', '.') }}</p>
            </div>

            <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400">Pagos</p>
                <p class="mt-3 text-3xl font-black text-orange-400">{{ number_format($paidNumbers, 0, ',', '.') }}</p>
            </div>

            <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400">Pendentes</p>
                <p class="mt-3 text-3xl font-black text-red-400">{{ $pendingReservations }}</p>
            </div>
        </section>

        <section class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
            <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
                <p class="text-sm font-bold uppercase tracking-[0.25em] text-orange-300">Visão geral</p>
                <h3 class="mt-3 text-2xl font-black text-white">Controle total da campanha</h3>
                <p class="mt-3 max-w-2xl text-zinc-300">
                    Gerencie reservas pendentes, confirme pagamentos, ajuste informações da rifa e acompanhe todas as alterações feitas no sistema.
                </p>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <div class="rounded-2xl border border-white/10 bg-black/20 p-5">
                        <p class="text-sm font-bold text-white">Data do sorteio</p>
                        <p class="mt-2 text-2xl font-black text-orange-300">{{ $drawDate }}</p>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-black/20 p-5">
                        <p class="text-sm font-bold text-white">Status da campanha</p>
                        <p class="mt-2 text-lg font-black text-emerald-400">Reserva aberta</p>
                    </div>
                </div>
            </div>

            <div class="rounded-[1.75rem] border border-white/10 bg-gradient-to-br from-orange-500/10 to-red-600/10 p-6">
                <p class="text-sm font-bold uppercase tracking-[0.25em] text-orange-300">Ações rápidas</p>
                <div class="mt-5 space-y-3">
                    <a href="{{ route('admin.reservations') }}"
                       class="block rounded-2xl bg-gradient-to-r from-orange-500 to-red-600 px-5 py-3 text-center text-sm font-black uppercase tracking-wide text-white">
                        Confirmar pagamentos
                    </a>

                    <a href="#configuracoes-admin"
                       class="block rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-center text-sm font-bold text-zinc-100">
                        Editar campanha
                    </a>

                    <a href="#logs-recentes"
                       class="block rounded-2xl border border-white/10 bg-white/5 px-5 py-3 text-center text-sm font-bold text-zinc-100">
                        Ver logs
                    </a>
                </div>
            </div>
        </section>

        <section id="configuracoes-admin" class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
            <p class="text-sm font-bold uppercase tracking-[0.25em] text-orange-300">Configurações</p>
            <h3 class="mt-2 text-2xl font-black text-white">Painel administrativo</h3>
            <p class="mt-3 text-zinc-300">
                Esta área já pode servir como central de navegação. A edição real de campanha, usuários e suporte depende de rotas e controllers próprios.
            </p>

            <div class="mt-6 grid gap-4 md:grid-cols-3">
                <a href="{{ route('admin.reservations') }}"
                   class="rounded-2xl border border-white/10 bg-black/20 p-5 transition hover:bg-white/5">
                    <p class="text-sm font-bold text-white">Reservas</p>
                    <p class="mt-2 text-sm text-zinc-400">Confirmar, cancelar e acompanhar pendências.</p>
                </a>

                <a href="#logs-recentes"
                   class="rounded-2xl border border-white/10 bg-black/20 p-5 transition hover:bg-white/5">
                    <p class="text-sm font-bold text-white">Logs</p>
                    <p class="mt-2 text-sm text-zinc-400">Acompanhe alterações recentes do sistema.</p>
                </a>

                <a href="{{ route('home') }}"
                   class="rounded-2xl border border-white/10 bg-black/20 p-5 transition hover:bg-white/5">
                    <p class="text-sm font-bold text-white">Campanha pública</p>
                    <p class="mt-2 text-sm text-zinc-400">Visualize a landing page atual da campanha.</p>
                </a>
            </div>
        </section>

        <section class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.25em] text-orange-300">Reservas pendentes</p>
                    <h3 class="text-2xl font-black text-white">Pagamentos aguardando confirmação</h3>
                </div>
            </div>

            <div class="mt-6 overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead>
                        <tr class="border-b border-white/10 text-sm uppercase tracking-wide text-zinc-400">
                            <th class="px-4 py-3">Usuário</th>
                            <th class="px-4 py-3">Contato</th>
                            <th class="px-4 py-3">Números</th>
                            <th class="px-4 py-3">Valor</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Ações</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pendingList as $item)
                            <tr class="border-b border-white/5">
                                <td class="px-4 py-4">
                                    <p class="font-semibold text-white">{{ $item['name'] }}</p>
                                    <p class="text-sm text-zinc-400">{{ $item['email'] }}</p>
                                </td>

                                <td class="px-4 py-4 text-sm text-zinc-300">{{ $item['phone'] }}</td>

                                <td class="px-4 py-4 text-sm font-semibold text-white">{{ $item['numbers'] }}</td>

                                <td class="px-4 py-4 text-sm text-zinc-300">
                                    R$ {{ number_format($item['value'], 2, ',', '.') }}
                                </td>

                                <td class="px-4 py-4">
                                    <span class="rounded-full border border-amber-400/20 bg-amber-500/10 px-3 py-1 text-xs font-bold uppercase tracking-wide text-amber-300">
                                        {{ $item['status'] }}
                                    </span>
                                </td>

                                <td class="px-4 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        <form action="{{ route('admin.reservations.confirm', $item['id']) }}" method="POST">
                                            @csrf
                                            <button
                                                type="submit"
                                                class="rounded-xl bg-orange-500 px-3 py-2 text-xs font-bold text-white transition hover:bg-orange-400"
                                            >
                                                Confirmar
                                            </button>
                                        </form>

                                        <form action="{{ route('admin.reservations.cancel', $item['id']) }}" method="POST">
                                            @csrf
                                            <button
                                                type="submit"
                                                class="rounded-xl border border-white/10 px-3 py-2 text-xs font-bold text-zinc-200 transition hover:bg-white/5"
                                            >
                                                Cancelar
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-sm text-zinc-500">
                                    Nenhuma reserva pendente no momento.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>

        <section id="logs-recentes" class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
            <p class="text-sm font-bold uppercase tracking-[0.25em] text-orange-300">Logs recentes</p>
            <h3 class="mt-2 text-2xl font-black text-white">Últimas ações do sistema</h3>

            <div class="mt-6 overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead>
                        <tr class="border-b border-white/10 text-sm uppercase tracking-wide text-zinc-400">
                            <th class="px-4 py-3">Data</th>
                            <th class="px-4 py-3">Responsável</th>
                            <th class="px-4 py-3">Ação</th>
                            <th class="px-4 py-3">Alvo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($recentLogs as $log)
                            <tr class="border-b border-white/5">
                                <td class="px-4 py-4 text-sm text-zinc-300">{{ $log['date'] }}</td>
                                <td class="px-4 py-4 text-sm font-semibold text-white">{{ $log['user'] }}</td>
                                <td class="px-4 py-4 text-sm text-zinc-300">{{ $log['action'] }}</td>
                                <td class="px-4 py-4 text-sm text-zinc-300">{{ $log['target'] }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="px-4 py-6 text-sm text-zinc-500">
                                    Nenhum log disponível ainda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection