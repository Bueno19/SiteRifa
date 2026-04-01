@extends('layouts.public', ['title' => $campaignTitle])

@section('content')
    @php
        $soldNumbers = $reservedNumbers + $paidNumbers;
        $progressPercentage = $totalNumbers > 0 ? round(($soldNumbers / $totalNumbers) * 100, 1) : 0;
        $formattedTicketPrice = number_format($ticketPrice, 2, ',', '.');
        $whatsMessage = rawurlencode("Olá! Tenho interesse na rifa do {$prizeTitle} e quero participar.");
        $whatsLink = "https://wa.me/{$whatsappNumber}?text={$whatsMessage}";
    @endphp

    <section class="relative">
        <div class="mx-auto grid max-w-7xl gap-12 px-4 py-14 sm:px-6 lg:grid-cols-2 lg:items-center lg:px-8 lg:py-20">
            <div class="order-2 lg:order-1">
                <span class="inline-flex items-center rounded-full border border-orange-400/25 bg-orange-500/10 px-4 py-1 text-xs font-bold uppercase tracking-[0.25em] text-orange-300">
                    Rifa Gamer
                </span>

                <h2 class="mt-5 max-w-3xl text-4xl font-black uppercase leading-[0.95] tracking-tight text-white sm:text-5xl lg:text-6xl">
                    {{ $campaignTitle }}
                </h2>

                <p class="mt-4 max-w-2xl text-lg font-semibold text-orange-300 sm:text-xl">
                    {{ $prizeTitle }}
                </p>

                <p class="mt-6 max-w-2xl text-base leading-7 text-zinc-300 sm:text-lg">
                    Ajude participando da rifa e reserve seus números de forma simples. O pagamento é confirmado diretamente com o organizador, e seus números ficam vinculados à sua conta para evitar conflitos.
                </p>

                <div class="mt-8 flex flex-wrap gap-4">
                    <a href="{{ route('register') }}"
                       class="inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-orange-500 to-red-600 px-6 py-3 text-sm font-black uppercase tracking-wide text-white shadow-[0_12px_30px_rgba(255,90,31,0.35)] transition hover:scale-[1.02]">
                        Quero participar
                    </a>

                    <a href="{{ route('numbers') }}"
                       class="inline-flex items-center justify-center rounded-2xl border border-white/15 bg-white/5 px-6 py-3 text-sm font-bold uppercase tracking-wide text-zinc-100 transition hover:border-white/30 hover:bg-white/10">
                        Ver números
                    </a>
                </div>

                <div class="mt-10 grid gap-4 sm:grid-cols-3">
                    <div class="rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur">
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400">Valor por número</p>
                        <p class="mt-2 text-2xl font-black text-white">R$ {{ $formattedTicketPrice }}</p>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur">
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400">Total de números</p>
                        <p class="mt-2 text-2xl font-black text-white">{{ number_format($totalNumbers, 0, ',', '.') }}</p>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-white/5 p-5 backdrop-blur">
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400">Data do sorteio</p>
                        <p class="mt-2 text-2xl font-black text-white">{{ $drawDate }}</p>
                    </div>
                </div>
            </div>

            <div class="order-1 lg:order-2">
                <div class="relative mx-auto max-w-xl">
                    <div class="absolute -inset-4 rounded-[2rem] bg-gradient-to-r from-orange-500/20 to-pink-500/20 blur-2xl"></div>

                    <div class="relative overflow-hidden rounded-[2rem] border border-white/10 bg-white/5 p-3 shadow-[0_25px_80px_rgba(0,0,0,0.45)] backdrop-blur">
                        <img
                            src="{{ asset('images/rifa-monitor.jpg') }}"
                            alt="Arte da rifa do monitor ASUS ROG"
                            class="h-full w-full rounded-[1.4rem] object-cover"
                        >
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="estatisticas" class="pb-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-[2rem] border border-white/10 bg-white/5 p-6 shadow-[0_25px_60px_rgba(0,0,0,0.25)] backdrop-blur sm:p-8">
                <div class="flex flex-col gap-6 lg:flex-row lg:items-end lg:justify-between">
                    <div>
                        <p class="text-sm font-bold uppercase tracking-[0.25em] text-orange-300">Estatísticas da campanha</p>
                        <h3 class="mt-2 text-2xl font-black text-white sm:text-3xl">Acompanhe a disponibilidade em tempo real</h3>
                        <p class="mt-3 max-w-2xl text-zinc-300">
                            Os números reservados e pagos ficam vinculados à conta do participante. Assim o processo fica organizado e seguro.
                        </p>
                    </div>

                    <div class="min-w-[220px] rounded-2xl border border-orange-400/20 bg-orange-500/10 p-5">
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-orange-300">Progresso geral</p>
                        <p class="mt-2 text-3xl font-black text-white">{{ $progressPercentage }}%</p>
                        <p class="mt-1 text-sm text-zinc-300">{{ number_format($soldNumbers, 0, ',', '.') }} de {{ number_format($totalNumbers, 0, ',', '.') }} números ocupados</p>
                    </div>
                </div>

                <div class="mt-6 h-4 overflow-hidden rounded-full bg-white/10">
                    <div class="h-full rounded-full bg-gradient-to-r from-orange-500 via-red-500 to-pink-500"
                         style="width: {{ $progressPercentage }}%"></div>
                </div>

                <div class="mt-8 grid gap-4 sm:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl border border-white/10 bg-[#0d0d14] p-6">
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400">Disponíveis</p>
                        <p class="mt-3 text-3xl font-black text-emerald-400">{{ number_format($availableNumbers, 0, ',', '.') }}</p>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-[#0d0d14] p-6">
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400">Reservados</p>
                        <p class="mt-3 text-3xl font-black text-amber-400">{{ number_format($reservedNumbers, 0, ',', '.') }}</p>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-[#0d0d14] p-6">
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400">Pagos</p>
                        <p class="mt-3 text-3xl font-black text-orange-400">{{ number_format($paidNumbers, 0, ',', '.') }}</p>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-[#0d0d14] p-6">
                        <p class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400">Valor unitário</p>
                        <p class="mt-3 text-3xl font-black text-white">R$ {{ $formattedTicketPrice }}</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="premio" class="py-8">
        <div class="mx-auto grid max-w-7xl gap-6 px-4 sm:px-6 lg:grid-cols-[1.2fr_0.8fr] lg:px-8">
            <div class="rounded-[2rem] border border-white/10 bg-white/5 p-6 backdrop-blur sm:p-8">
                <p class="text-sm font-bold uppercase tracking-[0.25em] text-orange-300">Sobre o prêmio</p>
                <h3 class="mt-3 text-2xl font-black text-white sm:text-3xl">{{ $prizeTitle }}</h3>
                <p class="mt-4 leading-7 text-zinc-300">
                    Um prêmio de alto nível para quem curte desempenho, fluidez e experiência gamer premium. Participe escolhendo seus números, faça a reserva com sua conta e envie a mensagem para confirmação manual do pagamento.
                </p>

                <div class="mt-6 grid gap-4 sm:grid-cols-2">
                    <div class="rounded-2xl border border-white/10 bg-black/20 p-5">
                        <p class="text-sm font-bold text-white">Reserva vinculada ao usuário</p>
                        <p class="mt-2 text-sm leading-6 text-zinc-400">
                            Cada número reservado fica ligado ao login da pessoa, evitando conflitos e facilitando a confirmação.
                        </p>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-black/20 p-5">
                        <p class="text-sm font-bold text-white">Confirmação manual</p>
                        <p class="mt-2 text-sm leading-6 text-zinc-400">
                            Após a mensagem enviada, o organizador confirma o pagamento e o número passa para comprado.
                        </p>
                    </div>
                </div>
            </div>

            <div id="data-sorteio" class="rounded-[2rem] border border-white/10 bg-gradient-to-br from-orange-500/10 to-red-600/10 p-6 backdrop-blur sm:p-8">
                <p class="text-sm font-bold uppercase tracking-[0.25em] text-orange-300">Sorteio</p>
                <h3 class="mt-3 text-3xl font-black text-white">{{ $drawDate }}</h3>
                <p class="mt-4 leading-7 text-zinc-300">
                    A data do sorteio ficará visível no painel do participante e poderá ser atualizada pelo administrador quando necessário.
                </p>

                <div class="mt-8 rounded-2xl border border-white/10 bg-black/20 p-5">
                    <p class="text-xs font-bold uppercase tracking-[0.2em] text-zinc-400">Status da campanha</p>
                    <p class="mt-2 text-lg font-bold text-white">Reserva aberta</p>
                    <p class="mt-2 text-sm text-zinc-400">Entre, escolha seus números disponíveis e envie sua solicitação.</p>
                </div>
            </div>
        </div>
    </section>

    <section id="como-funciona" class="py-8">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="rounded-[2rem] border border-white/10 bg-white/5 p-6 backdrop-blur sm:p-8">
                <div class="max-w-2xl">
                    <p class="text-sm font-bold uppercase tracking-[0.25em] text-orange-300">Como funciona</p>
                    <h3 class="mt-2 text-2xl font-black text-white sm:text-3xl">Participar é simples</h3>
                    <p class="mt-4 text-zinc-300">
                        O site foi pensado para ser direto: você cria sua conta, escolhe os números disponíveis e envia a solicitação para confirmação.
                    </p>
                </div>

                <div class="mt-8 grid gap-4 md:grid-cols-2 xl:grid-cols-4">
                    <div class="rounded-2xl border border-white/10 bg-[#0d0d14] p-6">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-orange-500/15 text-lg font-black text-orange-300">1</span>
                        <h4 class="mt-4 text-lg font-bold text-white">Crie sua conta</h4>
                        <p class="mt-2 text-sm leading-6 text-zinc-400">Faça seu cadastro para vincular seus números ao seu login.</p>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-[#0d0d14] p-6">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-orange-500/15 text-lg font-black text-orange-300">2</span>
                        <h4 class="mt-4 text-lg font-bold text-white">Escolha os números</h4>
                        <p class="mt-2 text-sm leading-6 text-zinc-400">Selecione apenas os números disponíveis e monte sua reserva.</p>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-[#0d0d14] p-6">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-orange-500/15 text-lg font-black text-orange-300">3</span>
                        <h4 class="mt-4 text-lg font-bold text-white">Envie a mensagem</h4>
                        <p class="mt-2 text-sm leading-6 text-zinc-400">Depois da reserva, entre em contato para realizar o pagamento.</p>
                    </div>

                    <div class="rounded-2xl border border-white/10 bg-[#0d0d14] p-6">
                        <span class="inline-flex h-10 w-10 items-center justify-center rounded-xl bg-orange-500/15 text-lg font-black text-orange-300">4</span>
                        <h4 class="mt-4 text-lg font-bold text-white">Aguarde a confirmação</h4>
                        <p class="mt-2 text-sm leading-6 text-zinc-400">Com o pagamento confirmado, seus números passam para comprados no painel.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section class="py-10">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="overflow-hidden rounded-[2rem] border border-orange-400/15 bg-gradient-to-r from-orange-500/15 via-red-600/10 to-pink-500/10 p-8 shadow-[0_20px_60px_rgba(255,90,31,0.10)]">
                <div class="flex flex-col gap-8 lg:flex-row lg:items-center lg:justify-between">
                    <div class="max-w-2xl">
                        <p class="text-sm font-bold uppercase tracking-[0.25em] text-orange-300">Participe agora</p>
                        <h3 class="mt-2 text-3xl font-black text-white sm:text-4xl">
                            Reserve seus números e acompanhe tudo pela sua conta
                        </h3>
                        <p class="mt-4 text-zinc-300">
                            Visualize o prêmio, acompanhe a disponibilidade, confira a data do sorteio e envie sua solicitação de reserva com poucos cliques.
                        </p>
                    </div>

                    <div class="flex flex-col gap-3 sm:flex-row">
                        <a href="{{ route('register') }}"
                           class="inline-flex items-center justify-center rounded-2xl bg-gradient-to-r from-orange-500 to-red-600 px-6 py-3 text-sm font-black uppercase tracking-wide text-white shadow-[0_10px_30px_rgba(255,90,31,0.25)] transition hover:scale-[1.02]">
                            Criar conta
                        </a>

                        <a href="{{ $whatsLink }}"
                           target="_blank"
                           class="inline-flex items-center justify-center rounded-2xl border border-white/15 bg-white/5 px-6 py-3 text-sm font-bold uppercase tracking-wide text-zinc-100 transition hover:border-white/30 hover:bg-white/10">
                            Falar no WhatsApp
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection