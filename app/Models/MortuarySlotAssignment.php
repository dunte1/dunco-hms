<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MortuarySlotAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'mortuary_record_id', 'slot_number', 'assigned_at', 'removed_at', 'notes',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'removed_at' => 'datetime',
        'slot_number' => 'integer',
    ];

    public function mortuaryRecord(): BelongsTo
    {
        return $this->belongsTo(MortuaryRecord::class);
    }
}
