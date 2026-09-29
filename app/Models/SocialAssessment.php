<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SocialAssessment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'assessment_date', 'living_situation',
        'income_source', 'financial_status', 'family_support',
        'transport_needs', 'housing_needs', 'legal_needs', 'assessed_by',
    ];

    protected $casts = [
        'assessment_date' => 'date',
        'transport_needs' => 'boolean',
        'housing_needs' => 'boolean',
        'legal_needs' => 'boolean',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function assessedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }
}
