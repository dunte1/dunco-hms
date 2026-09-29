<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class VentilatorSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'icu_admission_id', 'mode', 'set_rate', 'tidal_volume', 'peep',
        'fio2', 'pressure_support', 'start_time', 'end_time', 'status',
        'reason_for_change', 'recorded_by',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function icuAdmission(): BelongsTo
    {
        return $this->belongsTo(IcuAdmission::class);
    }

    public function recordedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
