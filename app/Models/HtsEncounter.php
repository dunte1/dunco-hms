<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HtsEncounter extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'encounter_date',
        'hts_number',
        'risk_assessment_done',
        'consent_given',
        'test_type',
        'test_result',
        'test_date',
        'tested_by',
        'counselled_before',
        'counselled_after',
        'referral_offered',
        'status',
    ];

    protected $casts = [
        'encounter_date' => 'date',
        'test_date' => 'date',
        'risk_assessment_done' => 'boolean',
        'consent_given' => 'boolean',
        'counselled_before' => 'boolean',
        'counselled_after' => 'boolean',
        'referral_offered' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($encounter) {
            if (empty($encounter->hts_number)) {
                $encounter->hts_number = 'HTS' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function tester(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tested_by');
    }

    public function partnerNotifications()
    {
        return $this->hasMany(PartnerNotification::class);
    }
}
