<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Delivery extends Model
{
    use HasFactory;

    protected $fillable = [
        'pregnancy_id', 'patient_id', 'labour_record_id', 'delivery_date',
        'delivery_time', 'mode', 'baby_sex', 'birth_weight_grams',
        'apgar_1_min', 'apgar_5_min', 'alive', 'complications',
        'placenta_delivered_time', 'placenta_complete', 'blood_loss_ml',
        'delivered_by',
    ];

    protected $casts = [
        'delivery_date' => 'date',
        'delivery_time' => 'datetime:H:i',
        'placenta_delivered_time' => 'datetime',
        'alive' => 'boolean',
        'placenta_complete' => 'boolean',
    ];

    public function pregnancy(): BelongsTo
    {
        return $this->belongsTo(Pregnancy::class);
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function labourRecord(): BelongsTo
    {
        return $this->belongsTo(LabourRecord::class);
    }

    public function deliveredBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'delivered_by');
    }
}
