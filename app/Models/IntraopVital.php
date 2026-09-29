<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IntraopVital extends Model
{
    use HasFactory;

    protected $fillable = [
        'anaesthesia_record_id', 'patient_id', 'time_recorded', 'heart_rate',
        'blood_pressure_sys', 'blood_pressure_dia', 'map', 'spo2', 'etco2',
        'temperature', 'respiratory_rate', 'notes', 'recorded_by',
    ];

    protected $casts = [
        'time_recorded' => 'datetime',
        'heart_rate' => 'integer',
        'blood_pressure_sys' => 'integer',
        'blood_pressure_dia' => 'integer',
        'map' => 'integer',
        'spo2' => 'decimal:2',
        'etco2' => 'decimal:2',
        'temperature' => 'decimal:1',
        'respiratory_rate' => 'integer',
    ];

    public function anaesthesiaRecord(): BelongsTo
    {
        return $this->belongsTo(AnaesthesiaRecord::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
