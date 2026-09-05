<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class OtRoom extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'floor', 'type', 'equipment_notes', 'status', 'capacity'];

    public function schedules(): HasMany
    {
        return $this->hasMany(OtSchedule::class);
    }

    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }
}
