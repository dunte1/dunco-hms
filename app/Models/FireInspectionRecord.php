<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FireInspectionRecord extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'inspection_date', 'inspector_name', 'equipment_checked', 'equipment_passed',
        'deficiencies_found', 'corrective_actions', 'status', 'next_inspection_date',
    ];

    protected $casts = [
        'inspection_date' => 'date',
        'next_inspection_date' => 'date',
    ];
}
