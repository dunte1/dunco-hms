<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InAppAlert extends Model
{
    protected $fillable = [
        'type', 'title', 'body', 'target_role', 'related_id', 'related_type', 'read_at',
    ];

    protected $casts = [
        'read_at' => 'datetime',
    ];

    public function scopeForRole($query, ?string $role)
    {
        if (! $role) {
            return $query->whereNull('target_role');
        }

        return $query->where(function ($q) use ($role) {
            $q->whereNull('target_role')->orWhere('target_role', $role);
        });
    }
}
