<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SavedReport extends Model
{
    protected $fillable = [
        'name', 'description', 'report_type', 'parameters', 'owner_id',
        'is_public', 'last_run_at',
    ];

    protected $casts = [
        'parameters' => 'array',
        'is_public' => 'boolean',
        'last_run_at' => 'datetime',
    ];

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function schedules(): HasMany
    {
        return $this->hasMany(ReportSchedule::class, 'saved_report_id');
    }
}
