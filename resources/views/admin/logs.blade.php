@extends('layouts.admin', ['title' => 'Logs'])

@section('content')
    <div class="space-y-8">
        <section class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
            <p class="text-sm font-bold uppercase tracking-[0.25em] text-orange-300">Logs</p>
            <h3 class="mt-2 text-2xl font-black text-white">Histórico de ações do sistema</h3>
            <p class="mt-3 text-zinc-300">
                Aqui você acompanha confirmações, cancelamentos e futuras alterações administrativas.
            </p>
        </section>

        <section class="rounded-[1.75rem] border border-white/10 bg-white/5 p-6">
            <div class="overflow-x-auto">
                <table class="min-w-full text-left">
                    <thead>
                        <tr class="border-b border-white/10 text-sm uppercase tracking-wide text-zinc-400">
                            <th class="px-4 py-3">Data</th>
                            <th class="px-4 py-3">Responsável</th>
                            <th class="px-4 py-3">Ação</th>
                            <th class="px-4 py-3">Alvo</th>
                            <th class="px-4 py-3">ID Entidade</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($logs as $log)
                            <tr class="border-b border-white/5">
                                <td class="px-4 py-4 text-sm text-zinc-300">
                                    {{ $log->created_at ? \Carbon\Carbon::parse($log->created_at)->format('d/m/Y H:i') : '-' }}
                                </td>
                                <td class="px-4 py-4 text-sm font-semibold text-white">
                                    {{ $log->user_name ?? 'Sistema' }}
                                </td>
                                <td class="px-4 py-4 text-sm text-zinc-300">
                                    {{ $log->action ?? '-' }}
                                </td>
                                <td class="px-4 py-4 text-sm text-zinc-300">
                                    {{ $log->target ?? '-' }}
                                </td>
                                <td class="px-4 py-4 text-sm text-zinc-300">
                                    {{ $log->entity_id ?? '-' }}
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="px-4 py-6 text-sm text-zinc-500">
                                    Nenhum log registrado ainda.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            @if(method_exists($logs, 'links'))
                <div class="mt-6">
                    {{ $logs->links() }}
                </div>
            @endif
        </section>
    </div>
@endsection