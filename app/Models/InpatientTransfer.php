<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InpatientTransfer extends Model
{
    use HasFactory;

    protected $fillable = [
        'ipd_admission_id', 'patient_id', 'from_ward_id', 'to_ward_id',
        'from_bed_id', 'to_bed_id', 'reason', 'transferred_by', 'transferred_at',
    ];

    protected $casts = [
        'transferred_at' => 'datetime',
    ];

    public function ipdAdmission(): BelongsTo
    {
        return $this->belongsTo(IpdAdmission::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function fromWard(): BelongsTo
    {
        return $this->belongsTo(Ward::class, 'from_ward_id');
    }

    public function toWard(): BelongsTo
    {
        return $this->belongsTo(Ward::class, 'to_ward_id');
    }

    public function fromBed(): BelongsTo
    {
        return $this->belongsTo(Bed::class, 'from_bed_id');
    }

    public function toBed(): BelongsTo
    {
        return $this->belongsTo(Bed::class, 'to_bed_id');
    }

    public function transferer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'transferred_by');
    }
}
