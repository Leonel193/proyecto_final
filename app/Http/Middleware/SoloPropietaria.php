<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SoloPropietaria
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!$request->user() || !$request->user()->esPropietaria()) {
            abort(403, 'No tienes permiso para realizar esta acción. Contacta con la propietaria del sistema.');
        }

        return $next($request);
    }
}
