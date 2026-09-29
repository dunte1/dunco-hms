<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImmunizationSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'vaccine_id', 'dose_number', 'due_date', 'scheduled_date',
        'status', 'completed_date', 'administered_by', 'batch_number', 'site', 'notes',
    ];

    protected $casts = [
        'due_date' => 'date',
        'scheduled_date' => 'date',
        'completed_date' => 'date',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function vaccine(): BelongsTo
    {
        return $this->belongsTo(Vaccine::class);
    }

    public function administeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'administered_by');
    }
}
