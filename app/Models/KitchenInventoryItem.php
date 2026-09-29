<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KitchenInventoryItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_name', 'category', 'quantity', 'unit',
        'reorder_level', 'expiry_date', 'last_restocked_at',
    ];

    protected $casts = [
        'quantity' => 'decimal:2',
        'reorder_level' => 'decimal:2',
        'expiry_date' => 'date',
        'last_restocked_at' => 'datetime',
    ];
}
