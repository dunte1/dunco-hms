<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BiAnalytic extends Model
{
    use HasFactory;

    protected $fillable = [
        'metric_name', 'metric_type', 'data_points', 'date_from', 'date_to',
        'granularity', 'predictions', 'insights'
    ];

    protected $casts = [
        'data_points' => 'array',
        'date_from' => 'date',
        'date_to' => 'date',
        'predictions' => 'array',
    ];
}
