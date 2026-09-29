<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class TbCase extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'patient_id', 'case_number', 'diagnosis_date', 'specimen_type',
        'test_method', 'test_result', 'pulmonary', 'drug_susceptible',
        'mdr_tb', 'status', 'treatment_start_date', 'treatment_end_date',
        'outcome_date', 'outcome', 'registered_by',
    ];

    protected $casts = [
        'diagnosis_date' => 'date',
        'treatment_start_date' => 'date',
        'treatment_end_date' => 'date',
        'outcome_date' => 'date',
        'pulmonary' => 'boolean',
        'drug_susceptible' => 'boolean',
        'mdr_tb' => 'boolean',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($case) {
            if (empty($case->case_number)) {
                $case->case_number = 'TB' . str_pad(TbCase::count() + 1, 6, '0', STR_PAD_LEFT);
            }
        });
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function registrant(): BelongsTo
    {
        return $this->belongsTo(User::class, 'registered_by');
    }

    public function treatments(): HasMany
    {
        return $this->hasMany(TbTreatment::class);
    }

    public function contacts(): HasMany
    {
        return $this->hasMany(TbContact::class);
    }
}
