<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BloodDonation extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id', 'blood_group_id', 'volume_ml', 'donation_date', 'donation_type',
        'hemoglobin_g_dl', 'blood_pressure_sys', 'blood_pressure_dia', 'pulse_rate',
        'weight_kg', 'status', 'collected_by',
    ];

    protected $casts = [
        'donation_date' => 'date',
        'hemoglobin_g_dl' => 'decimal:1',
        'weight_kg' => 'decimal:1',
    ];

    public function donor(): BelongsTo
    {
        return $this->belongsTo(BloodDonor::class);
    }

    public function bloodGroup(): BelongsTo
    {
        return $this->belongsTo(BloodGroup::class);
    }

    public function collector(): BelongsTo
    {
        return $this->belongsTo(User::class, 'collected_by');
    }

    public function screeningResult(): HasOne
    {
        return $this->hasOne(BloodScreeningResult::class);
    }

    public function bloodUnits(): HasMany
    {
        return $this->hasMany(BloodUnit::class, 'donation_id');
    }
}
