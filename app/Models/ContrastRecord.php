<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ContrastRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'radiology_request_id', 'patient_id', 'contrast_type',
        'contrast_agent', 'volume_ml', 'route', 'reaction_notes',
        'administered_by', 'administered_at',
    ];

    protected $casts = [
        'volume_ml' => 'decimal:2',
        'administered_at' => 'datetime',
    ];

    public function radiologyRequest(): BelongsTo
    {
        return $this->belongsTo(RadiologyRequest::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function administeredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'administered_by');
    }
}
