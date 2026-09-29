<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ItAsset extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'asset_number', 'name', 'type', 'manufacturer', 'model', 'serial_number',
        'purchase_date', 'warranty_expiry', 'location', 'assigned_to_user_id', 'status',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'warranty_expiry' => 'date',
    ];

    public function assignedTo(): BelongsTo
    {
        return $this->belongsTo(User::class, 'assigned_to_user_id');
    }
}
