<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CalibrationRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_id', 'calibration_date', 'next_due_date', 'result',
        'certificate_number', 'performed_by_vendor', 'vendor_name',
        'cost', 'notes',
    ];

    protected $casts = [
        'calibration_date' => 'date',
        'next_due_date' => 'date',
        'performed_by_vendor' => 'boolean',
        'cost' => 'decimal:2',
    ];

    public function equipment(): BelongsTo
    {
        return $this->belongsTo(MedicalEquipment::class, 'equipment_id');
    }
}
