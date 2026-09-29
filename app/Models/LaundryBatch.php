<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LaundryBatch extends Model
{
    use HasFactory;

    protected $fillable = [
        'batch_number', 'collected_date', 'processed_date', 'total_items',
        'status', 'processed_by', 'ward_ids', 'notes',
    ];

    protected $casts = [
        'collected_date' => 'date',
        'processed_date' => 'date',
        'ward_ids' => 'array',
    ];

    public function processedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'processed_by');
    }
}
