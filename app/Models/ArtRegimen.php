<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ArtRegimen extends Model
{
    use HasFactory;

    protected $fillable = [
        'care_enrollment_id',
        'regimen_code',
        'start_date',
        'end_date',
        'reason_for_change',
        'regimen_line',
        'prescribed_by',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    public function careEnrollment(): BelongsTo
    {
        return $this->belongsTo(HivCareEnrollment::class, 'care_enrollment_id');
    }

    public function prescriber(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prescribed_by');
    }
}
