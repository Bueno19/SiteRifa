<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\RaffleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminNumberController extends Controller
{
    public function __construct(
        private readonly RaffleService $raffleService
    ) {
    }

    public function index(Request $request)
    {
        $campaign = $this->raffleService->campaignData();

        $numbersTable = $this->table(['numbers', 'raffle_numbers', 'numeros']);

        if (! $numbersTable) {
            return redirect()
                ->route('admin.dashboard')
                ->with('error', 'A tabela de números ainda não foi encontrada.');
        }

        $idCol = $this->column($numbersTable, ['id']);
        $numberCol = $this->column($numbersTable, ['number', 'numero']);
        $statusCol = $this->column($numbersTable, ['status', 'situacao']);
        $reservationCol = $this->column($numbersTable, ['reservation_id', 'reserva_id']);
        $userCol = $this->column($numbersTable, ['user_id', 'usuario_id']);
        $updatedAtCol = $this->column($numbersTable, ['updated_at', 'data_atualizacao']);

        if (! $idCol || ! $numberCol) {
            return redirect()
                ->route('admin.dashboard')
                ->with('error', 'A estrutura da tabela de números não está compatível.');
        }

        $query = DB::table($numbersTable)->select([
            DB::raw($idCol . ' as id'),
            DB::raw($numberCol . ' as number'),
            $statusCol ? DB::raw($statusCol . ' as status') : DB::raw("'disponivel' as status"),
            $reservationCol ? DB::raw($reservationCol . ' as reservation_id') : DB::raw('NULL as reservation_id'),
            $userCol ? DB::raw($userCol . ' as user_id') : DB::raw('NULL as user_id'),
            $updatedAtCol ? DB::raw($updatedAtCol . ' as updated_at') : DB::raw('NULL as updated_at'),
        ]);

        if ($request->filled('q')) {
            $q = preg_replace('/\D+/', '', (string) $request->q);
            if ($q !== '') {
                $query->where($numberCol, $q);
            }
        }

        if ($request->filled('status') && in_array($request->status, ['disponivel', 'reservado', 'pago'], true)) {
            if ($statusCol) {
                $query->where($statusCol, $request->status);
            }
        }

        $numbers = $query
            ->orderBy($numberCol)
            ->paginate(150)
            ->withQueryString();

        return view('admin.numbers', [
            'title'         => 'Números',
            'campaignTitle' => $campaign['campaignTitle'],
            'adminName'     => 'Administrador',
            'supportLink'   => $this->raffleService->whatsappLink(
                $campaign['whatsappNumber'],
                'Olá! Quero falar sobre números da rifa.'
            ),
            'numbers'       => $numbers,
            'filters'       => [
                'q'      => $request->get('q', ''),
                'status' => $request->get('status', ''),
            ],
        ]);
    }

    public function updateStatus(Request $request, int $id)
    {
        $validated = $request->validate([
            'status' => ['required', 'in:disponivel,reservado,pago'],
        ]);

        $numbersTable = $this->table(['numbers', 'raffle_numbers', 'numeros']);

        if (! $numbersTable) {
            return redirect()
                ->route('admin.dashboard')
                ->with('error', 'A tabela de números ainda não foi encontrada.');
        }

        $idCol = $this->column($numbersTable, ['id']);
        $statusCol = $this->column($numbersTable, ['status', 'situacao']);
        $reservationCol = $this->column($numbersTable, ['reservation_id', 'reserva_id']);
        $userCol = $this->column($numbersTable, ['user_id', 'usuario_id']);
        $updatedAtCol = $this->column($numbersTable, ['updated_at', 'data_atualizacao']);

        if (! $idCol || ! $statusCol) {
            return redirect()
                ->route('admin.numbers')
                ->with('error', 'A estrutura da tabela de números não está compatível.');
        }

        $updateData = [
            $statusCol => $validated['status'],
        ];

        if ($updatedAtCol) {
            $updateData[$updatedAtCol] = now();
        }

        if ($validated['status'] === 'disponivel') {
            if ($reservationCol) {
                $updateData[$reservationCol] = null;
            }

            if ($userCol) {
                $updateData[$userCol] = null;
            }
        }

        DB::table($numbersTable)
            ->where($idCol, $id)
            ->update($updateData);

        return redirect()->to(route('admin.numbers') . '?' . http_build_query([
            'q' => $request->input('q'),
            'status' => $request->input('status_filter'),
            'page' => $request->input('page'),
        ]))->with('success', 'Status do número atualizado com sucesso.');
    }

    protected function table(array $candidates): ?string
    {
        foreach ($candidates as $table) {
            if (Schema::hasTable($table)) {
                return $table;
            }
        }

        return null;
    }

    protected function column(?string $table, array $candidates): ?string
    {
        if (! $table || ! Schema::hasTable($table)) {
            return null;
        }

        try {
            $columns = Schema::getColumnListing($table);
        } catch (\Throwable $e) {
            return $candidates[0] ?? null;
        }

        foreach ($candidates as $column) {
            if (in_array($column, $columns, true)) {
                return $column;
            }
        }

        return null;
    }
}