<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class OptionalSanctumAuth
{
    /**
     * Authenticate user when Bearer token is present, while allowing guests.
     */
    public function handle(Request $request, Closure $next): Response
    {
        $bearerToken = $request->bearerToken();

        if (!$bearerToken) {
            return $next($request);
        }

        $guard = Auth::guard('sanctum');

        if (!$guard->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthenticated.'
            ], 401);
        }

        Auth::shouldUse('sanctum');
        $request->setUserResolver(fn () => $guard->user());

        return $next($request);
    }
}