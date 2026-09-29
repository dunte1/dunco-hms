<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NutritionRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'nutritionist_id', 'assessment_type', 'bmi',
        'malnutrition_risk', 'diet_plan', 'calorie_target',
        'protein_target', 'notes', 'status',
    ];

    protected $casts = [
        'bmi' => 'decimal:1',
        'protein_target' => 'decimal:1',
        'calorie_target' => 'integer',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function nutritionist(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nutritionist_id');
    }
}
