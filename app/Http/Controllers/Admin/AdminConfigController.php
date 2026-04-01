<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminConfigController extends Controller
{
    public function index()
    {
        if (! Schema::hasTable('rifas')) {
            return redirect()
                ->route('admin.dashboard')
                ->with('error', 'A tabela de configurações da campanha ainda não existe.');
        }

        $rifa = DB::table('rifas')->orderBy('id')->first();

        $config = [
            'titulo'         => $rifa->titulo ?? 'Mega Rifa Gamer',
            'descricao'      => $rifa->descricao ?? 'Monitor ASUS ROG Gaming 360Hz',
            'valor_numero'   => isset($rifa->valor_numero) ? (float) $rifa->valor_numero : 2.50,
            'total_numeros'  => isset($rifa->total_numeros) ? (int) $rifa->total_numeros : 4500,
            'status'         => $rifa->status ?? 'ativa',
            'imagem_banner'  => $rifa->imagem_banner ?? null,
            'whatsapp'       => $rifa->whatsapp ?? env('RAFFLE_WHATSAPP', ''),
            'data_sorteio'   => $rifa->data_sorteio ?? null,
        ];

        return view('admin.settings', [
            'title'         => 'Configurações',
            'campaignTitle' => $config['titulo'],
            'adminName'     => 'Administrador',
            'supportLink'   => $config['whatsapp']
                ? 'https://wa.me/' . preg_replace('/\D+/', '', $config['whatsapp'])
                : '#',
            'config'        => $config,
        ]);
    }

    public function update(Request $request)
    {
        if (! Schema::hasTable('rifas')) {
            return redirect()
                ->route('admin.dashboard')
                ->with('error', 'A tabela de configurações da campanha ainda não existe.');
        }

        $validated = $request->validate([
            'titulo'        => ['required', 'string', 'max:255'],
            'descricao'     => ['nullable', 'string'],
            'valor_numero'  => ['required', 'numeric', 'min:0'],
            'total_numeros' => ['required', 'integer', 'min:1'],
            'status'        => ['required', 'string', 'max:50'],
            'imagem_banner' => ['nullable', 'string', 'max:2048'],
            'whatsapp'      => ['nullable', 'string', 'max:30'],
            'data_sorteio'  => ['nullable', 'date'],
        ]);

        $first = DB::table('rifas')->orderBy('id')->first();

        $data = [
            'titulo'        => $validated['titulo'],
            'descricao'     => $validated['descricao'] ?? null,
            'valor_numero'  => $validated['valor_numero'],
            'total_numeros' => $validated['total_numeros'],
            'status'        => $validated['status'],
            'imagem_banner' => $validated['imagem_banner'] ?? null,
            'whatsapp'      => $validated['whatsapp'] ?? null,
            'data_sorteio'  => $validated['data_sorteio'] ?? null,
            'updated_at'    => now(),
        ];

        if ($first) {
            DB::table('rifas')
                ->where('id', $first->id)
                ->update($data);
        } else {
            $data['created_at'] = now();
            DB::table('rifas')->insert($data);
        }

        return redirect()
            ->route('admin.settings')
            ->with('success', 'Configurações da campanha atualizadas com sucesso.');
    }
}