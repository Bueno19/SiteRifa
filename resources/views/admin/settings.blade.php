@extends('layouts.admin', ['title' => 'Configurações'])

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
                <p class="font-bold">Não foi possível salvar</p>
                <ul class="mt-2 space-y-1 text-sm">
                    @foreach ($errors->all() as $error)
                        <li>• {{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <section class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
            <p class="text-sm font-bold uppercase tracking-[0.25em] text-orange-300">Configurações</p>
            <h3 class="mt-2 text-2xl font-black text-white">Campanha principal</h3>
            <p class="mt-3 text-zinc-300">
                Ajuste os dados principais que aparecem para os usuários e que controlam o funcionamento da rifa.
            </p>

            <form action="{{ route('admin.settings.update') }}" method="POST" class="mt-8 space-y-6">
                @csrf

                <div class="grid gap-6 md:grid-cols-2">
                    <div>
                        <label class="mb-2 block text-sm font-semibold text-zinc-200">Título da campanha</label>
                        <input
                            type="text"
                            name="titulo"
                            value="{{ old('titulo', $config['titulo']) }}"
                            class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white outline-none transition focus:border-orange-400/50 focus:ring-2 focus:ring-orange-500/20"
                            required
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-zinc-200">Status da campanha</label>
                        <select
                            name="status"
                            class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white outline-none transition focus:border-orange-400/50 focus:ring-2 focus:ring-orange-500/20"
                        >
                            <option value="ativa" {{ old('status', $config['status']) === 'ativa' ? 'selected' : '' }}>Ativa</option>
                            <option value="pausada" {{ old('status', $config['status']) === 'pausada' ? 'selected' : '' }}>Pausada</option>
                            <option value="finalizada" {{ old('status', $config['status']) === 'finalizada' ? 'selected' : '' }}>Finalizada</option>
                        </select>
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-zinc-200">Valor por número</label>
                        <input
                            type="number"
                            step="0.01"
                            min="0"
                            name="valor_numero"
                            value="{{ old('valor_numero', $config['valor_numero']) }}"
                            class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white outline-none transition focus:border-orange-400/50 focus:ring-2 focus:ring-orange-500/20"
                            required
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-zinc-200">Total de números</label>
                        <input
                            type="number"
                            min="1"
                            name="total_numeros"
                            value="{{ old('total_numeros', $config['total_numeros']) }}"
                            class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white outline-none transition focus:border-orange-400/50 focus:ring-2 focus:ring-orange-500/20"
                            required
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-zinc-200">WhatsApp</label>
                        <input
                            type="text"
                            name="whatsapp"
                            value="{{ old('whatsapp', $config['whatsapp']) }}"
                            placeholder="5511999999999"
                            class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white outline-none transition focus:border-orange-400/50 focus:ring-2 focus:ring-orange-500/20"
                        >
                    </div>

                    <div>
                        <label class="mb-2 block text-sm font-semibold text-zinc-200">Data do sorteio</label>
                        <input
                            type="date"
                            name="data_sorteio"
                            value="{{ old('data_sorteio', $config['data_sorteio']) }}"
                            class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white outline-none transition focus:border-orange-400/50 focus:ring-2 focus:ring-orange-500/20"
                        >
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-zinc-200">Imagem / banner</label>
                        <input
                            type="text"
                            name="imagem_banner"
                            value="{{ old('imagem_banner', $config['imagem_banner']) }}"
                            placeholder="URL ou caminho da imagem"
                            class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white outline-none transition focus:border-orange-400/50 focus:ring-2 focus:ring-orange-500/20"
                        >
                    </div>

                    <div class="md:col-span-2">
                        <label class="mb-2 block text-sm font-semibold text-zinc-200">Descrição / prêmio</label>
                        <textarea
                            name="descricao"
                            rows="4"
                            class="w-full rounded-2xl border border-white/10 bg-black/20 px-4 py-3 text-white outline-none transition focus:border-orange-400/50 focus:ring-2 focus:ring-orange-500/20"
                        >{{ old('descricao', $config['descricao']) }}</textarea>
                    </div>
                </div>

                <div class="flex flex-wrap gap-3">
                    <button
                        type="submit"
                        class="rounded-2xl bg-gradient-to-r from-orange-500 to-red-600 px-6 py-3 text-sm font-black uppercase tracking-wide text-white"
                    >
                        Salvar configurações
                    </button>

                    <a href="{{ route('admin.dashboard') }}"
                       class="rounded-2xl border border-white/10 px-6 py-3 text-sm font-bold text-zinc-200 transition hover:bg-white/5">
                        Voltar ao dashboard
                    </a>
                </div>
            </form>
        </section>
    </div>
@endsection