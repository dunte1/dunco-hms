<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PatientInsurance extends Model
{
    use HasFactory;

    protected $table = 'patient_insurance';

    protected $fillable = [
        'patient_id', 'insurance_provider_id', 'policy_number',
        'effective_date', 'expiry_date', 'coverage_amount', 'is_active', 'notes',
        'group_number', 'policy_holder_name', 'policy_holder_relationship',
        'coverage_start_date', 'coverage_end_date', 'coverage_type',
        'is_primary', 'copayment_amount'
    ];

    protected $casts = [
        'effective_date' => 'date',
        'expiry_date' => 'date',
        'coverage_start_date' => 'date',
        'coverage_end_date' => 'date',
        'coverage_amount' => 'decimal:2',
        'copayment_amount' => 'decimal:2',
        'is_active' => 'boolean',
        'is_primary' => 'boolean',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function insuranceProvider(): BelongsTo
    {
        return $this->belongsTo(InsuranceProvider::class);
    }

    public function provider(): BelongsTo
    {
        return $this->belongsTo(InsuranceProvider::class, 'insurance_provider_id');
    }

    public function claims(): HasMany
    {
        return $this->hasMany(InsuranceClaim::class);
    }
}
