<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ComplianceResponse extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_id', 'completed_by', 'status', 'notes',
        'evidence_path', 'completed_at', 'next_due_at',
    ];

    protected $casts = [
        'completed_at' => 'datetime',
        'next_due_at' => 'datetime',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(ComplianceItem::class, 'item_id');
    }

    public function completedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'completed_by');
    }

    public function isCompliant(): bool
    {
        return $this->status === 'compliant';
    }
}
