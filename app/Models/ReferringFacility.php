<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReferringFacility extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'facility_code', 'county', 'sub_county',
        'phone', 'email', 'contact_person', 'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}
