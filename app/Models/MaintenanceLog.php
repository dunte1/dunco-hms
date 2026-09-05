<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MaintenanceLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id', 'maintenance_type', 'performed_by', 'performed_at',
        'description', 'parts_replaced', 'cost', 'status',
        'next_action', 'next_due_date',
    ];

    protected $casts = [
        'performed_at' => 'datetime',
        'next_due_date' => 'date',
        'cost' => 'decimal:2',
    ];

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(MedicalEquipment::class, 'equipment_id');
    }
}
