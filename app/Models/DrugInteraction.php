<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DrugInteraction extends Model
{
    use HasFactory;

    protected $fillable = [
        'drug_a_id', 'drug_b_id', 'severity', 'description',
        'clinical_effect', 'management_advice', 'source', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function drugA(): BelongsTo
    {
        return $this->belongsTo(Medicine::class, 'drug_a_id');
    }

    public function drugB(): BelongsTo
    {
        return $this->belongsTo(Medicine::class, 'drug_b_id');
    }

    public function getSeverityBadgeAttribute(): string
    {
        return match($this->severity) {
            'critical' => 'bg-red-100 text-red-800',
            'severe' => 'bg-orange-100 text-orange-800',
            'moderate' => 'bg-yellow-100 text-yellow-800',
            'mild' => 'bg-green-100 text-green-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }
}
