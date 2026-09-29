<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class KpiDefinition extends Model
{
    protected $fillable = [
        'code', 'name', 'description', 'source_module', 'query_sql',
        'target_value', 'unit', 'comparison_period', 'is_active',
    ];

    protected $casts = [
        'target_value' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    public function snapshots(): HasMany
    {
        return $this->hasMany(KpiSnapshot::class, 'kpi_definition_id');
    }
}
