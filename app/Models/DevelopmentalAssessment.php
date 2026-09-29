<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DevelopmentalAssessment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'patient_id', 'assessment_date', 'age_months', 'motor_skills', 'language_skills',
        'social_skills', 'cognitive_skills', 'red_flags', 'overall_status', 'assessed_by',
    ];

    protected $casts = [
        'assessment_date' => 'date',
        'age_months' => 'integer',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function assessor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }
}
