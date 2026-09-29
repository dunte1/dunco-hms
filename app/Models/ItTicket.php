<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ItTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_number', 'subject', 'description', 'category', 'priority', 'status',
        'reported_by', 'assigned_to', 'reported_at', 'first_response_at', 'resolved_at',
        'resolution_notes',
    ];

    protected $casts = [
        'reported_at' => 'datetime',
        'first_response_at' => 'datetime',
        'resolved_at' => 'datetime',
    ];

    public function reportedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reported_by');
    }

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }
}
