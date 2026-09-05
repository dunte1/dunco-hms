<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ConsentForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'doctor_id', 'consent_type', 'procedure_name',
        'description', 'risks_disclosed', 'alternatives_disclosed',
        'patient_signature_path', 'witness_signature_path', 'signed_at',
        'expires_at', 'status', 'ip_address', 'notes',
    ];

    protected $casts = [
        'signed_at' => 'datetime',
        'expires_at' => 'datetime',
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
