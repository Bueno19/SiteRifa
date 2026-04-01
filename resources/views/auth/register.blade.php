@extends('layouts.auth', ['title' => 'Cadastro'])

@section('content')
    <div class="grid w-full max-w-6xl overflow-hidden rounded-[2rem] border border-white/10 bg-white/5 shadow-[0_30px_80px_rgba(0,0,0,0.45)] backdrop-blur lg:grid-cols-2">
        <div class="hidden flex-col justify-between bg-gradient-to-br from-orange-500/20 via-red-600/10 to-transparent p-10 lg:flex">
            <div>
                <p class="text-sm font-bold uppercase tracking-[0.3em] text-orange-300">Novo participante</p>
                <h1 class="mt-4 text-5xl font-black uppercase leading-none text-white">
                    Crie sua conta
                </h1>
                <p class="mt-6 max-w-md text-base leading-7 text-zinc-300">
                    Com seu cadastro você poderá reservar números, acompanhar pagamentos confirmados e ver todas as informações da rifa no seu painel.
                </p>
            </div>

            <div class="grid gap-4">
                <div class="rounded-2xl border border-white/10 bg-black/20 p-5">
                    <p class="text-sm font-bold text-white">Reservas vinculadas ao login</p>
                    <p class="mt-2 text-sm leading-6 text-zinc-400">Cada seleção de números fica conectada à sua conta.</p>
                </div>
                <div class="rounded-2xl border border-white/10 bg-black/20 p-5">
                    <p class="text-sm font-bold text-white">Painel pessoal</p>
                    <p class="mt-2 text-sm leading-6 text-zinc-400">Visualize o que reservou, o que já foi pago e quanto você gastou.</p>
                </div>
            </div>
        </div>

        <div class="p-6 sm:p-10">
            <div class="mx-auto max-w-md">
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-sm font-semibold text-orange-300 transition hover:text-orange-200">
                    ← Voltar ao site
                </a>

                <h2 class="mt-6 text-3xl font-black text-white">Criar conta</h2>
                <p class="mt-2 text-zinc-400">Preencha seus dados para participar da rifa.</p>

                @if ($errors->any())
                    <div class="mt-6 rounded-2xl border border-red-400/20 bg-red-500/10 p-4 text-red-300">
                        <p class="font-bold">Não foi possível criar a conta</p>
                        <ul class="mt-2 space-y-1 text-sm">
                            @foreach ($errors->all() as $error)
                                <li>• {{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('register.store') }}" method="POST" class="mt-8 space-y-5">
                    @csrf

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-zinc-200">Nome completo</label>
                        <input
                            type="text"
                            name="name"
                            value="{{ old('name') }}"
                            placeholder="Seu nome"
                            class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white outline-none transition placeholder:text-zinc-500 focus:border-orange-400/50 focus:ring-2 focus:ring-orange-500/20"
                            required
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-zinc-200">Telefone / WhatsApp</label>
                        <input
                            type="text"
                            name="phone"
                            value="{{ old('phone') }}"
                            placeholder="(00) 00000-0000"
                            class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white outline-none transition placeholder:text-zinc-500 focus:border-orange-400/50 focus:ring-2 focus:ring-orange-500/20"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-zinc-200">Email</label>
                        <input
                            type="email"
                            name="email"
                            value="{{ old('email') }}"
                            placeholder="seuemail@exemplo.com"
                            class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white outline-none transition placeholder:text-zinc-500 focus:border-orange-400/50 focus:ring-2 focus:ring-orange-500/20"
                            required
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-zinc-200">Senha</label>
                        <input
                            type="password"
                            name="password"
                            placeholder="••••••••"
                            class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white outline-none transition placeholder:text-zinc-500 focus:border-orange-400/50 focus:ring-2 focus:ring-orange-500/20"
                            required
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-zinc-200">Confirmar senha</label>
                        <input
                            type="password"
                            name="password_confirmation"
                            placeholder="••••••••"
                            class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white outline-none transition placeholder:text-zinc-500 focus:border-orange-400/50 focus:ring-2 focus:ring-orange-500/20"
                            required
                        >
                    </div>

                    <button
                        type="submit"
                        class="w-full rounded-2xl bg-gradient-to-r from-orange-500 to-red-600 px-6 py-3 text-sm font-black uppercase tracking-wide text-white shadow-[0_10px_30px_rgba(255,90,31,0.25)] transition hover:scale-[1.01]"
                    >
                        Criar conta
                    </button>
                </form>

                <div class="mt-6 rounded-2xl border border-white/10 bg-black/20 p-4">
                    <p class="text-sm text-zinc-400">
                        Já tem conta?
                        <a href="{{ route('login') }}" class="font-bold text-orange-300 transition hover:text-orange-200">Entrar agora</a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection