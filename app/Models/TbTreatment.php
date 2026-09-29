<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TbTreatment extends Model
{
    protected $fillable = [
        'tb_case_id', 'regimen', 'phase', 'start_date', 'end_date',
        'weight_kg', 'drugs_given', 'status', 'prescribed_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'weight_kg' => 'decimal:2',
        'drugs_given' => 'array',
    ];

    public function tbCase(): BelongsTo
    {
        return $this->belongsTo(TbCase::class);
    }

    public function doctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'prescribed_by');
    }

    public function adherenceLogs(): HasMany
    {
        return $this->hasMany(TbAdherenceLog::class);
    }
}
