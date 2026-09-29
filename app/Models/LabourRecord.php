<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LabourRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'pregnancy_id', 'patient_id', 'admission_time', 'labour_start_time',
        'membrane_status', 'rupture_time', 'cervical_dilation', 'liquor',
        'presenting_part', 'labour_progress', 'complications', 'status', 'ward_id',
    ];

    protected $casts = [
        'admission_time' => 'datetime',
        'labour_start_time' => 'datetime',
        'rupture_time' => 'datetime',
    ];

    public function pregnancy(): BelongsTo
    {
        return $this->belongsTo(Pregnancy::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    public function partographEntries(): HasMany
    {
        return $this->hasMany(PartographEntry::class);
    }
}
