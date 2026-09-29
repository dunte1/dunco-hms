<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PepPrepRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'record_type',
        'start_date',
        'end_date',
        'indication',
        'regimen',
        'prescribed_by',
        'status',
        'discontinuation_reason',
        'follow_up_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'follow_up_date' => 'date',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function prescriber(): BelongsTo
    {
        return $this->belongsTo(User::class, 'prescribed_by');
    }
}
