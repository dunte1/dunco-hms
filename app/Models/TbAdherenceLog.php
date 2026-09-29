<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TbAdherenceLog extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'tb_treatment_id', 'patient_id', 'log_date', 'doses_expected',
        'doses_taken', 'adherence_percentage', 'missed_reason',
        'counselling_done', 'logged_by',
    ];

    protected $casts = [
        'log_date' => 'date',
        'doses_expected' => 'integer',
        'doses_taken' => 'integer',
        'adherence_percentage' => 'decimal:2',
        'counselling_done' => 'boolean',
    ];

    public function treatment(): BelongsTo
    {
        return $this->belongsTo(TbTreatment::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function logger(): BelongsTo
    {
        return $this->belongsTo(User::class, 'logged_by');
    }
}
