<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SoftwareLicence extends Model
{
    use HasFactory;

    protected $fillable = [
        'software_name', 'licence_key', 'licence_type', 'max_seats', 'current_seats',
        'purchase_date', 'expiry_date', 'cost', 'vendor', 'status',
    ];

    protected $casts = [
        'purchase_date' => 'date',
        'expiry_date' => 'date',
        'cost' => 'decimal:2',
    ];
}
