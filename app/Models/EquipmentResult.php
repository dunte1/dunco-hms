<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EquipmentResult extends Model
{
    use HasFactory;

    protected $fillable = [
        'lab_equipment_id', 'lab_request_id', 'raw_data', 'processed_data',
        'result_status', 'notes'
    ];

    protected $casts = [
        'raw_data' => 'array',
        'processed_data' => 'array',
    ];

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(LabEquipment::class, 'lab_equipment_id');
    }

    public function labRequest(): BelongsTo
    {
        return $this->belongsTo(LabRequest::class);
    }
}
