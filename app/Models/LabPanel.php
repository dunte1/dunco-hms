<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabPanel extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'code', 'category_id', 'description', 'price', 'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(LabCategory::class, 'category_id');
    }

    public function items(): HasMany
    {
        return $this->hasMany(LabPanelItem::class, 'panel_id');
    }
}
