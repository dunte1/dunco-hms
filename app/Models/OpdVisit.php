<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OpdVisit extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'doctor_id', 'visit_date', 'visit_type', 
        'chief_complaint', 'diagnosis', 'prescription', 'consultation_fee'
    ];

    protected $casts = [
        'visit_date' => 'datetime',
        'consultation_fee' => 'decimal:2',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }
}