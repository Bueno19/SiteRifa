<?php

namespace App\Http\Controllers;

use App\Services\RaffleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NumberController extends Controller
{
    public function __construct(private readonly RaffleService $raffleService)
    {
    }

    public function index()
    {
        $campaign = $this->raffleService->campaignData();
        $dataset = $this->raffleService->numbersDataset();

        $user = Auth::user();
        $userName = 'Usuário';

        if ($user) {
            $userName = $user->name ?? $user->nome ?? $user->email ?? 'Usuário';
        }

        return view('user.numbers', [
            'campaignTitle'  => $campaign['campaignTitle'],
            'userName'       => $userName,
            'ticketPrice'    => $dataset['ticketPrice'],
            'totalNumbers'   => $dataset['totalNumbers'],
            'availableCount' => $dataset['availableCount'],
            'reservedCount'  => $dataset['reservedCount'],
            'paidCount'      => $dataset['paidCount'],
            'numbers'        => $dataset['numbers'],
            'whatsLink'      => $this->raffleService->whatsappLink(
                $campaign['whatsappNumber'],
                'Olá! Quero falar sobre meus números da rifa.'
            ),
        ]);
    }

    public function reserve(Request $request)
{
    $request->validate([
        'selected_numbers' => ['required', 'string'],
    ]);

    $selectedNumbers = explode(',', $request->input('selected_numbers', ''));

    $result = $this->raffleService->reserve(
        $selectedNumbers,
        \Illuminate\Support\Facades\Auth::id()
    );

    if (! $result['ok']) {
        return redirect()
            ->route($result['redirect'])
            ->with('error', $result['message']);
    }

    $user = \Illuminate\Support\Facades\Auth::user();
    $campaign = $this->raffleService->campaignData();

    $cleanNumbers = collect($selectedNumbers)
        ->map(fn ($number) => (int) trim((string) $number))
        ->filter(fn ($number) => $number > 0)
        ->unique()
        ->values()
        ->all();

    $userName = $user?->name ?? $user?->nome ?? $user?->email ?? 'Usuário';

    $totalValue = count($cleanNumbers) * (float) $campaign['ticketPrice'];

    $message = $this->raffleService->reservationWhatsappMessage(
        $userName,
        $cleanNumbers,
        $totalValue
    );

    $whatsappLink = $this->raffleService->whatsappLink(
        $campaign['whatsappNumber'],
        $message
    );

    return redirect()->away($whatsappLink);
    }
}