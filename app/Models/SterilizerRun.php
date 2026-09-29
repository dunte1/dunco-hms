<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SterilizerRun extends Model
{
    use HasFactory;

    protected $fillable = [
        'sterilizer_name', 'load_number', 'start_time', 'end_time',
        'temperature', 'pressure', 'exposure_time_minutes', 'cycle_type',
        'status', 'operator_id',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'temperature' => 'decimal:1',
        'pressure' => 'decimal:1',
        'exposure_time_minutes' => 'integer',
        'load_number' => 'integer',
    ];

    public function operator()
    {
        return $this->belongsTo(User::class, 'operator_id');
    }

    public function indicators()
    {
        return $this->hasMany(SterilityIndicatorResult::class, 'sterilizer_run_id');
    }
}
