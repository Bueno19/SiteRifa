<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (! $user) {
            return redirect()->route('login');
        }

        $isAdmin =
            (isset($user->is_admin) && (bool) $user->is_admin) ||
            (isset($user->role) && $user->role === 'admin');

        if (! $isAdmin) {
            abort(403, 'Acesso não autorizado.');
        }

        return $next($request);
    }
}