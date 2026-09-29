<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class HivCareEnrollment extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'hts_encounter_id',
        'enrollment_date',
        'art_number',
        'who_stage',
        'baseline_cd4',
        'baseline_viral_load',
        'enrolled_by',
        'status',
    ];

    protected $casts = [
        'enrollment_date' => 'date',
        'who_stage' => 'integer',
        'baseline_cd4' => 'integer',
        'baseline_viral_load' => 'integer',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function htsEncounter(): BelongsTo
    {
        return $this->belongsTo(HtsEncounter::class);
    }

    public function enrollee(): BelongsTo
    {
        return $this->belongsTo(User::class, 'enrolled_by');
    }

    public function artRegimens(): HasMany
    {
        return $this->hasMany(ArtRegimen::class, 'care_enrollment_id');
    }

    public function viralLoads(): HasMany
    {
        return $this->hasMany(ViralLoadResult::class, 'care_enrollment_id');
    }

    public function activeRegimen()
    {
        return $this->hasOne(ArtRegimen::class, 'care_enrollment_id')->latest('start_date');
    }
}
