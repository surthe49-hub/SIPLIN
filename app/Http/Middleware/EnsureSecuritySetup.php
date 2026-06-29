<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * MIDDLEWARE DI-DISABLE
 * Fitur security setup paksa dimatikan untuk SIPLIN.
 * Middleware ini hanya pass-through tanpa redirect ke mana-mana.
 */
class EnsureSecuritySetup
{
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }
}