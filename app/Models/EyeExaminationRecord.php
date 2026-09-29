<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EyeExaminationRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'examiner_id', 'visual_acuity_right', 'visual_acuity_left',
        'iop_right', 'iop_left', 'refraction_right', 'refraction_left',
        'diagnosis', 'treatment', 'glasses_prescribed',
    ];

    protected $casts = [
        'visual_acuity_right' => 'decimal:1',
        'visual_acuity_left' => 'decimal:1',
        'glasses_prescribed' => 'boolean',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function examiner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'examiner_id');
    }
}
