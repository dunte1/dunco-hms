<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TheatreTeam extends Model
{
    use HasFactory;

    protected $fillable = [
        'ot_schedule_id', 'role', 'user_id', 'assigned_at', 'released_at', 'notes',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'released_at' => 'datetime',
    ];

    public function otSchedule(): BelongsTo
    {
        return $this->belongsTo(OtSchedule::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
