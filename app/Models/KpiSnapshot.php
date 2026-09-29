<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KpiSnapshot extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'kpi_definition_id', 'snapshot_date', 'actual_value',
        'target_value', 'status', 'notes', 'created_at',
    ];

    protected $casts = [
        'snapshot_date' => 'date',
        'actual_value' => 'decimal:4',
        'target_value' => 'decimal:2',
        'created_at' => 'datetime',
    ];

    public function kpiDefinition(): BelongsTo
    {
        return $this->belongsTo(KpiDefinition::class, 'kpi_definition_id');
    }
}
