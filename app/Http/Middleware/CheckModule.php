<?php

namespace App\Http\Middleware;

use App\Models\Module;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckModule
{
    /**
     * Gate a route (or route group) behind a module being enabled.
     *
     * Usage:
     *   Route::middleware('module:patients-management')...
     *   Route::middleware('module:sha-shif')...
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        if (!Module::isEnabled($module)) {
            abort(403, 'This module has been disabled by the administrator.');
        }

        return $next($request);
    }
}
