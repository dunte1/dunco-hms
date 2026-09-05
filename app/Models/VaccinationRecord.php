<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VaccinationRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'vaccine_id', 'dose_number', 'administered_by',
        'administered_at', 'site', 'batch_number', 'reaction_notes', 'next_dose_date',
    ];

    protected $casts = [
        'administered_at' => 'datetime',
        'next_dose_date' => 'date',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function vaccine(): BelongsTo
    {
        return $this->belongsTo(Vaccine::class);
    }
}
