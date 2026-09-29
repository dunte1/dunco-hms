<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AncVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'pregnancy_id', 'visit_number', 'visit_date', 'gestational_age_weeks',
        'weight_kg', 'blood_pressure_sys', 'blood_pressure_dia', 'hemoglobin',
        'urine_protein', 'urine_glucose', 'fundal_height', 'fetal_heart_rate',
        'presentation', 'notes', 'visited_by',
    ];

    protected $casts = [
        'visit_date' => 'date',
    ];

    public function pregnancy(): BelongsTo
    {
        return $this->belongsTo(Pregnancy::class);
    }

    public function visitedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'visited_by');
    }
}
