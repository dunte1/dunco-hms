<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientHandoverRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'trip_id', 'patient_id', 'receiving_facility', 'receiving_person',
        'receiving_department', 'clinical_summary', 'handover_time',
        'handed_over_by', 'received_by_name', 'signature_path', 'status',
    ];

    protected $casts = [
        'handover_time' => 'datetime',
    ];

    public function trip(): BelongsTo
    {
        return $this->belongsTo(AmbulanceTrip::class, 'trip_id');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function handedOverBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'handed_over_by');
    }
}
