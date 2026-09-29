<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AbgResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'icu_admission_id', 'patient_id', 'ph', 'pco2', 'po2', 'hco3',
        'be', 'sao2', 'lactate', 'interpretation', 'collected_at',
    ];

    protected $casts = [
        'ph' => 'decimal:2',
        'pco2' => 'decimal:1',
        'po2' => 'decimal:1',
        'hco3' => 'decimal:1',
        'be' => 'decimal:1',
        'sao2' => 'decimal:1',
        'lactate' => 'decimal:1',
        'collected_at' => 'datetime',
    ];

    public function icuAdmission(): BelongsTo
    {
        return $this->belongsTo(IcuAdmission::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }
}
