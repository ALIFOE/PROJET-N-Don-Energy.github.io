<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use App\Models\LogActivite;

class LogUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        $response = $next($request);

        if (auth()->check()) {
            // Log des connexions/déconnexions
            if ($request->is('login')) {
                LogActivite::create([
                    'user_id' => auth()->id(),
                    'action' => 'connexion',
                    'table' => 'users',
                    'id_table' => auth()->id(),
                    'description' => 'Connexion au tableau de bord',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);
            } elseif ($request->is('logout')) {
                LogActivite::create([
                    'user_id' => auth()->id(),
                    'action' => 'déconnexion',
                    'table' => 'users',
                    'id_table' => auth()->id(),
                    'description' => 'Déconnexion du tableau de bord',
                    'ip_address' => $request->ip(),
                    'user_agent' => $request->userAgent()
                ]);
            }
        }

        return $response;
    }
}