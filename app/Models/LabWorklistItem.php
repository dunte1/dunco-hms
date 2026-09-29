<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LabWorklistItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'worklist_id', 'lab_request_id', 'lab_request_item_id',
        'priority', 'sort_order', 'status',
    ];

    public function worklist(): BelongsTo
    {
        return $this->belongsTo(LabWorklist::class, 'worklist_id');
    }

    public function labRequest(): BelongsTo
    {
        return $this->belongsTo(LabRequest::class);
    }

    public function labRequestItem(): BelongsTo
    {
        return $this->belongsTo(LabRequestItem::class);
    }
}
