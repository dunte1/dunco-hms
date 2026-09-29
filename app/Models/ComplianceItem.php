<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ComplianceItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'checklist_id', 'title', 'description', 'is_mandatory', 'sort_order',
    ];

    protected $casts = [
        'is_mandatory' => 'boolean',
    ];

    public function checklist(): BelongsTo
    {
        return $this->belongsTo(ComplianceChecklist::class, 'checklist_id');
    }

    public function responses(): HasMany
    {
        return $this->hasMany(ComplianceResponse::class, 'item_id');
    }

    public function scopeMandatory($query)
    {
        return $query->where('is_mandatory', true);
    }
}
