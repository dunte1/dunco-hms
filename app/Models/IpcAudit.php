<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IpcAudit extends Model
{
    use HasFactory;

    protected $fillable = [
        'audit_type', 'ward_id', 'audit_date', 'score', 'findings',
        'corrective_actions', 'auditor_id', 'next_audit_date',
    ];

    protected $casts = [
        'audit_date' => 'date',
        'score' => 'decimal:2',
        'next_audit_date' => 'date',
    ];

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    public function auditor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'auditor_id');
    }
}
