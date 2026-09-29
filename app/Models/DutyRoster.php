<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class DutyRoster extends Model
{
    use HasFactory;

    protected $fillable = [
        'ward_id', 'roster_date', 'shift_type', 'roster_name', 'status',
        'published_by', 'published_at', 'notes',
    ];

    protected $casts = [
        'roster_date' => 'date',
        'published_at' => 'datetime',
    ];

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    public function publishedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'published_by');
    }

    public function entries(): HasMany
    {
        return $this->hasMany(DutyRosterEntry::class, 'roster_id');
    }
}
