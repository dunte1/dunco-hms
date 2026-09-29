<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ApiClient extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'name', 'client_id', 'client_secret_hash', 'scopes', 'is_active', 'last_used_at',
    ];

    protected $casts = [
        'scopes' => 'array',
        'is_active' => 'boolean',
        'last_used_at' => 'datetime',
    ];

    protected $hidden = [
        'client_secret_hash',
    ];

    public static function generateCredentials(): array
    {
        return [
            'client_id' => Str::random(40),
            'client_secret' => Str::random(60),
        ];
    }
}
