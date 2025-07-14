<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next,string $role): Response
    {
        if (!Auth::check()) {
            return response()->json([
                'status' => false,
                'message' => 'Unauthorized',
            ], 401);
        }

        $user = Auth::user();

        if (($role === 'admin' && $user->isAdmin) || ($role === 'user' && !$user->isAdmin)) {
            return $next($request);
        }

        return response()->json([
            'status' => false,
            'message' => 'Access denied. Only ' . $role . 's are allowed.',
        ], 403);
        
    }
}
