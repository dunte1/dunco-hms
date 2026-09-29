<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WorkOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'request_id', 'equipment_id', 'work_type', 'title', 'description',
        'parts_used', 'labor_hours', 'cost', 'status', 'assigned_to',
        'started_at', 'completed_at',
    ];

    protected $casts = [
        'labor_hours' => 'decimal:2',
        'cost' => 'decimal:2',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function request(): BelongsTo
    {
        return $this->belongsTo(MaintenanceRequest::class, 'request_id');
    }

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(MedicalEquipment::class, 'equipment_id');
    }

    public function assignee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
