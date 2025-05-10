<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class ApiAuthentication
{
    public function handle(Request $request, Closure $next)
    {
        if ($request->header('X-API-KEY') !== config('services.api.key')) {
            return response()->json([
                'success' => false,
                'message' => 'Accès non autorisé'
            ], 401);
        }

        return $next($request);
    }
}
