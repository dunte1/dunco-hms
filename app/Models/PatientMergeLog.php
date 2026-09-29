<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientMergeLog extends Model
{
    use HasFactory;

    protected $table = 'patient_merge_log';

    protected $fillable = [
        'primary_patient_id',
        'duplicate_patient_id',
        'merged_by',
        'merge_reason',
        'data_migrated',
        'merged_at',
        'reversed_at',
        'reversed_by',
    ];

    protected $casts = [
        'data_migrated' => 'boolean',
        'merged_at' => 'datetime',
        'reversed_at' => 'datetime',
    ];

    public function primaryPatient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'primary_patient_id');
    }

    public function duplicatePatient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'duplicate_patient_id');
    }

    public function mergedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'merged_by');
    }

    public function reversedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reversed_by');
    }
}
