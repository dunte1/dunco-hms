<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CssdReturnRecord extends Model
{
    use HasFactory;

    protected $table = 'cssd_return_records';

    protected $fillable = [
        'issue_record_id', 'returned_at', 'condition',
        'missing_items', 'inspected_by', 'notes',
    ];

    protected $casts = [
        'returned_at' => 'datetime',
    ];

    public function issueRecord()
    {
        return $this->belongsTo(CssdIssueRecord::class);
    }

    public function inspectedBy()
    {
        return $this->belongsTo(User::class, 'inspected_by');
    }
}
