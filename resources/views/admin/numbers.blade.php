@extends('layouts.admin', ['title' => 'Números'])

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

        @if ($errors->any())
            <div class="rounded-2xl border border-red-400/20 bg-red-500/10 px-5 py-4 text-red-300">
                <p class="font-bold">Não foi possível concluir a ação</p>
                <ul class="mt-2 space-y-1 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
            <p class="text-sm font-bold uppercase tracking-[0.25em] text-orange-300">Números</p>
            <h3 class="mt-2 text-2xl font-black text-white">Gerenciamento manual</h3>
            <p class="mt-3 text-zinc-300">
                Altere o status dos números manualmente para liberar, reservar ou marcar como pago.
            </p>

            <form method="GET" action="{{ route('admin.numbers') }}" class="mt-6 grid gap-4 md:grid-cols-[1fr_220px_160px]">
                <div>
                    <label class="mb-2 block text-sm font-semibold text-zinc-200">Buscar número</label>
                    <input
                        type="text"
                        name="q"
                        value="{{ $filters['q'] }}"
                        placeholder="Ex.: 25"
                        class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white outline-none transition focus:border-orange-400/50 focus:ring-2 focus:ring-orange-500/20"
                    >
                </div>

                <div>
                    <label class="mb-2 block text-sm font-semibold text-zinc-200">Status</label>
                    <select
                        name="status"
                        class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white outline-none transition focus:border-orange-400/50 focus:ring-2 focus:ring-orange-500/20"
                    >
                        <option value="">Todos</option>
                        <option value="disponivel" {{ $filters['status'] === 'disponivel' ? 'selected' : '' }}>Disponível</option>
                        <option value="reservado" {{ $filters['status'] === 'reservado' ? 'selected' : '' }}>Reservado</option>
                        <option value="pago" {{ $filters['status'] === 'pago' ? 'selected' : '' }}>Pago</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button
                        type="submit"
                        class="w-full rounded-2xl bg-gradient-to-r from-orange-500 to-red-600 px-6 py-3 text-sm font-black uppercase tracking-wide text-white"
                    >
                        Filtrar
                    </button>
                </div>
            </form>
        </section>

        <section class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead>
                        <tr class="border-b border-white/10 text-sm uppercase tracking-wide text-zinc-400">
                            <th class="px-4 py-3">Número</th>
                            <th class="px-4 py-3">Status atual</th>
                            <th class="px-4 py-3">Reserva</th>
                            <th class="px-4 py-3">Usuário</th>
                            <th class="px-4 py-3">Atualizado em</th>
                            <th class="px-4 py-3">Alterar status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($numbers as $item)
                            <tr class="border-b border-white/5 align-top">
                                <td class="px-4 py-4">
                                    <p class="font-semibold text-white">#{{ str_pad($item->number, 4, '0', STR_PAD_LEFT) }}</p>
                                    <p class="mt-1 text-xs text-zinc-500">ID {{ $item->id }}</p>
                                </td>

                                <td class="px-4 py-4">
                                    @php
                                        $statusClass = match ($item->status) {
                                            'pago' => 'border-orange-400/20 bg-orange-500/10 text-orange-300',
                                            'reservado' => 'border-amber-400/20 bg-amber-500/10 text-amber-300',
                                            default => 'border-emerald-400/20 bg-emerald-500/10 text-emerald-300',
                                        };
                                    @endphp

                                    <span class="rounded-full border px-3 py-1 text-xs font-bold uppercase tracking-wide {{ $statusClass }}">
                                        {{ $item->status }}
                                    </span>
                                </td>

                                <td class="px-4 py-4 text-sm text-zinc-300">
                                    {{ $item->reservation_id ?? '-' }}
                                </td>

                                <td class="px-4 py-4 text-sm text-zinc-300">
                                    {{ $item->user_id ?? '-' }}
                                </td>

                                <td class="px-4 py-4 text-sm text-zinc-300">
                                    {{ $item->updated_at ? \Carbon\Carbon::parse($item->updated_at)->format('d/m/Y H:i') : '-' }}
                                </td>

                                <td class="px-4 py-4">
                                    <form action="{{ route('admin.numbers.status', $item->id) }}" method="POST" class="flex flex-col gap-2 sm:flex-row">
                                        @csrf

                                        <input type="hidden" name="q" value="{{ $filters['q'] }}">
                                        <input type="hidden" name="status_filter" value="{{ $filters['status'] }}">
                                        <input type="hidden" name="page" value="{{ request('page', 1) }}">

                                        <select
                                            name="status"
                                            class="rounded-xl border border-white/10 bg-black/20 px-3 py-2 text-sm text-white outline-none transition focus:border-orange-400/50 focus:ring-2 focus:ring-orange-500/20"
                                        >
                                            <option value="disponivel" {{ $item->status === 'disponivel' ? 'selected' : '' }}>Disponível</option>
                                            <option value="reservado" {{ $item->status === 'reservado' ? 'selected' : '' }}>Reservado</option>
                                            <option value="pago" {{ $item->status === 'pago' ? 'selected' : '' }}>Pago</option>
                                        </select>

                                        <button
                                            type="submit"
                                            class="rounded-xl border border-white/10 px-3 py-2 text-xs font-bold text-zinc-200 transition hover:bg-white/5"
                                        >
                                            Salvar
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-4 py-6 text-sm text-zinc-500">
                                    Nenhum número encontrado com os filtros atuais.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-6">
                {{ $numbers->links() }}
            </div>
        </section>
    </div>
@endsection