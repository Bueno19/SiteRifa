<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Services\RaffleService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AdminUserController extends Controller
{
    public function __construct(
        private readonly RaffleService $raffleService
    ) {
    }

    public function index()
    {
        $campaign = $this->raffleService->campaignData();

        $users = User::query()
            ->orderBy('name')
            ->get()
            ->map(function ($user) {
                return [
                    'id'         => $user->id,
                    'name'       => $user->name,
                    'email'      => $user->email,
                    'role'       => $user->role ?? 'user',
                    'created_at' => optional($user->created_at)?->format('d/m/Y H:i') ?? '-',
                ];
            });

        return view('admin.users', [
            'title'         => 'Usuários',
            'campaignTitle' => $campaign['campaignTitle'],
            'adminName'     => 'Administrador',
            'supportLink'   => $this->raffleService->whatsappLink(
                $campaign['whatsappNumber'],
                'Olá! Quero falar sobre usuários da rifa.'
            ),
            'users'         => $users,
        ]);
    }

    public function updateRole(Request $request, User $user)
    {
        $validated = $request->validate([
            'role' => ['required', 'in:user,admin'],
        ]);

        $user->role = $validated['role'];
        $user->save();

        return redirect()
            ->route('admin.users')
            ->with('success', 'Perfil do usuário atualizado com sucesso.');
    }

    public function updatePassword(Request $request, User $user)
    {
        $validated = $request->validate([
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);

        $user->password = Hash::make($validated['password']);
        $user->save();

        return redirect()
            ->route('admin.users')
            ->with('success', 'Senha redefinida com sucesso.');
    }
}