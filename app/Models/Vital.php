<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Vital extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'opd_visit_id', 'ipd_admission_id', 'vitals_number',
        'temperature', 'pulse_rate', 'systolic_bp', 'diastolic_bp',
        'respiratory_rate', 'oxygen_saturation', 'blood_glucose',
        'weight_kg', 'height_cm', 'bmi', 'notes', 'recorded_by',
    ];

    protected $casts = [
        'temperature' => 'decimal:1',
        'oxygen_saturation' => 'decimal:1',
        'blood_glucose' => 'decimal:1',
        'weight_kg' => 'decimal:2',
        'height_cm' => 'decimal:1',
        'bmi' => 'decimal:1',
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

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function ($vital) {
            if (!$vital->vitals_number) {
                $vital->vitals_number = 'VIT-' . date('Y') . '-' . str_pad(static::count() + 1, 6, '0', STR_PAD_LEFT);
            }
            if (!$vital->bmi && $vital->weight_kg && $vital->height_cm && $vital->height_cm > 0) {
                $vital->bmi = round($vital->weight_kg / pow($vital->height_cm / 100, 2), 1);
            }
        });
    }
}
