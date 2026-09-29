<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TbScreening extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'patient_id', 'screening_date', 'symptoms_cough', 'symptoms_fever',
        'symptoms_night_sweats', 'symptoms_weight_loss', 'symptoms_other',
        'contact_history', 'hiv_status', 'chest_xray_result', 'screen_result',
        'screened_by',
    ];

    protected $casts = [
        'screening_date' => 'date',
        'symptoms_cough' => 'boolean',
        'symptoms_fever' => 'boolean',
        'symptoms_night_sweats' => 'boolean',
        'symptoms_weight_loss' => 'boolean',
        'contact_history' => 'boolean',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function screener(): BelongsTo
    {
        return $this->belongsTo(User::class, 'screened_by');
    }
}
