<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransfusionReaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transfusion_id', 'patient_id', 'reaction_type', 'severity',
        'symptoms', 'onset_time', 'treatment_given', 'reported_by', 'reported_at',
    ];

    protected $casts = [
        'onset_time' => 'datetime',
        'reported_at' => 'datetime',
    ];

    public function transfusion(): BelongsTo
    {
        return $this->belongsTo(Transfusion::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }
}
