<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LabCriticalAlert extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'lab_request_item_id', 'patient_id', 'alert_type',
        'result_value', 'reference_range', 'message',
        'acknowledged_by', 'acknowledged_at', 'is_acknowledged', 'created_at',
    ];

    protected $casts = [
        'acknowledged_at' => 'datetime',
        'created_at' => 'datetime',
        'is_acknowledged' => 'boolean',
    ];

    public function labRequestItem(): BelongsTo
    {
        return $this->belongsTo(LabRequestItem::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function acknowledgedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'acknowledged_by');
    }

    public function acknowledge(User $user): void
    {
        $this->update([
            'is_acknowledged' => true,
            'acknowledged_by' => $user->id,
            'acknowledged_at' => now(),
        ]);
    }
}
