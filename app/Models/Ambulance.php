<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ambulance extends Model
{
    use HasFactory;

    protected $fillable = [
        'vehicle_number', 'driver_name', 'driver_phone', 'vehicle_type',
        'equipment_list', 'is_available', 'status'
    ];

    protected $casts = [
        'is_available' => 'boolean',
    ];

    public function calls(): HasMany
    {
        return $this->hasMany(AmbulanceCall::class);
    }

    public function emergencyAdmissions(): HasMany
    {
        return $this->hasMany(EmergencyAdmission::class);
    }
}
