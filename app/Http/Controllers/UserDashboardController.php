<?php

namespace App\Http\Controllers;

use App\Services\RaffleService;
use Illuminate\Support\Facades\Auth;

class UserDashboardController extends Controller
{
    public function __construct(private readonly RaffleService $raffleService)
    {
    }

    public function index()
    {
        return view('user.dashboard', $this->raffleService->userDashboardData(Auth::user()));
    }
}