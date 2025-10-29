<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BloodInventory extends Model
{
    use HasFactory;

    protected $fillable = [
        'blood_group_id', 'donor_id', 'bag_number', 'collection_date',
        'expiry_date', 'status', 'notes'
    ];

    protected $casts = [
        'collection_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function bloodGroup(): BelongsTo
    {
        return $this->belongsTo(BloodGroup::class);
    }

    public function donor(): BelongsTo
    {
        return $this->belongsTo(BloodDonor::class);
    }
}
