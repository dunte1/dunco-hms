<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FireEquipmentRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'equipment_type', 'location', 'serial_number', 'last_inspection_date',
        'next_inspection_date', 'status', 'notes',
    ];

    protected $casts = [
        'last_inspection_date' => 'date',
        'next_inspection_date' => 'date',
    ];
}
