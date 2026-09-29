<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AmbulanceFuelLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ambulance_id', 'fill_date', 'liters', 'cost',
        'odometer_km', 'station', 'recorded_by',
    ];

    protected $casts = [
        'fill_date' => 'date',
        'liters' => 'decimal:2',
        'cost' => 'decimal:2',
        'odometer_km' => 'integer',
    ];

    public function ambulance(): BelongsTo
    {
        return $this->belongsTo(Ambulance::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
