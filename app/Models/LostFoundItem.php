<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LostFoundItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_description', 'location_found', 'date_found', 'found_by',
        'claimed_by_name', 'claimed_by_id_number', 'claimed_at', 'status', 'storage_location',
    ];

    protected $casts = [
        'date_found' => 'date',
        'claimed_at' => 'datetime',
    ];

    public function foundBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'found_by');
    }
}
