<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OtTimeLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ot_schedule_id', 'event_type', 'event_time', 'recorded_by', 'notes',
    ];

    protected $casts = [
        'event_time' => 'datetime',
    ];

    public function schedule(): BelongsTo
    {
        return $this->belongsTo(OtSchedule::class, 'ot_schedule_id');
    }

    public function recordedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
