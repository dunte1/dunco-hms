<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AntibioticUsageRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'antibiotic_name', 'indication', 'start_date',
        'end_date', 'ddd', 'route', 'ward_id', 'prescriber_id',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'ddd' => 'decimal:2',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    public function prescriber(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prescriber_id');
    }
}
