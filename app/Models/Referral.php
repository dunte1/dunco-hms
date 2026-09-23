<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'opd_visit_id', 'ipd_admission_id', 'referral_number',
        'referral_type', 'referring_facility', 'receiving_facility',
        'referring_doctor_id', 'receiving_doctor_id', 'reason',
        'clinical_summary', 'investigations_done', 'urgency', 'status',
        'referred_at', 'accepted_at', 'completed_at', 'outcome', 'created_by',
    ];

    protected $casts = [
        'referred_at' => 'datetime',
        'accepted_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function opdVisit(): BelongsTo
    {
        return $this->belongsTo(OpdVisit::class);
    }

    public function ipdAdmission(): BelongsTo
    {
        return $this->belongsTo(IpdAdmission::class);
    }

    public function referringDoctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'referring_doctor_id');
    }

    public function receivingDoctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'receiving_doctor_id');
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($referral) {
            if (!$referral->referral_number) {
                $prefix = $referral->referral_type === 'in' ? 'REF-IN' : 'REF-OUT';
                $referral->referral_number = $prefix . '-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
            }
            if (!$referral->referred_at) {
                $referral->referred_at = now();
            }
        });
    }
}
