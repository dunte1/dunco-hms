<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MrdFileMovement extends Model
{
    use HasFactory;

    protected $fillable = [
        'mrd_file_id', 'action', 'performed_by', 'from_location',
        'to_location', 'issued_to', 'issued_at', 'returned_at', 'notes',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'returned_at' => 'datetime',
    ];

    public function mrdFile(): BelongsTo
    {
        return $this->belongsTo(MrdFile::class, 'mrd_file_id');
    }

    public function performedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
