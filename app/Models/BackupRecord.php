<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BackupRecord extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'backup_type', 'backup_location', 'file_size_mb', 'started_at', 'completed_at',
        'status', 'verified', 'verified_at', 'notes', 'created_at',
    ];

    protected $casts = [
        'started_at' => 'datetime',
        'completed_at' => 'datetime',
        'verified_at' => 'datetime',
        'verified' => 'boolean',
        'file_size_mb' => 'decimal:2',
    ];
}
