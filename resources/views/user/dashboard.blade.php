@extends('layouts.user', ['title' => 'Dashboard do Usuário'])

@section('content')
    @php
        $formattedSpent = number_format($spentTotal, 2, ',', '.');
    @endphp

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

        <section class="grid gap-4 md:grid-cols-2 xl:grid-cols-4">
            <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400">Reservados</p>
                <p class="mt-3 text-3xl font-black text-amber-400">{{ $reservedCount }}</p>
            </div>

            <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400">Pagos</p>
                <p class="mt-3 text-3xl font-black text-orange-400">{{ $paidCount }}</p>
            </div>

            <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400">Total gasto</p>
                <p class="mt-3 text-3xl font-black text-white">R$ {{ $formattedSpent }}</p>
            </div>

            <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400">Disponíveis no geral</p>
                <p class="mt-3 text-3xl font-black text-emerald-400">{{ number_format($availableNumbers, 0, ',', '.') }}</p>
            </div>
        </section>

        <section id="perfil" class="grid gap-6 xl:grid-cols-[1.2fr_0.8fr]">
            <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
                <p class="text-sm font-bold uppercase tracking-[0.25em] text-orange-300">Resumo da conta</p>
                <h3 class="mt-3 text-2xl font-black text-white">Seus números e sua participação</h3>
                <p class="mt-3 max-w-2xl text-zinc-300">
                    Aqui você acompanha tudo que já reservou, o que já foi confirmado e as informações principais da campanha.
                </p>

                <div class="mt-6 grid gap-4 md:grid-cols-2">
                    <div class="rounded-2xl border border-white/10 bg-black/20 p-5">
                        <p class="text-sm font-bold text-white">Data do sorteio</p>
                        <p class="mt-2 text-2xl font-black text-orange-300">{{ $drawDate }}</p>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-black/20 p-5">
                        <p class="text-sm font-bold text-white">Status da conta</p>
                        <p class="mt-2 text-lg font-black text-emerald-400">Ativa</p>
                    </div>
                </div>
            </div>

            <div class="rounded-[1.75rem] border border-white/10 bg-gradient-to-br from-orange-500/10 to-red-600/10 p-6">
                <p class="text-sm font-bold uppercase tracking-[0.25em] text-orange-300">Ação rápida</p>
                <h3 class="mt-3 text-2xl font-black text-white">Falar com o organizador</h3>
                <p class="mt-3 text-zinc-300">
                    Depois de reservar seus números, envie a mensagem para confirmar o pagamento.
                </p>

                <div class="mt-6 space-y-3">
                    <a href="{{ $whatsLink ?? '#' }}"
                       target="_blank"
                       rel="noopener noreferrer"
                       class="inline-flex w-full items-center justify-center rounded-2xl bg-gradient-to-r from-orange-500 to-red-600 px-5 py-3 text-center text-sm font-black uppercase tracking-wide text-white">
                        Enviar mensagem
                    </a>

                    <a href="{{ route('numbers') }}"
                       class="inline-flex w-full items-center justify-center rounded-2xl border border-white/10 px-4 py-3 text-sm font-semibold text-zinc-200 transition hover:bg-white/5">
                        Escolher mais números
                    </a>
                </div>
            </div>
        </section>

        <section id="minhas-reservas" class="grid gap-6 xl:grid-cols-2">
            <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
                <h3 class="text-xl font-black text-white">Números reservados</h3>
                <div class="mt-5 flex flex-wrap gap-3">
                    @forelse ($myReservedNumbers as $number)
                        <span class="inline-flex rounded-2xl border border-amber-400/20 bg-amber-500/10 px-4 py-2 font-bold text-amber-300">
                            #{{ str_pad($number, 4, '0', STR_PAD_LEFT) }}
                        </span>
                    @empty
                        <span class="text-sm text-zinc-500">Nenhum número reservado ainda.</span>
                    @endforelse
                </div>
            </div>

            <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
                <h3 class="text-xl font-black text-white">Números pagos</h3>
                <div class="mt-5 flex flex-wrap gap-3">
                    @forelse ($myPaidNumbers as $number)
                        <span class="inline-flex rounded-2xl border border-orange-400/20 bg-orange-500/10 px-4 py-2 font-bold text-orange-300">
                            #{{ str_pad($number, 4, '0', STR_PAD_LEFT) }}
                        </span>
                    @empty
                        <span class="text-sm text-zinc-500">Nenhum número pago ainda.</span>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.25em] text-orange-300">Histórico</p>
                    <h3 class="text-2xl font-black text-white">Últimas reservas</h3>
                </div>
            </div>

            <div class="mt-6 overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead>
                        <tr class="border-b border-white/10 text-sm uppercase tracking-wide text-zinc-400">
                            <th class="px-4 py-3">Data</th>
                            <th class="px-4 py-3">Números</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($reservationHistory as $item)
                            <tr class="border-b border-white/5">
                                <td class="px-4 py-4 text-sm text-zinc-300">{{ $item['date'] }}</td>
                                <td class="px-4 py-4 text-sm font-semibold text-white">{{ $item['numbers'] }}</td>
                                <td class="px-4 py-4">
                                    <span class="rounded-full border border-orange-400/20 bg-orange-500/10 px-3 py-1 text-xs font-bold uppercase tracking-wide text-orange-300">
                                        {{ $item['status'] }}
                                    </span>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="px-4 py-6 text-sm text-zinc-500">
                                    Você ainda não tem histórico de reservas.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection