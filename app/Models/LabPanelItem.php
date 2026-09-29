<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LabPanelItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'panel_id', 'lab_test_id', 'sort_order',
    ];

    public function panel(): BelongsTo
    {
        return $this->belongsTo(LabPanel::class, 'panel_id');
    }

    public function labTest(): BelongsTo
    {
        return $this->belongsTo(LabTest::class, 'lab_test_id');
    }
}
