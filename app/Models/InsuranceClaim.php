<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InsuranceClaim extends Model
{
    use HasFactory;

    protected $fillable = [
        'claim_number',
        'patient_id',
        'patient_insurance_id',
        'invoice_id',
        'claim_date',
        'service_date',
        'claimed_amount',
        'approved_amount',
        'paid_amount',
        'status',
        'diagnosis_code',
        'diagnosis_description',
        'treatment_details',
        'documents',
        'rejection_reason',
        'notes',
        'submission_date',
        'approval_date',
        'payment_date',
        'insurance_reference',
        'sha_authorization_number',
        'sha_service_codes',
        'sha_tariff_amount',
        'sha_patient_amount',
        'submission_response',
        'remittance_date',
        'remittance_reference',
    ];

    protected $casts = [
        'claim_date' => 'date',
        'service_date' => 'date',
        'submission_date' => 'date',
        'approval_date' => 'date',
        'payment_date' => 'date',
        'remittance_date' => 'date',
        'claimed_amount' => 'decimal:2',
        'approved_amount' => 'decimal:2',
        'paid_amount' => 'decimal:2',
        'sha_tariff_amount' => 'decimal:2',
        'sha_patient_amount' => 'decimal:2',
        'documents' => 'array',
        'sha_service_codes' => 'array',
        'submission_response' => 'array',
    ];

    public function shaAuthorization()
    {
        return $this->belongsTo(ShaAuthorization::class, 'sha_authorization_number', 'authorization_number');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function patientInsurance(): BelongsTo
    {
        return $this->belongsTo(PatientInsurance::class);
    }

    public function invoice(): BelongsTo
    {
        return $this->belongsTo(Invoice::class);
    }

    public function getBalanceAmountAttribute(): float
    {
        return $this->approved_amount - $this->paid_amount;
    }

    public function getStatusColorAttribute(): string
    {
        return match($this->status) {
            'pending' => 'gray',
            'submitted' => 'blue',
            'under_review' => 'yellow',
            'approved' => 'green',
            'partially_approved' => 'orange',
            'rejected' => 'red',
            'paid' => 'emerald',
            default => 'gray'
        };
    }
}
