<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ScannedDocument extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'patient_id', 'mrd_file_id', 'document_type', 'file_path',
        'file_name', 'file_size', 'mime_type', 'uploaded_by', 'created_at',
    ];

    protected function createdAt(): \Illuminate\Database\Eloquent\Casts\Attribute
    {
        return \Illuminate\Database\Eloquent\Casts\Attribute::make(
            get: fn ($value) => $value,
        );
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function mrdFile(): BelongsTo
    {
        return $this->belongsTo(MrdFile::class, 'mrd_file_id');
    }

    public function uploadedByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }
}
