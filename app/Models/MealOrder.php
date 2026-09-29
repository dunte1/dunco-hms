<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MealOrder extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id', 'ward_id', 'meal_type', 'diet_type',
        'quantity', 'order_date', 'order_time', 'status',
        'ordered_by', 'delivered_at',
    ];

    protected $casts = [
        'order_date' => 'date',
        'order_time' => 'string',
        'delivered_at' => 'datetime',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function ward(): BelongsTo
    {
        return $this->belongsTo(Ward::class);
    }

    public function orderedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ordered_by');
    }
}
