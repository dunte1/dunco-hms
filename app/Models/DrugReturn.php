<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DrugReturn extends Model
{
    use HasFactory;

    protected $fillable = [
        'dispensation_id', 'prescription_id', 'patient_id', 'medicine_id',
        'batch_id', 'quantity', 'reason', 'return_type', 'status',
        'approved_by', 'processed_by', 'processed_at',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'processed_at' => 'datetime',
    ];

    public function dispensation(): BelongsTo
    {
        return $this->belongsTo(Dispensation::class);
    }

    public function prescription(): BelongsTo
    {
        return $this->belongsTo(Prescription::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }

    public function batch(): BelongsTo
    {
        return $this->belongsTo(MedicineBatch::class, 'batch_id');
    }

    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }

    public function processedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }

    public function approve(User $user): bool
    {
        if ($this->status !== 'pending') {
            return false;
        }
        return $this->update(['status' => 'approved', 'approved_by' => $user->id]);
    }

    public function process(User $user): bool
    {
        if ($this->status !== 'approved') {
            return false;
        }
        return $this->update([
            'status' => 'processed',
            'processed_by' => $user->id,
            'processed_at' => now(),
        ]);
    }
}
