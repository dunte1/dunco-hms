<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImagingSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'radiology_request_id', 'patient_id', 'radiology_test_id', 'modality',
        'scheduled_date', 'scheduled_time', 'status', 'scheduled_by', 'notes',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
    ];

    public function radiologyRequest(): BelongsTo
    {
        return $this->belongsTo(RadiologyRequest::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function radiologyTest(): BelongsTo
    {
        return $this->belongsTo(RadiologyTest::class);
    }

    public function scheduledBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'scheduled_by');
    }
}
