@extends('layouts.admin', ['title' => 'Usuários'])

@section('content')
    <div class="space-y-8">
        @if (session('success'))
            <div class="rounded-2xl border border-emerald-400/20 bg-emerald-500/10 px-5 py-4 text-emerald-300">
                <p class="font-bold">Sucesso</p>
                <p class="mt-1 text-sm">{{ session('success') }}</p>
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
            <p class="text-sm font-bold uppercase tracking-[0.25em] text-orange-300">Usuários</p>
            <h3 class="mt-2 text-2xl font-black text-white">Gerenciamento de contas</h3>
            <p class="mt-3 text-zinc-300">
                Aqui o administrador pode ajustar o perfil de acesso e redefinir a senha dos participantes.
            </p>
        </section>

        <section class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead>
                        <tr class="border-b border-white/10 text-sm uppercase tracking-wide text-zinc-400">
                            <th class="px-4 py-3">Usuário</th>
                            <th class="px-4 py-3">Perfil</th>
                            <th class="px-4 py-3">Criado em</th>
                            <th class="px-4 py-3">Alterar perfil</th>
                            <th class="px-4 py-3">Redefinir senha</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($users as $user)
                            <tr class="border-b border-white/5 align-top">
                                <td class="px-4 py-4">
                                    <p class="font-semibold text-white">{{ $user['name'] }}</p>
                                    <p class="text-sm text-zinc-400">{{ $user['email'] }}</p>
                                    <p class="mt-2 text-xs uppercase tracking-wide text-zinc-500">ID {{ $user['id'] }}</p>
                                </td>

                                <td class="px-4 py-4">
                                    <span class="rounded-full border border-orange-400/20 bg-orange-500/10 px-3 py-1 text-xs font-bold uppercase tracking-wide text-orange-300">
                                        {{ $user['role'] }}
                                    </span>
                                </td>

                                <td class="px-4 py-4 text-sm text-zinc-300">
                                    {{ $user['created_at'] }}
                                </td>

                                <td class="px-4 py-4">
                                    <form action="{{ route('admin.users.role', $user['id']) }}" method="POST" class="space-y-2">
                                        @csrf

                                        <select
                                            name="role"
                                            class="w-full rounded-xl border border-white/10 bg-black/20 px-3 py-2 text-sm text-white outline-none transition focus:border-orange-400/50 focus:ring-2 focus:ring-orange-500/20"
                                        >
                                            <option value="user" {{ $user['role'] === 'user' ? 'selected' : '' }}>Usuário</option>
                                            <option value="admin" {{ $user['role'] === 'admin' ? 'selected' : '' }}>Administrador</option>
                                        </select>

                                        <button
                                            type="submit"
                                            class="w-full rounded-xl border border-white/10 px-3 py-2 text-xs font-bold text-zinc-200 transition hover:bg-white/5"
                                        >
                                            Salvar perfil
                                        </button>
                                    </form>
                                </td>

                                <td class="px-4 py-4">
                                    <form action="{{ route('admin.users.password', $user['id']) }}" method="POST" class="space-y-2">
                                        @csrf

                                        <input
                                            type="password"
                                            name="password"
                                            placeholder="Nova senha"
                                            class="w-full rounded-xl border border-white/10 bg-black/20 px-3 py-2 text-sm text-white outline-none transition placeholder:text-zinc-500 focus:border-orange-400/50 focus:ring-2 focus:ring-orange-500/20"
                                            required
                                        >

                                        <input
                                            type="password"
                                            name="password_confirmation"
                                            placeholder="Confirmar senha"
                                            class="w-full rounded-xl border border-white/10 bg-black/20 px-3 py-2 text-sm text-white outline-none transition placeholder:text-zinc-500 focus:border-orange-400/50 focus:ring-2 focus:ring-orange-500/20"
                                            required
                                        >

                                        <button
                                            type="submit"
                                            class="w-full rounded-xl bg-gradient-to-r from-orange-500 to-red-600 px-3 py-2 text-xs font-bold text-white"
                                        >
                                            Redefinir senha
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-sm text-zinc-500">
                                    Nenhum usuário encontrado.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </section>
    </div>
@endsection