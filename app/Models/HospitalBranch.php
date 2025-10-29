<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HospitalBranch extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_code', 'name', 'description', 'address', 'phone', 'email',
        'manager_name', 'manager_phone', 'manager_email', 'is_main_branch',
        'is_active', 'notes'
    ];

    protected $casts = [
        'is_main_branch' => 'boolean',
        'is_active' => 'boolean',
    ];
}
