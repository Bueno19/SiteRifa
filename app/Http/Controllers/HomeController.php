<?php

namespace App\Http\Controllers;

use App\Services\RaffleService;

class HomeController extends Controller
{
    public function __construct(
        private readonly RaffleService $raffleService
    ) {
    }

    public function index()
    {
        $campaign = $this->raffleService->campaignData();

        return view('public.home', [
            'campaignTitle'    => $campaign['campaignTitle'],
            'prizeTitle'       => $campaign['prizeTitle'],
            'ticketPrice'      => $campaign['ticketPrice'],
            'totalNumbers'     => $campaign['totalNumbers'],
            'reservedNumbers'  => $campaign['reservedNumbers'],
            'paidNumbers'      => $campaign['paidNumbers'],
            'availableNumbers' => $campaign['availableNumbers'],
            'drawDate'         => $campaign['drawDate'],
            'whatsappNumber'   => $campaign['whatsappNumber'],
        ]);
    }
}