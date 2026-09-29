<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MessageOptOut extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone', 'email', 'channel', 'reason', 'opted_out_at',
    ];

    protected $casts = [
        'opted_out_at' => 'datetime',
    ];
}
