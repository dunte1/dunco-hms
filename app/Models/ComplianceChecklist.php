<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class ComplianceChecklist extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'description', 'frequency', 'category', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function items(): HasMany
    {
        return $this->hasMany(ComplianceItem::class, 'checklist_id');
    }

    public function responses(): HasMany
    {
        return $this->hasManyThrough(ComplianceResponse::class, ComplianceItem::class, 'checklist_id', 'item_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeForFrequency($query, string $frequency)
    {
        return $query->where('frequency', $frequency);
    }
}
