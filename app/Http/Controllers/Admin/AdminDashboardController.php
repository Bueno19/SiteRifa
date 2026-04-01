<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\RaffleService;

class AdminDashboardController extends Controller
{
    public function __construct(private readonly RaffleService $raffleService)
    {
    }

    public function index()
    {
        return view('admin.dashboard', $this->raffleService->adminDashboardData());
    }
}