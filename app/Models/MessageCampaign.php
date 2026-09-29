<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MessageCampaign extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'channel', 'template_id', 'target_audience', 'status',
        'total_recipients', 'sent_count', 'failed_count', 'started_by',
        'started_at', 'completed_at',
    ];

    protected $casts = [
        'target_audience' => 'array',
        'total_recipients' => 'integer',
        'sent_count' => 'integer',
        'failed_count' => 'integer',
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
    ];

    public function template(): BelongsTo
    {
        return $this->belongsTo(MessageTemplate::class, 'template_id');
    }

    public function startedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'started_by');
    }
}
