<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AmbulanceCall extends Model
{
    use HasFactory;

    protected $fillable = [
        'call_number', 'ambulance_id', 'caller_name', 'caller_phone',
        'pickup_address', 'destination_address', 'patient_condition',
        'call_time', 'dispatch_time', 'arrival_time', 'return_time',
        'distance_km', 'charges', 'status', 'notes'
    ];

    protected $casts = [
        'call_time' => 'datetime',
        'dispatch_time' => 'datetime',
        'arrival_time' => 'datetime',
        'return_time' => 'datetime',
        'distance_km' => 'decimal:2',
        'charges' => 'decimal:2',
    ];

    public function ambulance(): BelongsTo
    {
        return $this->belongsTo(Ambulance::class);
    }
}
