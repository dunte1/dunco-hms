<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Pregnancy extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'gravida', 'parity', 'last_menstrual_date',
        'estimated_due_date', 'current_gestational_weeks', 'blood_group',
        'rh_factor', 'hiv_status', 'previous_complications', 'is_high_risk',
        'high_risk_reason', 'status',
    ];

    protected $casts = [
        'last_menstrual_date' => 'date',
        'estimated_due_date' => 'date',
        'is_high_risk' => 'boolean',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function ancVisits(): HasMany
    {
        return $this->hasMany(AncVisit::class);
    }

    public function labourRecords(): HasMany
    {
        return $this->hasMany(LabourRecord::class);
    }

    public function deliveries(): HasMany
    {
        return $this->hasMany(Delivery::class);
    }

    public function postnatalVisits(): HasMany
    {
        return $this->hasMany(PostnatalVisit::class);
    }
}
