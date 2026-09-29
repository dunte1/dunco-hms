<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InfusionRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'icu_admission_id', 'patient_id', 'drug_name', 'concentration',
        'rate_ml_hr', 'volume_infused_ml', 'start_time', 'end_time',
        'status', 'stopped_by', 'reason_for_stop',
    ];

    protected $casts = [
        'rate_ml_hr' => 'decimal:2',
        'volume_infused_ml' => 'decimal:1',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function icuAdmission(): BelongsTo
    {
        return $this->belongsTo(IcuAdmission::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function stoppedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'stopped_by');
    }
}
