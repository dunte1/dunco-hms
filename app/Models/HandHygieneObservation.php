<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HandHygieneObservation extends Model
{
    use HasFactory;

    protected $fillable = [
        'observer_id', 'ward_id', 'observation_date', 'opportunities_observed',
        'hand_washes', 'compliance_rate', 'technique_score', 'notes',
    ];

    protected $casts = [
        'observation_date' => 'date',
        'opportunities_observed' => 'integer',
        'hand_washes' => 'integer',
        'compliance_rate' => 'decimal:2',
    ];

    public function observer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'observer_id');
    }

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }
}
