<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ImagingReportVersion extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'radiology_request_id', 'version_number', 'findings',
        'impression', 'created_by', 'status', 'created_at',
    ];

    public function radiologyRequest(): BelongsTo
    {
        return $this->belongsTo(RadiologyRequest::class);
    }

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
