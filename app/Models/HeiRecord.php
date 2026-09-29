<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HeiRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'newborn_id',
        'mother_patient_id',
        'mother_art_number',
        'birth_date',
        'pcr_1_result',
        'pcr_1_date',
        'pcr_2_result',
        'pcr_2_date',
        'pcr_6_result',
        'pcr_6_date',
        'final_status',
        'prophylaxis_given',
        'cotrimoxazole_start',
        'status',
    ];

    protected $casts = [
        'birth_date' => 'date',
        'pcr_1_date' => 'date',
        'pcr_2_date' => 'date',
        'pcr_6_date' => 'date',
        'cotrimoxazole_start' => 'date',
        'prophylaxis_given' => 'boolean',
    ];

    public function newborn(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'newborn_id');
    }

    public function mother(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'mother_patient_id');
    }
}
