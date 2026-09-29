<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CrossmatchRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'blood_request_id', 'patient_id', 'blood_group_id', 'sample_date',
        'requested_by', 'result', 'tested_by', 'tested_at',
    ];

    protected $casts = [
        'sample_date' => 'date',
        'tested_at' => 'datetime',
    ];

    public function bloodRequest(): BelongsTo
    {
        return $this->belongsTo(BloodRequest::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function bloodGroup(): BelongsTo
    {
        return $this->belongsTo(BloodGroup::class);
    }

    public function requestedByDoctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'requested_by');
    }

    public function testedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'tested_by');
    }
}
