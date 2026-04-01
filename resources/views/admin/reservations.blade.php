@extends('layouts.admin', ['title' => 'Reservas Admin'])

@section('content')
    @php
        $formattedPendingAmount = number_format($pendingAmount, 2, ',', '.');
        $formattedConfirmedAmount = number_format($confirmedAmount, 2, ',', '.');
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
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400">Pendentes</p>
                <p class="mt-3 text-3xl font-black text-amber-400">{{ $pendingCount }}</p>
            </div>

            <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400">Confirmadas</p>
                <p class="mt-3 text-3xl font-black text-emerald-400">{{ $confirmedCount }}</p>
            </div>

            <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400">Valor pendente</p>
                <p class="mt-3 text-3xl font-black text-white">R$ {{ $formattedPendingAmount }}</p>
            </div>

            <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400">Valor confirmado</p>
                <p class="mt-3 text-3xl font-black text-orange-300">R$ {{ $formattedConfirmedAmount }}</p>
            </div>
        </section>

        <section class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.25em] text-orange-300">Gestão de reservas</p>
                    <h2 class="mt-2 text-3xl font-black text-white">Pagamentos aguardando confirmação</h2>
                    <p class="mt-3 max-w-3xl text-zinc-300">
                        Aqui vocês localizam o participante, conferem os números reservados e confirmam ou cancelam a reserva manualmente.
                    </p>
                </div>

                <div class="flex flex-wrap gap-3">
                    <div class="rounded-2xl border border-white/10 bg-black/20 px-4 py-3">
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400">Fluxo atual</p>
                        <p class="mt-1 text-sm font-semibold text-white">Reserva manual com confirmação pelo admin</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 grid gap-4 lg:grid-cols-[1fr_220px]">
                <div>
                    <label for="reservationSearch" class="mb-2 block text-sm font-semibold text-zinc-300">
                        Buscar por nome, email ou telefone
                    </label>
                    <input
                        id="reservationSearch"
                        type="text"
                        placeholder="Ex.: Carlos, amanda@email.com, 99777..."
                        class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white outline-none transition placeholder:text-zinc-500 focus:border-orange-400/50 focus:ring-2 focus:ring-orange-500/20"
                    >
                </div>

                <div>
                    <label for="reservationFilter" class="mb-2 block text-sm font-semibold text-zinc-300">
                        Filtrar status
                    </label>
                    <select
                        id="reservationFilter"
                        class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white outline-none transition focus:border-orange-400/50 focus:ring-2 focus:ring-orange-500/20"
                    >
                        <option value="todos">Todos</option>
                        <option value="pendente">Pendentes</option>
                        <option value="confirmado">Confirmadas</option>
                    </select>
                </div>
            </div>
        </section>

        <section class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.25em] text-orange-300">Reservas pendentes</p>
                    <h3 class="text-2xl font-black text-white">Confirmação manual de pagamento</h3>
                </div>

                <p class="text-sm text-zinc-400">
                    Mostrando <span id="pendingVisibleCount" class="font-bold text-white">{{ count($pendingReservations) }}</span> registro(s)
                </p>
            </div>

            <div class="mt-6 overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead>
                        <tr class="border-b border-white/10 text-sm uppercase tracking-wide text-zinc-400">
                            <th class="px-4 py-3">Reserva</th>
                            <th class="px-4 py-3">Participante</th>
                            <th class="px-4 py-3">Números</th>
                            <th class="px-4 py-3">Valor</th>
                            <th class="px-4 py-3">Status</th>
                            <th class="px-4 py-3">Ações</th>
                        </tr>
                    </thead>
                    <tbody id="pendingReservationsTable">
                        @foreach ($pendingReservations as $item)
                            <tr
                                class="reservation-row border-b border-white/5"
                                data-name="{{ strtolower($item['name']) }}"
                                data-email="{{ strtolower($item['email']) }}"
                                data-phone="{{ strtolower($item['phone']) }}"
                                data-status="{{ strtolower($item['status_key'] ?? 'pendente') }}"
                            >
                                <td class="px-4 py-4">
                                    <p class="font-semibold text-white">#{{ $item['id'] }}</p>
                                    <p class="text-sm text-zinc-400">{{ $item['created_at'] }}</p>
                                </td>

                                <td class="px-4 py-4">
                                    <p class="font-semibold text-white">{{ $item['name'] }}</p>
                                    <p class="text-sm text-zinc-400">{{ $item['email'] }}</p>
                                    <p class="text-sm text-zinc-500">{{ $item['phone'] }}</p>
                                </td>

                                <td class="px-4 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        @forelse ($item['numbers'] as $number)
                                            <span class="inline-flex rounded-2xl border border-amber-400/20 bg-amber-500/10 px-3 py-2 text-xs font-bold text-amber-300">
                                                {{ str_pad($number, 4, '0', STR_PAD_LEFT) }}
                                            </span>
                                        @empty
                                            <span class="text-sm text-zinc-500">Nenhum número</span>
                                        @endforelse
                                    </div>
                                </td>

                                <td class="px-4 py-4 text-sm font-semibold text-white">
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
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>

        <section class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
            <div class="flex flex-col gap-2 sm:flex-row sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.25em] text-orange-300">Reservas confirmadas</p>
                    <h3 class="text-2xl font-black text-white">Histórico recente</h3>
                </div>

                <p class="text-sm text-zinc-400">
                    {{ count($confirmedReservations) }} confirmada(s)
                </p>
            </div>

            <div class="mt-6 overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead>
                        <tr class="border-b border-white/10 text-sm uppercase tracking-wide text-zinc-400">
                            <th class="px-4 py-3">Reserva</th>
                            <th class="px-4 py-3">Participante</th>
                            <th class="px-4 py-3">Números</th>
                            <th class="px-4 py-3">Valor</th>
                            <th class="px-4 py-3">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($confirmedReservations as $item)
                            <tr class="border-b border-white/5">
                                <td class="px-4 py-4">
                                    <p class="font-semibold text-white">#{{ $item['id'] }}</p>
                                    <p class="text-sm text-zinc-400">{{ $item['created_at'] }}</p>
                                </td>

                                <td class="px-4 py-4">
                                    <p class="font-semibold text-white">{{ $item['name'] }}</p>
                                    <p class="text-sm text-zinc-400">{{ $item['email'] }}</p>
                                    <p class="text-sm text-zinc-500">{{ $item['phone'] }}</p>
                                </td>

                                <td class="px-4 py-4">
                                    <div class="flex flex-wrap gap-2">
                                        @forelse ($item['numbers'] as $number)
                                            <span class="inline-flex rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-3 py-2 text-xs font-bold text-emerald-300">
                                                {{ str_pad($number, 4, '0', STR_PAD_LEFT) }}
                                            </span>
                                        @empty
                                            <span class="text-sm text-zinc-500">Nenhum número</span>
                                        @endforelse
                                    </div>
                                </td>

                                <td class="px-4 py-4 text-sm font-semibold text-white">
                                    R$ {{ number_format($item['value'], 2, ',', '.') }}
                                </td>

                                <td class="px-4 py-4">
                                    <span class="rounded-full border border-emerald-400/20 bg-emerald-500/10 px-3 py-1 text-xs font-bold uppercase tracking-wide text-emerald-300">
                                        {{ $item['status'] }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const searchInput = document.getElementById('reservationSearch');
            const filterSelect = document.getElementById('reservationFilter');
            const rows = Array.from(document.querySelectorAll('.reservation-row'));
            const visibleCount = document.getElementById('pendingVisibleCount');

            function applyFilters() {
                const search = searchInput.value.trim().toLowerCase();
                const filter = filterSelect.value.toLowerCase();
                let totalVisible = 0;

                rows.forEach(row => {
                    const name = row.dataset.name || '';
                    const email = row.dataset.email || '';
                    const phone = row.dataset.phone || '';
                    const status = row.dataset.status || '';

                    const matchesSearch =
                        search === '' ||
                        name.includes(search) ||
                        email.includes(search) ||
                        phone.includes(search);

                    const matchesFilter =
                        filter === 'todos' ||
                        status === filter;

                    const show = matchesSearch && matchesFilter;
                    row.style.display = show ? '' : 'none';

                    if (show) {
                        totalVisible++;
                    }
                });

                visibleCount.textContent = totalVisible;
            }

            searchInput.addEventListener('input', applyFilters);
            filterSelect.addEventListener('change', applyFilters);

            applyFilters();
        });
    </script>
@endsection