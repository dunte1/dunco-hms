<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AmbulanceMaintenanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'ambulance_id', 'maintenance_type', 'description', 'service_date',
        'next_service_date', 'cost', 'provider', 'status',
    ];

    protected $casts = [
        'service_date' => 'date',
        'next_service_date' => 'date',
        'cost' => 'decimal:2',
    ];

    public function ambulance(): BelongsTo
    {
        return $this->belongsTo(Ambulance::class);
    }
}
