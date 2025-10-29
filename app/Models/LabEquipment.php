<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LabEquipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_name', 'equipment_type', 'model_number', 'serial_number',
        'manufacturer', 'ip_address', 'port', 'connection_type', 'configuration',
        'is_connected', 'status', 'notes'
    ];

    protected $casts = [
        'configuration' => 'array',
        'is_connected' => 'boolean',
    ];

    public function results(): HasMany
    {
        return $this->hasMany(EquipmentResult::class);
    }
}
