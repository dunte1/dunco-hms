<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OutbreakInvestigation extends Model
{
    use HasFactory;

    protected $fillable = [
        'outbreak_event_id', 'disease_name', 'investigation_start_date',
        'investigation_end_date', 'source_identified', 'source_description',
        'control_measures', 'status', 'investigated_by',
    ];

    protected $casts = [
        'investigation_start_date' => 'date',
        'investigation_end_date' => 'date',
        'source_identified' => 'boolean',
    ];

    public function outbreakEvent(): BelongsTo
    {
        return $this->belongsTo(OutbreakEvent::class);
    }

    public function investigatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'investigated_by');
    }
}
