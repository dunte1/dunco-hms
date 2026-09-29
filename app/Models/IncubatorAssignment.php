<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IncubatorAssignment extends Model
{
    use HasFactory;

    protected $fillable = [
        'nicu_admission_id', 'incubator_id', 'assigned_at', 'removed_at', 'notes',
    ];

    protected $casts = [
        'assigned_at' => 'datetime',
        'removed_at' => 'datetime',
    ];

    public function nicuAdmission(): BelongsTo
    {
        return $this->belongsTo(NicuAdmission::class);
    }
}
