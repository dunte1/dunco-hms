<?php

namespace App\Models;

use App\Traits\Auditable;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CssdInstrument extends Model
{
    use HasFactory;
    use Auditable;

    protected $fillable = ['name', 'category', 'description', 'quantity', 'status', 'location', 'last_sterilized_at'];

    protected $casts = [
        'last_sterilized_at' => 'datetime',
    ];
}

