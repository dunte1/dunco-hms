<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Bed extends Model
{
    use HasFactory;

    protected $fillable = ['bed_number', 'ward', 'room', 'ward_name', 'bed_type_id', 'is_available'];

    public function bedType(): BelongsTo
    {
        return $this->belongsTo(BedType::class);
    }

    public function assignments(): HasMany
    {
        return $this->hasMany(BedAssignment::class);
    }

    public function bedAssignments(): HasMany
    {
        return $this->hasMany(BedAssignment::class);
    }

    public function currentAssignment(): HasOne
    {
        return $this->hasOne(BedAssignment::class)->latest();
    }

    public function sensor(): HasOne
    {
        return $this->hasOne(IotBedSensor::class);
    }
}