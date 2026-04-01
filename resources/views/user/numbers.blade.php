@extends('layouts.user', ['title' => 'Escolher números'])

@section('content')
    @php
        $formattedTicketPrice = number_format($ticketPrice, 2, ',', '.');
    @endphp

    <div class="space-y-8 pb-28 xl:pb-0">
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
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400">Disponíveis</p>
                <p class="mt-3 text-3xl font-black text-emerald-400">{{ number_format($availableCount, 0, ',', '.') }}</p>
            </div>

            <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400">Reservados</p>
                <p class="mt-3 text-3xl font-black text-amber-400">{{ number_format($reservedCount, 0, ',', '.') }}</p>
            </div>

            <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400">Pagos</p>
                <p class="mt-3 text-3xl font-black text-orange-400">{{ number_format($paidCount, 0, ',', '.') }}</p>
            </div>

            <div class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400">Valor por número</p>
                <p class="mt-3 text-3xl font-black text-white">R$ {{ $formattedTicketPrice }}</p>
            </div>
        </section>

        <section class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
            <div class="flex flex-col gap-3 lg:flex-row lg:items-end lg:justify-between">
                <div>
                    <p class="text-sm font-bold uppercase tracking-[0.25em] text-orange-300">Escolha seus números</p>
                    <h2 class="mt-2 text-3xl font-black text-white">Selecione apenas os disponíveis</h2>
                    <p class="mt-3 max-w-3xl text-zinc-300">
                        Agora a grade está mais compacta para facilitar a escolha. O resumo da seleção acompanha a navegação para você confirmar a qualquer momento.
                    </p>
                </div>

                <div class="rounded-2xl border border-white/10 bg-black/20 px-4 py-3">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400">Legenda</p>
                    <div class="mt-3 flex flex-wrap gap-2 text-xs font-bold uppercase">
                        <span class="rounded-full border border-emerald-400/20 bg-emerald-500/10 px-3 py-1 text-emerald-300">Disponível</span>
                        <span class="rounded-full border border-amber-400/20 bg-amber-500/10 px-3 py-1 text-amber-300">Reservado</span>
                        <span class="rounded-full border border-orange-400/20 bg-orange-500/10 px-3 py-1 text-orange-300">Pago</span>
                        <span class="rounded-full border border-sky-400/20 bg-sky-500/10 px-3 py-1 text-sky-300">Selecionado</span>
                    </div>
                </div>
            </div>

            <div class="mt-6 grid gap-6 xl:grid-cols-[minmax(0,1fr)_360px]">
                <div>
                    <div class="flex flex-col gap-4 rounded-[1.5rem] border border-white/10 bg-black/20 p-4">
                        <div class="grid gap-4 lg:grid-cols-[320px_1fr] lg:items-end">
                            <div class="w-full">
                                <label for="numberSearch" class="mb-2 block text-sm font-semibold text-zinc-300">
                                    Buscar número
                                </label>
                                <input
                                    id="numberSearch"
                                    type="text"
                                    placeholder="Ex.: 0012, 89, 1500..."
                                    class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white outline-none transition placeholder:text-zinc-500 focus:border-orange-400/50 focus:ring-2 focus:ring-orange-500/20"
                                >
                            </div>

                            <div class="flex flex-wrap gap-2">
                                <button type="button" data-filter="todos" class="filter-button rounded-2xl border border-orange-400/20 bg-orange-500/10 px-4 py-3 text-sm font-bold text-white">
                                    Todos
                                </button>
                                <button type="button" data-filter="disponivel" class="filter-button rounded-2xl border border-white/10 px-4 py-3 text-sm font-bold text-zinc-300 transition hover:bg-white/5">
                                    Disponíveis
                                </button>
                                <button type="button" data-filter="reservado" class="filter-button rounded-2xl border border-white/10 px-4 py-3 text-sm font-bold text-zinc-300 transition hover:bg-white/5">
                                    Reservados
                                </button>
                                <button type="button" data-filter="pago" class="filter-button rounded-2xl border border-white/10 px-4 py-3 text-sm font-bold text-zinc-300 transition hover:bg-white/5">
                                    Pagos
                                </button>
                                <button type="button" data-filter="selecionados" class="filter-button rounded-2xl border border-white/10 px-4 py-3 text-sm font-bold text-zinc-300 transition hover:bg-white/5">
                                    Selecionados
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="mt-4 flex items-center justify-between gap-3 px-1">
                        <p class="text-sm text-zinc-400">
                            Mostrando <span id="visibleCount" class="font-bold text-white">0</span> números
                        </p>

                        <button
                            type="button"
                            id="clearSelectionButton"
                            class="rounded-xl border border-white/10 px-4 py-2 text-sm font-semibold text-zinc-300 transition hover:bg-white/5"
                        >
                            Limpar seleção
                        </button>
                    </div>

                    <div
                        id="numbersGrid"
                        class="mt-4 grid grid-cols-4 gap-2 sm:grid-cols-5 md:grid-cols-6 lg:grid-cols-8 xl:grid-cols-10 2xl:grid-cols-12"
                    >
                        @foreach ($numbers as $item)
                            @php
                                $numberLabel = str_pad($item['number'], 4, '0', STR_PAD_LEFT);

                                $statusClasses = match ($item['status']) {
                                    'disponivel' => 'border-emerald-400/20 bg-emerald-500/10 text-emerald-300 hover:border-emerald-300/40 hover:bg-emerald-500/15',
                                    'reservado' => 'border-amber-400/20 bg-amber-500/10 text-amber-300 opacity-80 cursor-not-allowed',
                                    'pago' => 'border-orange-400/20 bg-orange-500/10 text-orange-300 opacity-80 cursor-not-allowed',
                                    default => 'border-white/10 bg-white/5 text-white',
                                };
                            @endphp

                            <button
                                type="button"
                                class="number-button h-11 rounded-xl border text-xs font-black tracking-wide transition {{ $statusClasses }}"
                                data-number="{{ $item['number'] }}"
                                data-status="{{ $item['status'] }}"
                                data-label="{{ $numberLabel }}"
                            >
                                {{ $numberLabel }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <aside class="hidden xl:sticky xl:top-4 xl:block xl:self-start">
                    <form
                        id="numbersForm"
                        action="{{ route('numbers.reserve') }}"
                        method="POST"
                        class="rounded-[1.75rem] border border-white/10 bg-gradient-to-br from-orange-500/10 to-red-600/10 p-6 xl:max-h-[calc(100vh-2rem)] xl:overflow-y-auto"
                    >
                        @csrf

                        <p class="text-sm font-bold uppercase tracking-[0.25em] text-orange-300">Sua seleção</p>
                        <h3 class="mt-2 text-2xl font-black text-white">Carrinho de números</h3>
                        <p class="mt-3 text-sm leading-6 text-zinc-300">
                            O resumo fica fixo para você confirmar a reserva a qualquer momento.
                        </p>

                        <input type="hidden" name="selected_numbers" id="selectedNumbersInput">

                        <div class="mt-6 grid gap-4">
                            <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
                                <p class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400">Quantidade</p>
                                <p id="selectedCount" class="mt-2 text-2xl font-black text-white">0</p>
                            </div>

                            <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
                                <p class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400">Valor unitário</p>
                                <p class="mt-2 text-2xl font-black text-white">R$ {{ $formattedTicketPrice }}</p>
                            </div>

                            <div class="rounded-2xl border border-white/10 bg-black/20 p-4">
                                <p class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400">Total</p>
                                <p id="selectedTotal" class="mt-2 text-2xl font-black text-orange-300">R$ 0,00</p>
                            </div>
                        </div>

                        <div class="mt-6 rounded-2xl border border-white/10 bg-black/20 p-4">
                            <div class="flex items-center justify-between">
                                <p class="text-sm font-bold text-white">Números escolhidos</p>
                                <span id="emptySelectionText" class="text-xs font-semibold uppercase tracking-wide text-zinc-500">
                                    Nenhum ainda
                                </span>
                            </div>

                            <div id="selectedNumbersList" class="mt-4 flex max-h-64 flex-wrap gap-2 overflow-y-auto pr-1">
                            </div>
                        </div>

                        <button
                            type="submit"
                            id="reserveButton"
                            disabled
                            class="mt-6 w-full rounded-2xl bg-gradient-to-r from-orange-500 to-red-600 px-6 py-3 text-sm font-black uppercase tracking-wide text-white shadow-[0_10px_30px_rgba(255,90,31,0.25)] transition disabled:cursor-not-allowed disabled:opacity-40"
                        >
                            Reservar números
                        </button>
                    </form>
                </aside>
            </div>
        </section>
    </div>

    <div class="fixed inset-x-0 bottom-0 z-40 border-t border-white/10 bg-[#090910]/95 px-4 py-3 backdrop-blur xl:hidden">
        <div class="mx-auto flex max-w-7xl items-center gap-3">
            <div class="min-w-0 flex-1 rounded-2xl border border-white/10 bg-white/5 px-4 py-3">
                <p class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400">Sua seleção</p>
                <div class="mt-1 flex items-center justify-between gap-3">
                    <p class="text-sm font-bold text-white">
                        <span id="mobileSelectedCount">0</span> número(s)
                    </p>
                    <p id="mobileSelectedTotal" class="text-sm font-black text-orange-300">R$ 0,00</p>
                </div>
            </div>

            <button
                type="button"
                id="mobileReserveButton"
                disabled
                class="rounded-2xl bg-gradient-to-r from-orange-500 to-red-600 px-5 py-3 text-sm font-black uppercase tracking-wide text-white shadow-[0_10px_30px_rgba(255,90,31,0.25)] transition disabled:cursor-not-allowed disabled:opacity-40"
            >
                Reservar
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const ticketPrice = {{ $ticketPrice }};
            const buttons = Array.from(document.querySelectorAll('.number-button'));
            const filterButtons = Array.from(document.querySelectorAll('.filter-button'));
            const searchInput = document.getElementById('numberSearch');
            const visibleCount = document.getElementById('visibleCount');
            const clearSelectionButton = document.getElementById('clearSelectionButton');

            const selectedCount = document.getElementById('selectedCount');
            const selectedTotal = document.getElementById('selectedTotal');
            const selectedNumbersList = document.getElementById('selectedNumbersList');
            const selectedNumbersInput = document.getElementById('selectedNumbersInput');
            const reserveButton = document.getElementById('reserveButton');
            const emptySelectionText = document.getElementById('emptySelectionText');

            const mobileSelectedCount = document.getElementById('mobileSelectedCount');
            const mobileSelectedTotal = document.getElementById('mobileSelectedTotal');
            const mobileReserveButton = document.getElementById('mobileReserveButton');

            const numbersForm = document.getElementById('numbersForm');

            let currentFilter = 'todos';
            const selectedNumbers = new Set();

            function formatMoney(value) {
                return value.toLocaleString('pt-BR', {
                    style: 'currency',
                    currency: 'BRL'
                });
            }

            function baseClassForStatus(status) {
                switch (status) {
                    case 'disponivel':
                        return 'number-button h-11 rounded-xl border text-xs font-black tracking-wide transition border-emerald-400/20 bg-emerald-500/10 text-emerald-300 hover:border-emerald-300/40 hover:bg-emerald-500/15';
                    case 'reservado':
                        return 'number-button h-11 rounded-xl border text-xs font-black tracking-wide transition border-amber-400/20 bg-amber-500/10 text-amber-300 opacity-80 cursor-not-allowed';
                    case 'pago':
                        return 'number-button h-11 rounded-xl border text-xs font-black tracking-wide transition border-orange-400/20 bg-orange-500/10 text-orange-300 opacity-80 cursor-not-allowed';
                    default:
                        return 'number-button h-11 rounded-xl border text-xs font-black tracking-wide transition border-white/10 bg-white/5 text-white';
                }
            }

            function renderSelectedCart() {
                const numbers = Array.from(selectedNumbers).sort((a, b) => a - b);
                const totalValue = numbers.length * ticketPrice;

                selectedCount.textContent = numbers.length;
                selectedTotal.textContent = formatMoney(totalValue);
                selectedNumbersInput.value = numbers.join(',');
                reserveButton.disabled = numbers.length === 0;

                mobileSelectedCount.textContent = numbers.length;
                mobileSelectedTotal.textContent = formatMoney(totalValue);
                mobileReserveButton.disabled = numbers.length === 0;

                emptySelectionText.textContent = numbers.length === 0
                    ? 'Nenhum ainda'
                    : `${numbers.length} selecionado(s)`;

                selectedNumbersList.innerHTML = '';

                numbers.forEach(number => {
                    const item = document.createElement('span');
                    item.className = 'inline-flex rounded-xl border border-sky-400/20 bg-sky-500/10 px-3 py-2 text-xs font-bold text-sky-300';
                    item.textContent = String(number).padStart(4, '0');
                    selectedNumbersList.appendChild(item);
                });
            }

            function applyButtonState(button) {
                const status = button.dataset.status;
                const number = Number(button.dataset.number);

                button.className = baseClassForStatus(status);

                if (selectedNumbers.has(number)) {
                    button.className = 'number-button h-11 rounded-xl border text-xs font-black tracking-wide transition border-sky-400/30 bg-sky-500/20 text-sky-200 shadow-[0_0_0_1px_rgba(56,189,248,0.20)]';
                }
            }

            function shouldShowButton(button) {
                const number = button.dataset.label;
                const status = button.dataset.status;
                const rawNumber = Number(button.dataset.number);
                const search = searchInput.value.trim().toLowerCase();

                const matchSearch = search === '' || number.includes(search) || String(rawNumber).includes(search);

                let matchFilter = true;

                if (currentFilter === 'disponivel') matchFilter = status === 'disponivel';
                if (currentFilter === 'reservado') matchFilter = status === 'reservado';
                if (currentFilter === 'pago') matchFilter = status === 'pago';
                if (currentFilter === 'selecionados') matchFilter = selectedNumbers.has(rawNumber);

                return matchSearch && matchFilter;
            }

            function renderGridVisibility() {
                let visible = 0;

                buttons.forEach(button => {
                    const show = shouldShowButton(button);
                    button.style.display = show ? '' : 'none';

                    if (show) visible++;

                    applyButtonState(button);
                });

                visibleCount.textContent = visible.toLocaleString('pt-BR');
            }

            function setActiveFilterButton() {
                filterButtons.forEach(button => {
                    if (button.dataset.filter === currentFilter) {
                        button.className = 'filter-button rounded-2xl border border-orange-400/20 bg-orange-500/10 px-4 py-3 text-sm font-bold text-white';
                    } else {
                        button.className = 'filter-button rounded-2xl border border-white/10 px-4 py-3 text-sm font-bold text-zinc-300 transition hover:bg-white/5';
                    }
                });
            }

            buttons.forEach(button => {
                button.addEventListener('click', function () {
                    if (button.dataset.status !== 'disponivel') return;

                    const number = Number(button.dataset.number);

                    if (selectedNumbers.has(number)) {
                        selectedNumbers.delete(number);
                    } else {
                        selectedNumbers.add(number);
                    }

                    renderSelectedCart();
                    renderGridVisibility();
                });
            });

            filterButtons.forEach(button => {
                button.addEventListener('click', function () {
                    currentFilter = button.dataset.filter;
                    setActiveFilterButton();
                    renderGridVisibility();
                });
            });

            searchInput.addEventListener('input', renderGridVisibility);

            clearSelectionButton.addEventListener('click', function () {
                selectedNumbers.clear();
                renderSelectedCart();
                renderGridVisibility();
            });

            mobileReserveButton.addEventListener('click', function () {
                if (!mobileReserveButton.disabled && numbersForm) {
                    numbersForm.requestSubmit();
                }
            });

            setActiveFilterButton();
            renderSelectedCart();
            renderGridVisibility();
        });
    </script>
@endsection