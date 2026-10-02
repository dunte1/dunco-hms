<?php

namespace App\Policies;

use App\Models\User;
use App\Models\RadiologyRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class RadiologyRequestPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(['view patients', 'add test requests', 'enter test results', 'manage test categories']);
    }

    public function view(User $user, RadiologyRequest $radiologyRequest): bool
    {
        return $user->hasAnyPermission(['view patients', 'add test requests', 'enter test results']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('add test requests');
    }

    public function update(User $user, RadiologyRequest $radiologyRequest): bool
    {
        return $user->hasAnyPermission(['enter test results', 'add test requests']);
    }

    public function delete(User $user, RadiologyRequest $radiologyRequest): bool
    {
        return $user->hasPermissionTo('add test requests');
    }
}
