<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeathCertificate extends Model
{
    use HasFactory;

    protected $fillable = [
        'mortuary_record_id', 'patient_id', 'certificate_number',
        'cause_of_death_primary', 'cause_of_death_secondary',
        'contributing_conditions', 'issued_by', 'issued_at', 'signed_at',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'signed_at' => 'datetime',
    ];

    public function mortuaryRecord(): BelongsTo
    {
        return $this->belongsTo(MortuaryRecord::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'issued_by');
    }
}
