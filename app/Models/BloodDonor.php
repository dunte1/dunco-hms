<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class BloodDonor extends Model
{
    use HasFactory;

    protected $fillable = [
        'donor_id', 'first_name', 'last_name', 'email', 'phone', 'date_of_birth',
        'gender', 'blood_group_id', 'address', 'last_donation_date',
        'is_eligible', 'medical_history'
    ];

    protected $casts = [
        'date_of_birth' => 'date',
        'last_donation_date' => 'date',
        'is_eligible' => 'boolean',
    ];

    public function bloodGroup(): BelongsTo
    {
        return $this->belongsTo(BloodGroup::class);
    }

    public function donations(): HasMany
    {
        return $this->hasMany(BloodInventory::class);
    }

    public function getFullNameAttribute()
    {
        return $this->first_name . ' ' . $this->last_name;
    }
}
