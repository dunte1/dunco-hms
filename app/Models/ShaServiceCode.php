<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShaServiceCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code', 'name', 'description', 'category',
        'tariff_amount', 'requires_authorization', 'is_active',
    ];

    protected $casts = [
        'tariff_amount' => 'decimal:2',
        'requires_authorization' => 'boolean',
        'is_active' => 'boolean',
    ];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }
}
