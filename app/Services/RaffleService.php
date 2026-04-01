<?php

namespace App\Services;

use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class RaffleService
{
    public function campaignData(): array
    {
        $settingsTable = $this->table([
            'campaign_settings',
            'raffle_settings',
            'settings',
            'configuracoes',
            'rifas',
        ]);

        $numbersTable = $this->table([
            'numbers',
            'raffle_numbers',
            'numeros',
        ]);

        $settings = $settingsTable ? DB::table($settingsTable)->first() : null;

        $titleCol = $this->column($settingsTable, [
            'campaign_title',
            'title',
            'titulo',
            'nome_campanha',
        ]);

        $prizeCol = $this->column($settingsTable, [
            'prize_title',
            'prize_name',
            'premio',
            'nome_premio',
            'descricao',
        ]);

        $priceCol = $this->column($settingsTable, [
            'ticket_price',
            'valor_numero',
            'preco_numero',
            'price',
        ]);

        $drawDateCol = $this->column($settingsTable, [
            'draw_date',
            'data_sorteio',
        ]);

        $whatsappCol = $this->column($settingsTable, [
            'whatsapp_number',
            'telefone_whatsapp',
            'whatsapp',
            'telefone',
        ]);

        $totalNumbersCol = $this->column($settingsTable, [
            'total_numbers',
            'total_numeros',
            'quantidade_numeros',
        ]);

        $numberCol = $this->column($numbersTable, ['number', 'numero']);
        $statusCol = $this->column($numbersTable, ['status', 'situacao']);

        $campaignTitle = $settings && $titleCol
            ? ($settings->{$titleCol} ?? 'Mega Rifa Gamer')
            : 'Mega Rifa Gamer';

        $prizeTitle = $settings && $prizeCol
            ? ($settings->{$prizeCol} ?? 'Monitor ASUS ROG Gaming 360Hz')
            : 'Monitor ASUS ROG Gaming 360Hz';

        $ticketPrice = $settings && $priceCol
            ? (float) ($settings->{$priceCol} ?? 2.50)
            : 2.50;

        $drawDate = $settings && $drawDateCol
            ? $this->formatDate($settings->{$drawDateCol} ?? null)
            : 'A definir';

        $whatsappNumber = $settings && $whatsappCol
            ? preg_replace('/\D+/', '', (string) ($settings->{$whatsappCol} ?? ''))
            : env('RAFFLE_WHATSAPP', '5511999999999');

        if (! $whatsappNumber) {
            $whatsappNumber = env('RAFFLE_WHATSAPP', '5511999999999');
        }

        $totalNumbers = 0;
        $reservedNumbers = 0;
        $paidNumbers = 0;
        $availableNumbers = 0;

        if ($numbersTable && $numberCol) {
            $rows = DB::table($numbersTable)
                ->select([$numberCol, $statusCol ?: DB::raw('NULL as status')])
                ->get();

            $totalNumbers = $rows->count();

            foreach ($rows as $row) {
                $normalized = $this->normalizeNumberStatus(
                    $statusCol ? ($row->{$statusCol} ?? null) : null
                );

                if ($normalized === 'pago') {
                    $paidNumbers++;
                } elseif ($normalized === 'reservado') {
                    $reservedNumbers++;
                } else {
                    $availableNumbers++;
                }
            }
        } elseif ($settings && $totalNumbersCol) {
            $totalNumbers = (int) ($settings->{$totalNumbersCol} ?? 0);
        }

        return [
            'campaignTitle'    => $campaignTitle,
            'prizeTitle'       => $prizeTitle,
            'ticketPrice'      => $ticketPrice,
            'totalNumbers'     => $totalNumbers,
            'reservedNumbers'  => $reservedNumbers,
            'paidNumbers'      => $paidNumbers,
            'availableNumbers' => $availableNumbers,
            'drawDate'         => $drawDate,
            'whatsappNumber'   => $whatsappNumber,
        ];
    }

    public function whatsappLink(
        string $number = '',
        string $message = 'Olá! Quero falar sobre minha participação na rifa.'
    ): string {
        $cleanNumber = preg_replace('/\D+/', '', $number);

        if (! $cleanNumber) {
            return '#';
        }

        return 'https://wa.me/' . $cleanNumber . '?text=' . rawurlencode($message);
    }

    public function reservationWhatsappMessage(
    string $userName,
    array $numbers,
    float $totalValue
    ): string {
    $formattedNumbers = collect($numbers)
        ->map(fn ($number) => str_pad((int) $number, 4, '0', STR_PAD_LEFT))
        ->implode(', ');

    $formattedValue = number_format($totalValue, 2, ',', '.');

    return "Olá! Meu nome é {$userName}.\n"
        . "Acabei de reservar os números: {$formattedNumbers}.\n"
        . "Valor total: R$ {$formattedValue}.\n"
        . "Quero enviar o comprovante para confirmação do pagamento.";
    }

    public function numbersDataset(): array
    {
        $campaign = $this->campaignData();

        $numbersTable = $this->table(['numbers', 'raffle_numbers', 'numeros']);
        $numberCol = $this->column($numbersTable, ['number', 'numero']);
        $statusCol = $this->column($numbersTable, ['status', 'situacao']);

        if (! $numbersTable || ! $numberCol) {
            return [
                'numbers'        => [],
                'totalNumbers'   => 0,
                'availableCount' => 0,
                'reservedCount'  => 0,
                'paidCount'      => 0,
                'ticketPrice'    => $campaign['ticketPrice'],
            ];
        }

        $rows = DB::table($numbersTable)
            ->select([
                $numberCol . ' as number',
                $statusCol ? DB::raw($statusCol . ' as status') : DB::raw('NULL as status'),
            ])
            ->orderBy($numberCol)
            ->get();

        $numbers = [];
        $availableCount = 0;
        $reservedCount = 0;
        $paidCount = 0;

        foreach ($rows as $row) {
            $normalizedStatus = $this->normalizeNumberStatus($row->status ?? null);

            if ($normalizedStatus === 'pago') {
                $paidCount++;
            } elseif ($normalizedStatus === 'reservado') {
                $reservedCount++;
            } else {
                $availableCount++;
            }

            $numbers[] = [
                'number' => (int) $row->number,
                'status' => $normalizedStatus,
            ];
        }

        return [
            'numbers'        => $numbers,
            'totalNumbers'   => count($numbers),
            'availableCount' => $availableCount,
            'reservedCount'  => $reservedCount,
            'paidCount'      => $paidCount,
            'ticketPrice'    => $campaign['ticketPrice'],
        ];
    }

    public function userDashboardData($user = null): array
    {
        $campaign = $this->campaignData();
        $userId = $user?->id ?? null;

        $entries = $userId ? $this->reservationEntries($userId) : [];

        $reservedEntries = array_values(array_filter(
            $entries,
            fn ($item) => $item['status_key'] === 'pendente'
        ));

        $paidEntries = array_values(array_filter(
            $entries,
            fn ($item) => $item['status_key'] === 'confirmado'
        ));

        $myReservedNumbers = collect($reservedEntries)
            ->pluck('numbers')
            ->flatten()
            ->unique()
            ->sort()
            ->values()
            ->all();

        $myPaidNumbers = collect($paidEntries)
            ->pluck('numbers')
            ->flatten()
            ->unique()
            ->sort()
            ->values()
            ->all();

        $userName = 'Usuário';

        if ($user) {
            $userName = $user->name ?? $user->nome ?? $user->email ?? 'Usuário';
        }

        $spentTotal = collect($paidEntries)->sum(fn ($item) => (float) ($item['value'] ?? 0));

        if ($spentTotal <= 0 && ! empty($myPaidNumbers)) {
        $spentTotal = count($myPaidNumbers) * (float) $campaign['ticketPrice'];
        }

        return [
            'campaignTitle'      => $campaign['campaignTitle'],
            'userName'           => $userName,
            'reservedCount'      => count($myReservedNumbers),
            'paidCount'          => count($myPaidNumbers),
            'spentTotal'         => $spentTotal,
            'availableNumbers'   => $campaign['availableNumbers'],
            'drawDate'           => $campaign['drawDate'],
            'whatsLink'          => $this->whatsappLink(
                $campaign['whatsappNumber'],
                'Olá! Quero falar sobre minhas reservas da rifa.'
            ),
            'myReservedNumbers'  => $myReservedNumbers,
            'myPaidNumbers'      => $myPaidNumbers,
            'reservationHistory' => collect($entries)
                ->map(fn ($item) => [
                    'date'    => $item['created_at'],
                    'numbers' => empty($item['numbers'])
                        ? '-'
                        : collect($item['numbers'])
                            ->map(fn ($n) => str_pad($n, 4, '0', STR_PAD_LEFT))
                            ->implode(', '),
                    'status'  => $item['status'],
                ])
                ->values()
                ->all(),
        ];
    }

    public function adminDashboardData(): array
    {
        $campaign = $this->campaignData();
        $entries = $this->reservationEntries();
        $logs = $this->logs();

        return [
            'campaignTitle'       => $campaign['campaignTitle'],
            'adminName'           => 'Administrador',
            'supportLink'         => $this->whatsappLink(
                $campaign['whatsappNumber'],
                'Olá! Quero falar sobre a administração da rifa.'
            ),
            'totalNumbers'        => $campaign['totalNumbers'],
            'availableNumbers'    => $campaign['availableNumbers'],
            'reservedNumbers'     => $campaign['reservedNumbers'],
            'paidNumbers'         => $campaign['paidNumbers'],
            'pendingReservations' => collect($entries)->where('status_key', 'pendente')->count(),
            'drawDate'            => $campaign['drawDate'],
            'pendingList'         => collect($entries)
                ->where('status_key', 'pendente')
                ->take(10)
                ->map(fn ($item) => [
                    'id'      => $item['id'],
                    'name'    => $item['name'],
                    'email'   => $item['email'],
                    'phone'   => $item['phone'],
                    'numbers' => empty($item['numbers'])
                        ? '-'
                        : collect($item['numbers'])
                            ->map(fn ($n) => str_pad($n, 4, '0', STR_PAD_LEFT))
                            ->implode(', '),
                    'value'   => $item['value'],
                    'status'  => $item['status'],
                ])
                ->values()
                ->all(),
            'recentLogs'          => $logs,
        ];
    }

    public function adminReservationsData(): array
    {
        $campaign = $this->campaignData();
        $entries = $this->reservationEntries();

        $pendingReservations = collect($entries)
            ->where('status_key', 'pendente')
            ->values()
            ->all();

        $confirmedReservations = collect($entries)
            ->where('status_key', 'confirmado')
            ->values()
            ->all();

        return [
            'campaignTitle'         => $campaign['campaignTitle'],
            'adminName'             => 'Administrador',
            'supportLink'           => $this->whatsappLink(
                $campaign['whatsappNumber'],
                'Olá! Quero falar sobre reservas da rifa.'
            ),
            'pendingReservations'   => $pendingReservations,
            'confirmedReservations' => $confirmedReservations,
            'pendingCount'          => count($pendingReservations),
            'confirmedCount'        => count($confirmedReservations),
            'pendingAmount'         => collect($pendingReservations)->sum('value'),
            'confirmedAmount'       => collect($confirmedReservations)->sum('value'),
        ];
    }

    public function reserve(array $selectedNumbers, ?int $userId): array
    {
        if (! $userId) {
            return [
                'ok'       => false,
                'redirect' => 'login',
                'message'  => 'Faça login antes de reservar números.',
            ];
        }

        $selected = collect($selectedNumbers)
            ->map(fn ($number) => (int) trim((string) $number))
            ->filter(fn ($number) => $number > 0)
            ->unique()
            ->values();

        if ($selected->isEmpty()) {
            return [
                'ok'       => false,
                'redirect' => 'numbers',
                'message'  => 'Selecione pelo menos um número.',
            ];
        }

        $campaign = $this->campaignData();

        $reservationsTable = $this->table(['reservations', 'reserva', 'reservas']);
        $numbersTable = $this->table(['numbers', 'raffle_numbers', 'numeros']);
        $pivotTable = $this->table(['reservation_numbers', 'reserva_numeros', 'reservation_number']);

        $reservationIdCol = $this->column($reservationsTable, ['id']);
        $reservationUserCol = $this->column($reservationsTable, ['user_id', 'usuario_id']);
        $reservationStatusCol = $this->column($reservationsTable, ['status', 'situacao']);
        $reservationTotalCol = $this->column($reservationsTable, ['total_value', 'valor_total', 'amount', 'total']);
        $reservationCreatedAtCol = $this->column($reservationsTable, ['created_at', 'data_criacao']);
        $reservationUpdatedAtCol = $this->column($reservationsTable, ['updated_at', 'data_atualizacao']);

        $numberIdCol = $this->column($numbersTable, ['id']);
        $numberValueCol = $this->column($numbersTable, ['number', 'numero']);
        $numberStatusCol = $this->column($numbersTable, ['status', 'situacao']);
        $numberReservationCol = $this->column($numbersTable, ['reservation_id', 'reserva_id']);
        $numberUserCol = $this->column($numbersTable, ['user_id', 'usuario_id']);
        $numberUpdatedAtCol = $this->column($numbersTable, ['updated_at', 'data_atualizacao']);

        $pivotReservationCol = $this->column($pivotTable, ['reservation_id', 'reserva_id']);
        $pivotNumberCol = $this->column($pivotTable, ['number_id', 'numero_id', 'raffle_number_id']);

        if (! $reservationsTable || ! $numbersTable || ! $reservationIdCol || ! $numberValueCol) {
            return [
                'ok'       => false,
                'redirect' => 'numbers',
                'message'  => 'Estrutura do banco ainda não está pronta para gravar reservas.',
            ];
        }

        $numberRows = DB::table($numbersTable)
            ->whereIn($numberValueCol, $selected->all())
            ->get();

        if ($numberRows->count() !== $selected->count()) {
            return [
                'ok'       => false,
                'redirect' => 'numbers',
                'message'  => 'Um ou mais números selecionados não existem no banco.',
            ];
        }

        foreach ($numberRows as $row) {
            $status = $numberStatusCol
                ? $this->normalizeNumberStatus($row->{$numberStatusCol} ?? null)
                : 'disponivel';

            if ($status !== 'disponivel') {
                return [
                    'ok'       => false,
                    'redirect' => 'numbers',
                    'message'  => 'Um ou mais números escolhidos não estão mais disponíveis.',
                ];
            }
        }

        DB::transaction(function () use (
            $userId,
            $selected,
            $campaign,
            $reservationsTable,
            $reservationUserCol,
            $reservationStatusCol,
            $reservationTotalCol,
            $reservationCreatedAtCol,
            $reservationUpdatedAtCol,
            $numbersTable,
            $numberIdCol,
            $numberValueCol,
            $numberStatusCol,
            $numberReservationCol,
            $numberUserCol,
            $numberUpdatedAtCol,
            $pivotTable,
            $pivotReservationCol,
            $pivotNumberCol
        ) {
            $now = now();

            $reservationInsert = [];

            if ($reservationUserCol) {
                $reservationInsert[$reservationUserCol] = $userId;
            }

            if ($reservationStatusCol) {
                $reservationInsert[$reservationStatusCol] = 'pendente';
            }

            if ($reservationTotalCol) {
                $reservationInsert[$reservationTotalCol] = count($selected) * (float) $campaign['ticketPrice'];
            }

            if ($reservationCreatedAtCol) {
                $reservationInsert[$reservationCreatedAtCol] = $now;
            }

            if ($reservationUpdatedAtCol) {
                $reservationInsert[$reservationUpdatedAtCol] = $now;
            }

            $reservationId = DB::table($reservationsTable)->insertGetId($reservationInsert);

            $selectedNumberRows = DB::table($numbersTable)
                ->whereIn($numberValueCol, $selected->all())
                ->get();

            if ($pivotTable && $pivotReservationCol && $pivotNumberCol && $numberIdCol) {
                $pivotRows = [];

                foreach ($selectedNumberRows as $row) {
                    $pivotRows[] = [
                        $pivotReservationCol => $reservationId,
                        $pivotNumberCol      => $row->{$numberIdCol},
                    ];
                }

                if (! empty($pivotRows)) {
                    DB::table($pivotTable)->insert($pivotRows);
                }
            }

            foreach ($selectedNumberRows as $row) {
                $updateData = [];

                if ($numberStatusCol) {
                    $updateData[$numberStatusCol] = 'reservado';
                }

                if ($numberReservationCol) {
                    $updateData[$numberReservationCol] = $reservationId;
                }

                if ($numberUserCol) {
                    $updateData[$numberUserCol] = $userId;
                }

                if ($numberUpdatedAtCol) {
                    $updateData[$numberUpdatedAtCol] = $now;
                }

                if (! empty($updateData)) {
                    DB::table($numbersTable)
                        ->where($numberValueCol, $row->{$numberValueCol})
                        ->update($updateData);
                }
            }
        });

        $formattedNumbers = $selected
            ->map(fn ($number) => str_pad($number, 4, '0', STR_PAD_LEFT))
            ->implode(', ');

        return [
            'ok'       => true,
            'redirect' => 'numbers',
            'message'  => "Reserva criada com sucesso para os números: {$formattedNumbers}",
        ];
    }

    protected function reservationStatusLabel(string $status): string
    {
        return match ($status) {
            'confirmado' => 'Confirmado',
            'cancelado'  => 'Cancelado',
            'expirado'   => 'Expirado',
            default      => 'Pendente',
        };
    }

    public function confirmReservation(int $reservationId, $actor = null): array
    {
        $reservation = $this->reservationRowById($reservationId);

        if (! $reservation) {
            return [
                'ok' => false,
                'message' => 'Reserva não encontrada.',
            ];
        }

        $currentStatus = $this->normalizeReservationStatus($reservation->status_value ?? null);

        if ($currentStatus === 'confirmado') {
            return [
                'ok' => false,
                'message' => 'Essa reserva já está confirmada.',
            ];
        }

        if ($currentStatus === 'cancelado') {
            return [
                'ok' => false,
                'message' => 'Não é possível confirmar uma reserva cancelada.',
            ];
        }

        DB::transaction(function () use ($reservationId, $actor) {
            $this->updateReservationRecord($reservationId, 'confirmado');
            $this->updateNumbersStatusForReservation($reservationId, 'pago', false);
            $this->writeLog(
                'Confirmou pagamento',
                'Reserva #' . $reservationId,
                $actor,
                $reservationId
            );
        });

        return [
            'ok' => true,
            'message' => 'Pagamento confirmado com sucesso.',
        ];
    }

    public function cancelReservation(int $reservationId, $actor = null): array
    {
        $reservation = $this->reservationRowById($reservationId);

        if (! $reservation) {
            return [
                'ok' => false,
                'message' => 'Reserva não encontrada.',
            ];
        }

        $currentStatus = $this->normalizeReservationStatus($reservation->status_value ?? null);

        if ($currentStatus === 'cancelado') {
            return [
                'ok' => false,
                'message' => 'Essa reserva já está cancelada.',
            ];
        }

        DB::transaction(function () use ($reservationId, $actor) {
            $this->updateReservationRecord($reservationId, 'cancelado');
            $this->updateNumbersStatusForReservation($reservationId, 'disponivel', true);
            $this->writeLog(
                'Cancelou reserva',
                'Reserva #' . $reservationId,
                $actor,
                $reservationId
            );
        });

        return [
            'ok' => true,
            'message' => 'Reserva cancelada e números liberados com sucesso.',
        ];
    }

    protected function reservationRowById(int $reservationId): ?object
    {
        $reservationsTable = $this->table(['reservations', 'reserva', 'reservas']);

        if (! $reservationsTable) {
            return null;
        }

        $reservationIdCol = $this->column($reservationsTable, ['id']);
        $reservationStatusCol = $this->column($reservationsTable, ['status', 'situacao']);

        if (! $reservationIdCol) {
            return null;
        }

        $query = DB::table($reservationsTable)
            ->where($reservationIdCol, $reservationId);

        if ($reservationStatusCol) {
            $query->select([
                $reservationIdCol . ' as id',
                DB::raw($reservationStatusCol . ' as status_value'),
            ]);
        } else {
            $query->select([
                $reservationIdCol . ' as id',
                DB::raw('NULL as status_value'),
            ]);
        }

        return $query->first();
    }

    protected function updateReservationRecord(int $reservationId, string $status): void
    {
        $reservationsTable = $this->table(['reservations', 'reserva', 'reservas']);

        $reservationIdCol = $this->column($reservationsTable, ['id']);
        $reservationStatusCol = $this->column($reservationsTable, ['status', 'situacao']);
        $reservationUpdatedAtCol = $this->column($reservationsTable, ['updated_at', 'data_atualizacao']);

        if (! $reservationsTable || ! $reservationIdCol) {
            return;
        }

        $updateData = [];

        if ($reservationStatusCol) {
            $updateData[$reservationStatusCol] = $status;
        }

        if ($reservationUpdatedAtCol) {
            $updateData[$reservationUpdatedAtCol] = now();
        }

        if (! empty($updateData)) {
            DB::table($reservationsTable)
                ->where($reservationIdCol, $reservationId)
                ->update($updateData);
        }
    }

    protected function updateNumbersStatusForReservation(
        int $reservationId,
        string $status,
        bool $clearLinks = false
    ): void {
        $numbersTable = $this->table(['numbers', 'raffle_numbers', 'numeros']);

        if (! $numbersTable) {
            return;
        }

        $numberPk = $this->column($numbersTable, ['id']);
        $numberStatusCol = $this->column($numbersTable, ['status', 'situacao']);
        $numberReservationCol = $this->column($numbersTable, ['reservation_id', 'reserva_id']);
        $numberUserCol = $this->column($numbersTable, ['user_id', 'usuario_id']);
        $numberUpdatedAtCol = $this->column($numbersTable, ['updated_at', 'data_atualizacao']);

        $pivotTable = $this->table(['reservation_numbers', 'reserva_numeros', 'reservation_number']);
        $pivotReservationCol = $this->column($pivotTable, ['reservation_id', 'reserva_id']);
        $pivotNumberCol = $this->column($pivotTable, ['number_id', 'numero_id', 'raffle_number_id']);

        $hasPivot = $pivotTable && $pivotReservationCol && $pivotNumberCol && $numberPk;

        $numberIds = [];

        if ($hasPivot) {
            $numberIds = DB::table($pivotTable)
                ->where($pivotReservationCol, $reservationId)
                ->pluck($pivotNumberCol)
                ->map(fn ($id) => (int) $id)
                ->all();
        }

        $baseUpdate = [];

        if ($numberStatusCol) {
            $baseUpdate[$numberStatusCol] = $status;
        }

        if ($numberUpdatedAtCol) {
            $baseUpdate[$numberUpdatedAtCol] = now();
        }

        if ($clearLinks && $hasPivot) {
            if ($numberReservationCol) {
                $baseUpdate[$numberReservationCol] = null;
            }

            if ($numberUserCol) {
                $baseUpdate[$numberUserCol] = null;
            }
        }

        if (! empty($numberIds) && ! empty($baseUpdate)) {
            DB::table($numbersTable)
                ->whereIn($numberPk, $numberIds)
                ->update($baseUpdate);
        }

        if ($numberReservationCol && ! empty($baseUpdate)) {
            DB::table($numbersTable)
                ->where($numberReservationCol, $reservationId)
                ->update($baseUpdate);
        }
    }

    protected function writeLog(
        string $action,
        string $target,
        $actor = null,
        ?int $entityId = null
    ): void {
        $logsTable = $this->table(['system_logs', 'logs_sistema', 'audit_logs', 'logs']);

        if (! $logsTable) {
            return;
        }

        $actionCol = $this->column($logsTable, ['action', 'acao']);
        $entityCol = $this->column($logsTable, ['entity', 'entidade', 'target', 'alvo']);
        $entityIdCol = $this->column($logsTable, ['entity_id', 'entidade_id']);
        $userIdCol = $this->column($logsTable, ['user_id', 'usuario_id', 'admin_id']);
        $userNameCol = $this->column($logsTable, ['user_name', 'usuario_nome', 'responsavel']);
        $createdAtCol = $this->column($logsTable, ['created_at', 'data_criacao']);
        $updatedAtCol = $this->column($logsTable, ['updated_at', 'data_atualizacao']);

        $data = [];

        if ($actionCol) {
            $data[$actionCol] = $action;
        }

        if ($entityCol) {
            $data[$entityCol] = $target;
        }

        if ($entityIdCol && $entityId !== null) {
            $data[$entityIdCol] = $entityId;
        }

        if ($userIdCol && $actor && isset($actor->id)) {
            $data[$userIdCol] = $actor->id;
        }

        if ($userNameCol) {
            $data[$userNameCol] = $actor
                ? ($actor->name ?? $actor->nome ?? $actor->email ?? 'Administrador')
                : 'Administrador';
        }

        if ($createdAtCol) {
            $data[$createdAtCol] = now();
        }

        if ($updatedAtCol) {
            $data[$updatedAtCol] = now();
        }

        if (! empty($data)) {
            DB::table($logsTable)->insert($data);
        }
    }

    protected function reservationEntries(?int $onlyUserId = null): array
    {
        $reservationsTable = $this->table(['reservations', 'reserva', 'reservas']);

        if (! $reservationsTable) {
            return [];
        }

        $usersTable = $this->table(['users', 'usuarios']);

        $reservationIdCol = $this->column($reservationsTable, ['id']);
        $reservationUserCol = $this->column($reservationsTable, ['user_id', 'usuario_id']);
        $reservationStatusCol = $this->column($reservationsTable, ['status', 'situacao']);
        $reservationTotalCol = $this->column($reservationsTable, ['total_value', 'valor_total', 'amount', 'total']);
        $reservationCreatedAtCol = $this->column($reservationsTable, ['created_at', 'data_criacao']);
        $reservationNameCol = $this->column($reservationsTable, ['name', 'nome']);
        $reservationEmailCol = $this->column($reservationsTable, ['email']);
        $reservationPhoneCol = $this->column($reservationsTable, ['phone', 'telefone', 'whatsapp', 'celular']);

        if (! $reservationIdCol) {
            return [];
        }

        $query = DB::table($reservationsTable)
            ->orderByDesc($reservationIdCol);

        if ($onlyUserId && $reservationUserCol) {
            $query->where($reservationUserCol, $onlyUserId);
        }

        $reservationRows = $query->get();

        if ($reservationRows->isEmpty()) {
            return [];
        }

        $reservationIds = $reservationRows
            ->pluck($reservationIdCol)
            ->map(fn ($id) => (int) $id)
            ->all();

        $numbersMap = $this->reservationNumbersMap($reservationIds);

        $userMap = [];

        if ($usersTable && $reservationUserCol) {
            $userPk = $this->column($usersTable, ['id']);
            $userNameCol = $this->column($usersTable, ['name', 'nome']);
            $userEmailCol = $this->column($usersTable, ['email']);
            $userPhoneCol = $this->column($usersTable, ['phone', 'telefone', 'whatsapp', 'celular']);

            if ($userPk) {
                $userIds = $reservationRows
                    ->pluck($reservationUserCol)
                    ->filter()
                    ->unique()
                    ->values()
                    ->all();

                if (! empty($userIds)) {
                    $users = DB::table($usersTable)
                        ->whereIn($userPk, $userIds)
                        ->get();

                    foreach ($users as $user) {
                        $userMap[$user->{$userPk}] = [
                            'name'  => $userNameCol ? ($user->{$userNameCol} ?? 'Usuário') : 'Usuário',
                            'email' => $userEmailCol ? ($user->{$userEmailCol} ?? '-') : '-',
                            'phone' => $userPhoneCol ? ($user->{$userPhoneCol} ?? '-') : '-',
                        ];
                    }
                }
            }
        }

        $entries = [];

        foreach ($reservationRows as $row) {
            $reservationId = (int) $row->{$reservationIdCol};
            $userId = $reservationUserCol ? (int) ($row->{$reservationUserCol} ?? 0) : null;

            $inlineName = $reservationNameCol ? ($row->{$reservationNameCol} ?? null) : null;
            $inlineEmail = $reservationEmailCol ? ($row->{$reservationEmailCol} ?? null) : null;
            $inlinePhone = $reservationPhoneCol ? ($row->{$reservationPhoneCol} ?? null) : null;

            $statusKey = $this->normalizeReservationStatus(
                $reservationStatusCol ? ($row->{$reservationStatusCol} ?? null) : null
            );

            $entries[] = [
                'id'         => $reservationId,
                'user_id'    => $userId,
                'name'       => $userMap[$userId]['name'] ?? $inlineName ?? 'Usuário',
                'email'      => $userMap[$userId]['email'] ?? $inlineEmail ?? '-',
                'phone'      => $userMap[$userId]['phone'] ?? $inlinePhone ?? '-',
                'numbers'    => $numbersMap[$reservationId] ?? [],
                'value'      => $reservationTotalCol ? (float) ($row->{$reservationTotalCol} ?? 0) : 0.0,
                'status_key' => $statusKey,
                'status'     => $this->reservationStatusLabel($statusKey),
                'created_at' => $reservationCreatedAtCol
                    ? $this->formatDateTime($row->{$reservationCreatedAtCol} ?? null)
                    : '-',
            ];
        }

        return $entries;
    }

    protected function reservationNumbersMap(array $reservationIds): array
    {
        $map = [];

        if (empty($reservationIds)) {
            return $map;
        }

        $numbersTable = $this->table(['numbers', 'raffle_numbers', 'numeros']);
        $pivotTable = $this->table(['reservation_numbers', 'reserva_numeros', 'reservation_number']);

        $numberPk = $this->column($numbersTable, ['id']);
        $numberValueCol = $this->column($numbersTable, ['number', 'numero']);
        $numberReservationCol = $this->column($numbersTable, ['reservation_id', 'reserva_id']);

        $pivotReservationCol = $this->column($pivotTable, ['reservation_id', 'reserva_id']);
        $pivotNumberCol = $this->column($pivotTable, ['number_id', 'numero_id', 'raffle_number_id']);

        if (
            $pivotTable &&
            $numbersTable &&
            $pivotReservationCol &&
            $pivotNumberCol &&
            $numberPk &&
            $numberValueCol
        ) {
            $rows = DB::table($pivotTable . ' as p')
                ->join(
                    $numbersTable . ' as n',
                    'p.' . $pivotNumberCol,
                    '=',
                    'n.' . $numberPk
                )
                ->whereIn('p.' . $pivotReservationCol, $reservationIds)
                ->select([
                    DB::raw('p.' . $pivotReservationCol . ' as reservation_id'),
                    DB::raw('n.' . $numberValueCol . ' as number'),
                ])
                ->orderBy('n.' . $numberValueCol)
                ->get();

            foreach ($rows as $row) {
                $map[$row->reservation_id][] = (int) $row->number;
            }

            return $map;
        }

        if ($numbersTable && $numberValueCol && $numberReservationCol) {
            $rows = DB::table($numbersTable)
                ->whereIn($numberReservationCol, $reservationIds)
                ->select([
                    DB::raw($numberReservationCol . ' as reservation_id'),
                    DB::raw($numberValueCol . ' as number'),
                ])
                ->orderBy($numberValueCol)
                ->get();

            foreach ($rows as $row) {
                $map[$row->reservation_id][] = (int) $row->number;
            }
        }

        return $map;
    }

    protected function logs(): array
    {
        $logsTable = $this->table(['system_logs', 'logs_sistema', 'audit_logs', 'logs']);

        if (! $logsTable) {
            return [];
        }

        $idCol = $this->column($logsTable, ['id']);
        $actionCol = $this->column($logsTable, ['action', 'acao']);
        $entityCol = $this->column($logsTable, ['entity', 'entidade', 'target', 'alvo']);
        $createdAtCol = $this->column($logsTable, ['created_at', 'data_criacao']);
        $userNameCol = $this->column($logsTable, ['user_name', 'usuario_nome', 'responsavel']);

        if (! $idCol) {
            return [];
        }

        $rows = DB::table($logsTable)
            ->orderByDesc($idCol)
            ->limit(10)
            ->get();

        $logs = [];

        foreach ($rows as $row) {
            $logs[] = [
                'date'   => $createdAtCol ? $this->formatDateTime($row->{$createdAtCol} ?? null) : '-',
                'user'   => $userNameCol ? ($row->{$userNameCol} ?? 'Sistema') : 'Sistema',
                'action' => $actionCol ? ($row->{$actionCol} ?? '-') : '-',
                'target' => $entityCol ? ($row->{$entityCol} ?? '-') : '-',
            ];
        }

        return $logs;
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

    protected function formatDate($value, string $fallback = 'A definir'): string
    {
        if (blank($value)) {
            return $fallback;
        }

        try {
            return Carbon::parse($value)->format('d/m/Y');
        } catch (\Throwable $e) {
            return (string) $value;
        }
    }

    protected function formatDateTime($value, string $fallback = '-'): string
    {
        if (blank($value)) {
            return $fallback;
        }

        try {
            return Carbon::parse($value)->format('d/m/Y H:i');
        } catch (\Throwable $e) {
            return (string) $value;
        }
    }

    protected function normalizeNumberStatus(?string $status): string
    {
        $value = mb_strtolower(trim((string) $status));

        return match (true) {
            in_array($value, ['pago', 'paid', 'comprado', 'confirmado'], true) => 'pago',
            in_array($value, ['reservado', 'reserved', 'pendente', 'pending', 'aguardando_confirmacao', 'aguardando_pagamento'], true) => 'reservado',
            in_array($value, ['bloqueado', 'blocked'], true) => 'bloqueado',
            default => 'disponivel',
        };
    }

    protected function normalizeReservationStatus(?string $status): string
    {
        $value = mb_strtolower(trim((string) $status));

        return match (true) {
            in_array($value, ['confirmado', 'confirmada', 'pago', 'paid', 'approved', 'aprovado'], true) => 'confirmado',
            in_array($value, ['cancelado', 'cancelada'], true) => 'cancelado',
            in_array($value, ['expirado', 'expirada'], true) => 'expirado',
            default => 'pendente',
        };
    }
}