<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HaiSurveillanceRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'infection_type', 'organism', 'ward_id',
        'onset_date', 'reported_date', 'reported_by', 'status',
    ];

    protected $casts = [
        'onset_date' => 'date',
        'reported_date' => 'date',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
