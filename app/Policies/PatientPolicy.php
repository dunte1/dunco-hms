<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Patient;
use Illuminate\Auth\Access\HandlesAuthorization;

class PatientPolicy
{
    use HandlesAuthorization;

    /**
     * Users with any patient-related permission can view patients.
     * In a hospital admin system, any authenticated staff with the right
     * role can access any patient record (no per-patient isolation).
     */
    public function viewAny(User $user): bool
    {
        return $user->hasAnyPermission(['view patients', 'add patients', 'edit patients', 'manage staff profiles']);
    }

    public function view(User $user, Patient $patient): bool
    {
        return $user->hasAnyPermission(['view patients', 'add patients', 'edit patients']);
    }

    public function create(User $user): bool
    {
        return $user->hasPermissionTo('add patients');
    }

    public function update(User $user, Patient $patient): bool
    {
        return $user->hasPermissionTo('edit patients');
    }

    public function delete(User $user, Patient $patient): bool
    {
        return $user->hasPermissionTo('delete patients');
    }
}
