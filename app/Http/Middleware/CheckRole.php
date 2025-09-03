<?php

namespace App\Http\Middleware;
namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckRole
{
    public function handle(Request $request, Closure $next, ...$roles)
    {
        $user = auth('web')->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!in_array($user->rol->nombre, $roles)) {
            return redirect()->route('dashboard')
                ->with('error', 'No tienes permisos para acceder');
        }

        return $next($request);
    }
}
