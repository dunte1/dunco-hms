<?php

namespace App\Models\Scopes;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

/**
 * Query scope that limits results to the authenticated user's facility
 * when a branch/facility context is set.
 *
 * Models using this trait MUST have a facility_id (or branch_id) column.
 * When no context is set (null branch), queries are unscoped so single-site
 * deployments continue to work.
 */
trait BelongsToFacility
{
    public static function bootBelongsToFacility(): void
    {
        static::addGlobalScope('facility', function (Builder $builder) {
            $facilityId = static::currentFacilityId();
            if ($facilityId === null) {
                return;
            }

            $column = static::facilityColumn();
            $builder->where(function ($q) use ($column, $facilityId) {
                $q->where($column, $facilityId)
                  ->orWhereNull($column);
            });
        });
    }

    public function scopeForFacility(Builder $query, $facilityId): Builder
    {
        return $query->where(static::facilityColumn(), $facilityId);
    }

    public function scopeUnscopedFacility(Builder $query): Builder
    {
        return $query->withoutGlobalScope('facility');
    }

    public static function currentFacilityId(): ?int
    {
        if (!Auth::check()) {
            return null;
        }

        $user = Auth::user();
        $branchId = $user->branch_id ?? null;

        if ($branchId !== null) {
            return (int) $branchId;
        }

        // Fallback: main branch if exactly one main branch exists
        if (class_exists(\App\Models\HospitalBranch::class)) {
            $main = \App\Models\HospitalBranch::where('is_main_branch', true)
                ->where('is_active', true)
                ->value('id');
            if ($main !== null) {
                // Only apply main-branch scope when multiple branches exist
                $count = \App\Models\HospitalBranch::where('is_active', true)->count();
                if ($count > 1) {
                    return (int) $main;
                }
            }
        }

        return null;
    }

    public static function facilityColumn(): string
    {
        return property_exists(static::class, 'facilityColumn')
            ? static::$facilityColumn
            : 'facility_id';
    }
}
