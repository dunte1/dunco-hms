<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class CancerRegistration extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'patient_id', 'registration_number', 'cancer_site', 'histology_type',
        'laterality', 'grade', 'diagnosis_date',
        'tnm_staging_t', 'tnm_staging_n', 'tnm_staging_m',
        'overall_stage', 'status', 'registered_by',
    ];

    protected $casts = [
        'diagnosis_date' => 'date',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($reg) {
            if (empty($reg->registration_number)) {
                $reg->registration_number = 'CAN-' . date('Y') . '-' . str_pad(CancerRegistration::count() + 1, 5, '0', STR_PAD_LEFT);
            }
        });
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function registeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    public function treatmentPlans(): HasMany
    {
        return $this->hasMany(OncologyTreatmentPlan::class);
    }
}
