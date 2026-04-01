<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\RaffleService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class AdminLogController extends Controller
{
    public function __construct(
        private readonly RaffleService $raffleService
    ) {
    }

    public function index()
    {
        $campaign = $this->raffleService->campaignData();

        $logsTable = $this->table(['system_logs', 'logs_sistema', 'audit_logs', 'logs']);

        if (! $logsTable) {
            return view('admin.logs', [
                'title'         => 'Logs',
                'campaignTitle' => $campaign['campaignTitle'],
                'adminName'     => 'Administrador',
                'supportLink'   => $this->raffleService->whatsappLink(
                    $campaign['whatsappNumber'],
                    'Olá! Quero falar sobre logs da rifa.'
                ),
                'logs'          => collect(),
            ]);
        }

        $idCol = $this->column($logsTable, ['id']);
        $actionCol = $this->column($logsTable, ['action', 'acao']);
        $targetCol = $this->column($logsTable, ['target', 'alvo', 'entity', 'entidade']);
        $entityIdCol = $this->column($logsTable, ['entity_id', 'entidade_id']);
        $userIdCol = $this->column($logsTable, ['user_id', 'usuario_id', 'admin_id']);
        $userNameCol = $this->column($logsTable, ['user_name', 'usuario_nome', 'responsavel']);
        $createdAtCol = $this->column($logsTable, ['created_at', 'data_criacao']);

        $logs = DB::table($logsTable)
            ->select([
                $idCol . ' as id',
                ($actionCol ? DB::raw($actionCol . ' as action') : DB::raw('NULL as action')),
                ($targetCol ? DB::raw($targetCol . ' as target') : DB::raw('NULL as target')),
                ($entityIdCol ? DB::raw($entityIdCol . ' as entity_id') : DB::raw('NULL as entity_id')),
                ($userIdCol ? DB::raw($userIdCol . ' as user_id') : DB::raw('NULL as user_id')),
                ($userNameCol ? DB::raw($userNameCol . ' as user_name') : DB::raw('NULL as user_name')),
                ($createdAtCol ? DB::raw($createdAtCol . ' as created_at') : DB::raw('NULL as created_at')),
            ])
            ->orderByDesc($idCol)
            ->paginate(30);

        return view('admin.logs', [
            'title'         => 'Logs',
            'campaignTitle' => $campaign['campaignTitle'],
            'adminName'     => 'Administrador',
            'supportLink'   => $this->raffleService->whatsappLink(
                $campaign['whatsappNumber'],
                'Olá! Quero falar sobre logs da rifa.'
            ),
            'logs'          => $logs,
        ]);
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