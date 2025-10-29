<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RadiologyRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_number', 'patient_id', 'doctor_id', 'radiology_test_id',
        'request_date', 'appointment_date', 'clinical_notes', 'status',
        'findings', 'impression', 'image_path'
    ];

    protected $casts = [
        'request_date' => 'date',
        'appointment_date' => 'date',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function radiologyTest(): BelongsTo
    {
        return $this->belongsTo(RadiologyTest::class);
    }
}
