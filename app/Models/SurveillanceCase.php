<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SurveillanceCase extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'disease_name', 'icd_code', 'notification_type', 'case_date',
        'facility_id', 'county', 'sub_county', 'reported_by', 'status', 'confirmed_date',
    ];

    protected $casts = [
        'case_date' => 'date',
        'confirmed_date' => 'date',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function facility(): BelongsTo
    {
        return $this->belongsTo(HospitalBranch::class, 'facility_id');
    }

    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function notifiableDiseaseReports(): HasMany
    {
        return $this->hasMany(NotifiableDiseaseReport::class);
    }
}
