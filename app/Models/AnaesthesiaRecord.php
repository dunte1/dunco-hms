<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class AnaesthesiaRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'ot_schedule_id', 'patient_id', 'anaesthetist_id', 'anaesthesia_type',
        'induction_time', 'intubation_time', 'start_time', 'end_time',
        'extubation_time', 'total_duration_minutes', 'ebl_ml', 'urine_output_ml',
        'fluids_given_ml', 'blood_products', 'complications', 'notes', 'status',
    ];

    protected $casts = [
        'induction_time' => 'datetime',
        'intubation_time' => 'datetime',
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'extubation_time' => 'datetime',
        'total_duration_minutes' => 'integer',
        'ebl_ml' => 'integer',
        'urine_output_ml' => 'integer',
        'fluids_given_ml' => 'integer',
    ];

    public function otSchedule(): BelongsTo
    {
        return $this->belongsTo(OtSchedule::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function anaesthetist(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'anaesthetist_id');
    }

    public function drugsGiven(): HasMany
    {
        return $this->hasMany(AnaesthesiaDrugGiven::class);
    }

    public function intraopVitals(): HasMany
    {
        return $this->hasMany(IntraopVital::class);
    }

    public function complications(): HasMany
    {
        return $this->hasMany(AnaesthesiaComplication::class);
    }

    public function postReview(): HasOne
    {
        return $this->hasOne(PostAnaesthesiaReview::class);
    }

    public function getStatusBadgeAttribute(): string
    {
        return match ($this->status) {
            'in_progress' => 'bg-yellow-100 text-yellow-800',
            'completed' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
