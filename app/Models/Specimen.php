<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Specimen extends Model
{
    use HasFactory;

    protected $fillable = [
        'ot_schedule_id', 'patient_id', 'specimen_type', 'description',
        'collection_site', 'container_type', 'collected_by', 'collected_at',
        'sent_to_lab', 'lab_request_id',
    ];

    protected $casts = [
        'sent_to_lab' => 'boolean',
        'collected_at' => 'datetime',
    ];

    public function otSchedule(): BelongsTo
    {
        return $this->belongsTo(OtSchedule::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function collectedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'collected_by');
    }

    public function labRequest(): BelongsTo
    {
        return $this->belongsTo(LabRequest::class);
    }
}
