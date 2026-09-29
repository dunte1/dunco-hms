<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NeonatalAssessment extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'newborn_id', 'assessment_date', 'weight_grams', 'length_cm',
        'head_circumference_cm', 'temperature', 'heart_rate', 'respiratory_rate',
        'feeding_type', 'stool_passed', 'jaundice', 'reflexes', 'cried_at_birth',
        'notes', 'assessed_by', 'created_at',
    ];

    protected $casts = [
        'assessment_date' => 'date',
        'weight_grams' => 'integer',
        'length_cm' => 'decimal:1',
        'head_circumference_cm' => 'decimal:1',
        'temperature' => 'decimal:1',
        'stool_passed' => 'boolean',
        'cried_at_birth' => 'boolean',
    ];

    public function newborn(): BelongsTo
    {
        return $this->belongsTo(Newborn::class);
    }

    public function assessor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assessed_by');
    }
}
