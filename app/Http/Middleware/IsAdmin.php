<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class IsAdmin
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
         $user = auth('web')->user();
        if ($user && $user->rol_id === 1) {
            return $next($request);
        } else {
            return response()->json([
                'message' => 'You are not an ADMIN'
            ], 403);
        }
    }
}
