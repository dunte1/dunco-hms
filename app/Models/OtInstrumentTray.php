<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OtInstrumentTray extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'category', 'description', 'status',
        'sterilized_at', 'sterilization_expiry', 'last_used_schedule_id',
    ];

    protected $casts = [
        'sterilized_at' => 'datetime',
        'sterilization_expiry' => 'datetime',
    ];

    public function lastUsedSchedule(): BelongsTo
    {
        return $this->belongsTo(OtSchedule::class, 'last_used_schedule_id');
    }

    public function isSterile(): bool
    {
        return $this->status === 'sterile' && $this->sterilization_expiry && $this->sterilization_expiry->isFuture();
    }
}
