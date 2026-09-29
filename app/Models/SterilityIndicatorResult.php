<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SterilityIndicatorResult extends Model
{
    use HasFactory;

    protected $table = 'sterility_indicator_results';

    protected $fillable = [
        'sterilizer_run_id', 'indicator_type', 'result',
        'batch_number', 'expiry_date', 'recorded_by', 'recorded_at',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'recorded_at' => 'datetime',
    ];

    public function sterilizerRun()
    {
        return $this->belongsTo(SterilizerRun::class);
    }

    public function recordedBy()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
