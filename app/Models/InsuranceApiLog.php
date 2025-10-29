<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InsuranceApiLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_insurance_id', 'api_provider', 'request_type', 'request_data',
        'response_data', 'response_code', 'status', 'error_message'
    ];

    protected $casts = [
        'request_data' => 'array',
        'response_data' => 'array',
    ];

    public function patientInsurance(): BelongsTo
    {
        return $this->belongsTo(PatientInsurance::class);
    }
}
