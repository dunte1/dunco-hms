<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IotBedSensor extends Model
{
    use HasFactory;

    protected $fillable = [
        'bed_id', 'sensor_id', 'sensor_type', 'sensor_data', 'is_occupied',
        'vital_signs', 'alert_level', 'alerts', 'is_active'
    ];

    protected $casts = [
        'sensor_data' => 'array',
        'is_occupied' => 'boolean',
        'vital_signs' => 'array',
        'is_active' => 'boolean',
    ];

    public function bed(): BelongsTo
    {
        return $this->belongsTo(Bed::class);
    }
}
