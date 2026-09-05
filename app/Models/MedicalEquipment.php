<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedicalEquipment extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'category', 'department', 'model_number', 'serial_number',
        'manufacturer', 'purchase_date', 'warranty_expiry', 'status',
        'location', 'last_maintenance', 'next_maintenance', 'current_value',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'warranty_expiry' => 'date',
        'last_maintenance' => 'datetime',
        'next_maintenance' => 'datetime',
        'current_value' => 'decimal:2',
    ];

    public function maintenanceLogs(): HasMany
    {
        return $this->hasMany(MaintenanceLog::class, 'equipment_id');
    }
}
