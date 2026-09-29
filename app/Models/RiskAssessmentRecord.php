<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RiskAssessmentRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'area_or_activity', 'hazard_identified', 'risk_level', 'existing_controls',
        'additional_controls', 'likelihood', 'consequence', 'residual_risk_level',
        'assessed_by', 'assessment_date', 'next_review_date', 'status',
    ];

    protected $casts = [
        'assessment_date' => 'date',
        'next_review_date' => 'date',
    ];

    public function assessedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }
}
