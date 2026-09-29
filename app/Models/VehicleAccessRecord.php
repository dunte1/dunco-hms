<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VehicleAccessRecord extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'vehicle_registration', 'driver_name', 'driver_id_number', 'purpose',
        'destination', 'arrival_time', 'departure_time', 'gate_pass_number', 'recorded_by',
    ];

    protected $casts = [
        'arrival_time' => 'datetime',
        'departure_time' => 'datetime',
    ];

    public function recordedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
