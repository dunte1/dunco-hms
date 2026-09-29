<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NeonatalFeed extends Model
{
    use HasFactory;

    protected $fillable = [
        'newborn_id', 'feed_time', 'feed_type', 'volume_ml',
        'duration_minutes', 'method', 'notes', 'recorded_by',
    ];

    protected $casts = [
        'feed_time' => 'datetime',
        'volume_ml' => 'decimal:1',
        'duration_minutes' => 'integer',
    ];

    public function newborn(): BelongsTo
    {
        return $this->belongsTo(Newborn::class);
    }

    public function recorder(): BelongsTo
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }
}
