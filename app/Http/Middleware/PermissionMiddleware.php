<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class PermissionMiddleware
{
    /**
     * Handle an incoming request.
     *
     * Accepts one or more permission names separated by "|".
     * The user must have AT LEAST ONE of the listed permissions.
     *
     * Usage:
     *   Route::middleware('permission:view patients')
     *   Route::middleware('permission:view patients|add patients')
     */
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // Flatten pipe-separated permissions: "permission:view patients|add patients"
        $required = [];
        foreach ($permissions as $permission) {
            foreach (explode('|', $permission) as $perm) {
                $trimmed = trim($perm);
                if ($trimmed !== '') {
                    $required[] = $trimmed;
                }
            }
        }

        if (empty($required)) {
            return $next($request);
        }

        // User must have at least ONE of the required permissions (OR logic)
        foreach ($required as $permission) {
            if (auth()->user()->can($permission)) {
                return $next($request);
            }
        }

        abort(403, 'Access denied. Required permission: ' . implode(' or ', $required));
    }
}
