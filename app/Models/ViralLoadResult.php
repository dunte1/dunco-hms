<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ViralLoadResult extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'care_enrollment_id',
        'patient_id',
        'result_date',
        'viral_load_copies',
        'detection_limit',
        'suppression_status',
        'ordered_by',
        'created_at',
    ];

    protected $casts = [
        'result_date' => 'date',
        'viral_load_copies' => 'integer',
        'created_at' => 'datetime',
    ];

    public function careEnrollment(): BelongsTo
    {
        return $this->belongsTo(HivCareEnrollment::class, 'care_enrollment_id');
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function orderer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ordered_by');
    }
}
