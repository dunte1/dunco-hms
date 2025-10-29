<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

class InsuranceProvider extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'description', 'contact_person', 'phone',
        'email', 'address', 'coverage_percentage', 'is_active',
        'policy_number_prefix', 'website', 'coverage_limit',
        'copayment_percentage', 'deductible_amount', 'claim_submission_url',
        'api_endpoint', 'api_key', 'notes'
    ];

    protected $casts = [
        'coverage_percentage' => 'decimal:2',
        'coverage_limit' => 'decimal:2',
        'copayment_percentage' => 'decimal:2',
        'deductible_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function patientInsurance(): HasMany
    {
        return $this->hasMany(PatientInsurance::class);
    }

    public function patientInsurances(): HasMany
    {
        return $this->hasMany(PatientInsurance::class, 'insurance_provider_id');
    }

    public function claims(): HasManyThrough
    {
        return $this->hasManyThrough(
            InsuranceClaim::class,
            PatientInsurance::class,
            'insurance_provider_id',
            'patient_insurance_id',
            'id',
            'id'
        );
    }
}
