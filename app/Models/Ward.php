<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Ward extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'code', 'description', 'ward_type', 'capacity',
        'department_id', 'nurse_in_charge_id', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function department(): BelongsTo
    {
        return $this->belongsTo(EmployeeDepartment::class, 'department_id');
    }

    public function nurseInCharge(): BelongsTo
    {
        return $this->belongsTo(User::class, 'nurse_in_charge_id');
    }

    public function beds(): HasMany
    {
        return $this->hasMany(Bed::class);
    }

    public function getOccupiedBedsCountAttribute(): int
    {
        return $this->beds()->where('is_available', false)->count();
    }

    public function getAvailableBedsCountAttribute(): int
    {
        return $this->beds()->where('is_available', true)->count();
    }

    public function getOccupancyRateAttribute(): float
    {
        if ($this->capacity <= 0) return 0;
        return round(($this->getOccupiedBedsCountAttribute() / $this->capacity) * 100, 1);
    }
}
