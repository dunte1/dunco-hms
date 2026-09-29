<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class AmbulanceTrip extends Model
{
    use HasFactory;

    protected $fillable = [
        'ambulance_id', 'patient_id', 'pickup_location', 'dropoff_location',
        'departure_time', 'arrival_time', 'distance_km', 'status', 'trip_type',
    ];

    protected $casts = [
        'departure_time' => 'datetime',
        'arrival_time' => 'datetime',
        'distance_km' => 'decimal:2',
    ];

    public function ambulance(): BelongsTo
    {
        return $this->belongsTo(Ambulance::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function handoverRecords(): HasMany
    {
        return $this->hasMany(PatientHandoverRecord::class, 'trip_id');
    }
}
