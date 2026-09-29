<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class RecordRequest extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'mrd_file_id', 'request_type', 'requested_by', 'reason',
        'status', 'approved_by', 'approved_at', 'released_at', 'notes',
    ];

    protected $casts = [
        'approved_at' => 'datetime',
        'released_at' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function mrdFile(): BelongsTo
    {
        return $this->belongsTo(MrdFile::class, 'mrd_file_id');
    }

    public function requestedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'requested_by');
    }

    public function approvedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
