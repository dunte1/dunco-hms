<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ChemoProtocol extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name', 'code', 'regimen', 'cycle_count', 'cycle_days',
        'drugs', 'indication', 'is_active',
    ];

    protected $casts = [
        'drugs' => 'array',
        'is_active' => 'boolean',
    ];
}
