<?php

namespace App\Services\Roles;

use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

/**
 * Role composition / inheritance helper.
 *
 * Spatie roles are flat. Composite roles (ICU Nurse, Theatre Nurse, etc.)
 * are seeded with explicit permission sets. This service allows runtime
 * composition when assigning roles so new composite roles can be built
 * from a base role + extra permissions without duplicating hundreds of
 * permission rows by hand in seeders.
 */
class RoleComposer
{
    /**
     * Composite role definitions: role name => [base role, extra permission names].
     * Extra permissions are ignored if they do not exist in the permission table.
     */
    public const COMPOSITES = [
        'ICU Nurse' => [
            'base' => 'Nurse',
            'extra' => [
                'manage icu admissions', 'manage critical care charts', 'manage ventilators',
                'manage sedation scores', 'manage infusions', 'record abg results',
            ],
        ],
        'Maternity Nurse' => [
            'base' => 'Nurse',
            'extra' => [
                'manage anc registrations', 'manage pregnancies', 'manage labour records',
                'record deliveries', 'manage postnatal visits', 'manage family planning visits',
                'manage newborns', 'manage nicu admissions',
            ],
        ],
        'Theatre Nurse' => [
            'base' => 'Nurse',
            'extra' => [
                'manage theatre bookings', 'manage theatre teams', 'manage theatre consumables',
                'record preop assessments', 'complete who checklists', 'manage recovery records',
                'manage anaesthesia assessments', 'manage anaesthesia records',
                'manage anaesthesia drugs', 'record intraop vitals',
                'manage anaesthesia complications', 'record post anaesthesia reviews',
            ],
        ],
        'Telemedicine Doctor' => [
            'base' => 'Doctor',
            'extra' => [
                'use telemedicine', 'manage tele participants',
                'manage telemedicine sessions',
            ],
        ],
    ];

    /**
     * Apply composition to an existing user: sync role + ensure composite extras.
     */
    public function applyToUser(\App\Models\User $user, string $roleName): void
    {
        $user->syncRoles([$roleName]);

        $def = self::COMPOSITES[$roleName] ?? null;
        if ($def && !empty($def['extra'])) {
            $existing = Permission::whereIn('name', $def['extra'])->pluck('id')->all();
            if ($existing) {
                $user->permissions()->syncWithoutDetaching($existing);
            }
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    /**
     * Resolve the effective permission names for a composite role (for audits/tests).
     *
     * @return array<int, string>
     */
    public function effectivePermissions(string $roleName): array
    {
        $role = Role::where('name', $roleName)->first();
        if (!$role) {
            return [];
        }

        $names = $role->permissions->pluck('name')->unique()->values()->all();

        $def = self::COMPOSITES[$roleName] ?? null;
        if ($def) {
            $base = Role::where('name', $def['base'])->first();
            if ($base) {
                $names = array_values(array_unique(array_merge($names, $base->permissions->pluck('name')->all())));
            }
            $names = array_values(array_unique(array_merge($names, $def['extra'])));
        }

        return $names;
    }
}
