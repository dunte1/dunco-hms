<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ChemoInfusion extends Model
{
    use HasFactory;

    protected $fillable = [
        'chemo_cycle_id', 'patient_id', 'start_time', 'end_time',
        'drug_name', 'dose', 'volume_ml', 'rate', 'site',
        'nurse_id', 'status', 'complications',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'volume_ml' => 'decimal:2',
        'rate' => 'decimal:2',
    ];

    public function chemoCycle(): BelongsTo
    {
        return $this->belongsTo(ChemoCycle::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function nurse(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nurse_id');
    }
}
