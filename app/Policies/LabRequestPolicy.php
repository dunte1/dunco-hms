<?php

namespace App\Policies;

use App\Models\User;
use App\Models\LabRequest;
use Illuminate\Auth\Access\HandlesAuthorization;

class LabRequestPolicy
{
    use HandlesAuthorization;

    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(['view patients', 'add test requests', 'enter test results', 'manage test categories']);
    }

    public function view(User $user, LabRequest $labRequest): bool
    {
        return $user->hasAnyPermission(['view patients', 'add test requests', 'enter test results']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('add test requests');
    }

    public function update(User $user, LabRequest $labRequest): bool
    {
        return $user->hasAnyPermission(['enter test results', 'add test requests']);
    }

    public function delete(User $user, LabRequest $labRequest): bool
    {
        return $user->hasPermissionTo('add test requests');
    }
}
