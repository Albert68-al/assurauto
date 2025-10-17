<?php

namespace App\Http\Middleware;

use Closure;

class CheckSuperAdmin
{
    /**
     * Vérifie si l'utilisateur est un super administrateur
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (!auth()->check() || !auth()->user()->isSuperAdmin()) {
            abort(403, 'Accès non autorisé');
        }

        return $next($request);
    }
}
