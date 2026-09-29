<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class BenefitPackage extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'insurance_provider_id',
        'name',
        'description',
        'coverage_percentage',
        'max_amount',
        'is_active',
    ];

    protected $casts = [
        'coverage_percentage' => 'decimal:2',
        'max_amount' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function insuranceProvider(): BelongsTo
    {
        return $this->belongsTo(InsuranceProvider::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
