<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\RaffleService;
use Illuminate\Support\Facades\Auth;

class AdminReservationController extends Controller
{
    public function __construct(
        private readonly RaffleService $raffleService
    ) {
    }

    public function index()
    {
        return view(
            'admin.reservations',
            $this->raffleService->adminReservationsData()
        );
    }

    public function confirm(int $reservation)
    {
        $result = $this->raffleService->confirmReservation(
            $reservation,
            Auth::user()
        );

        return redirect()
            ->route('admin.reservations')
            ->with(
                $result['ok'] ? 'success' : 'error',
                $result['message']
            );
    }

    public function cancel(int $reservation)
    {
        $result = $this->raffleService->cancelReservation(
            $reservation,
            Auth::user()
        );

        return redirect()
            ->route('admin.reservations')
            ->with(
                $result['ok'] ? 'success' : 'error',
                $result['message']
            );
    }
}