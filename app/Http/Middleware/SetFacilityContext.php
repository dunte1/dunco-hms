<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Resolves facility context for the request and shares it with views.
 * Query-level enforcement is done by the BelongsToFacility global scope.
 */
class SetFacilityContext
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        $branchId = $user?->branch_id ?? null;

        view()->share('currentBranchId', $branchId);
        view()->share('facilityScoped', $branchId !== null);

        return $next($request);
    }
}
