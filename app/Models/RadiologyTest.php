<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class RadiologyTest extends Model
{
    use HasFactory;

    protected $fillable = [
        'test_name', 'category_id', 'description', 'price',
        'preparation_instructions', 'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(RadiologyCategory::class, 'category_id');
    }

    public function requests(): HasMany
    {
        return $this->hasMany(RadiologyRequest::class);
    }
}
