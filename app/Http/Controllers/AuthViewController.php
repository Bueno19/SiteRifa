<?php

namespace App\Http\Controllers;

use App\Services\RaffleService;

class AuthViewController extends Controller
{
    public function __construct(private readonly RaffleService $raffleService)
    {
    }

    public function login()
    {
        return view('auth.login', [
            'campaignTitle' => $this->raffleService->campaignData()['campaignTitle'],
        ]);
    }

    public function register()
    {
        return view('auth.register', [
            'campaignTitle' => $this->raffleService->campaignData()['campaignTitle'],
        ]);
    }
}