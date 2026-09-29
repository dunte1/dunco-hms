<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OutbreakEvent extends Model
{
    use HasFactory;

    protected $fillable = [
        'disease_name', 'start_date', 'facility_ids', 'total_cases', 'total_deaths',
        'status', 'declared_by', 'declared_at', 'contained_at', 'ended_at', 'investigation_notes',
    ];

    protected $casts = [
        'start_date' => 'date',
        'declared_at' => 'datetime',
        'contained_at' => 'datetime',
        'ended_at' => 'datetime',
        'facility_ids' => 'array',
        'total_cases' => 'integer',
        'total_deaths' => 'integer',
    ];

    public function declaredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'declared_by');
    }
}
