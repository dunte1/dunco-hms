<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NotifiableDiseaseReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'surveillance_case_id', 'report_week', 'report_year', 'facility_code',
        'disease_name', 'cases_count', 'deaths_count', 'reported_to_moh', 'reported_at',
    ];

    protected $casts = [
        'cases_count' => 'integer',
        'deaths_count' => 'integer',
        'reported_to_moh' => 'boolean',
        'reported_at' => 'datetime',
    ];

    public function surveillanceCase(): BelongsTo
    {
        return $this->belongsTo(SurveillanceCase::class);
    }
}
