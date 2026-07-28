<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'doctor_id', 'opd_visit_id', 'prescription_date',
        'symptoms', 'diagnosis', 'notes', 'status',
        'digital_signature', 'signed_at', 'signed_by', 'template_id', 'metadata'
    ];

    protected $casts = [
        'prescription_date' => 'date',
        'signed_at' => 'datetime',
        'metadata' => 'array',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class);
    }

    public function opdVisit(): BelongsTo
    {
        return $this->belongsTo(OpdVisit::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(PrescriptionItem::class);
    }

    public function signedBy(): BelongsTo
    {
        return $this->belongsTo(\App\Models\User::class, 'signed_by');
    }
}
