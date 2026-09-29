<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PostnatalVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'pregnancy_id', 'patient_id', 'visit_date', 'visit_day_postpartum',
        'blood_pressure_sys', 'blood_pressure_dia', 'uterine_involution',
        'lochia', 'breast_feeding', 'family_planning_counselled',
        'family_planning_method', 'wound_check', 'mental_health_screening',
        'complications', 'visited_by',
    ];

    protected $casts = [
        'visit_date' => 'date',
        'family_planning_counselled' => 'boolean',
    ];

    public function pregnancy(): BelongsTo
    {
        return $this->belongsTo(Pregnancy::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function visitedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'visited_by');
    }
}
