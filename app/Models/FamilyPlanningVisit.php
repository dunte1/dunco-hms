<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FamilyPlanningVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'method', 'previous_method', 'side_effects',
        'counselled_by', 'visit_date', 'next_visit_date',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'next_visit_date' => 'date',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function counselledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'counselled_by');
    }
}
