<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class BloodUnit extends Model
{
    use HasFactory;

    protected $fillable = [
        'blood_inventory_id', 'donation_id', 'unit_number', 'blood_group_id',
        'volume_ml', 'expiry_date', 'status', 'reserved_for_patient_id', 'reserved_at',
    ];

    protected $casts = [
        'expiry_date' => 'date',
        'reserved_at' => 'datetime',
    ];

    public function bloodInventory(): BelongsTo
    {
        return $this->belongsTo(BloodInventory::class);
    }

    public function donation(): BelongsTo
    {
        return $this->belongsTo(BloodDonation::class);
    }

    public function bloodGroup(): BelongsTo
    {
        return $this->belongsTo(BloodGroup::class);
    }

    public function reservedForPatient(): BelongsTo
    {
        return $this->belongsTo(Patient::class, 'reserved_for_patient_id');
    }

    public function bloodIssue(): HasOne
    {
        return $this->hasOne(BloodIssue::class);
    }

    public function transfusion(): HasOne
    {
        return $this->hasOne(Transfusion::class);
    }

    public static function generateUnitNumber(): string
    {
        return 'BU-' . date('Y') . '-' . str_pad(self::count() + 1, 6, '0', STR_PAD_LEFT);
    }
}
