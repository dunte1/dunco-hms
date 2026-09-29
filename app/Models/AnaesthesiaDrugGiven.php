<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AnaesthesiaDrugGiven extends Model
{
    use HasFactory;

    protected $table = 'anaesthesia_drugs_given';

    protected $fillable = [
        'anaesthesia_record_id', 'medicine_id', 'dose', 'route',
        'time_administered', 'notes',
    ];

    protected $casts = [
        'time_administered' => 'datetime',
    ];

    public function anaesthesiaRecord(): BelongsTo
    {
        return $this->belongsTo(AnaesthesiaRecord::class);
    }

    public function medicine(): BelongsTo
    {
        return $this->belongsTo(Medicine::class);
    }
}
