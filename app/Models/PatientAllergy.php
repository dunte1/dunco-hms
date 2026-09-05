<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientAllergy extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'allergen', 'allergen_type', 'reaction',
        'severity', 'onset_date', 'is_active', 'notes',
    ];

    protected $casts = [
        'onset_date' => 'date',
        'is_active' => 'boolean',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function getSeverityBadgeAttribute(): string
    {
        return match($this->severity) {
            'mild' => 'bg-green-100 text-green-800',
            'moderate' => 'bg-yellow-100 text-yellow-800',
            'severe' => 'bg-orange-100 text-orange-800',
            'anaphylaxis' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
