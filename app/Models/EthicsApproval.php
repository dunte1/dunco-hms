<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class EthicsApproval extends Model
{
    use HasFactory;

    protected $fillable = [
        'research_project_id', 'submission_date', 'approval_number',
        'approval_date', 'expiry_date', 'status', 'committee_name',
        'decision_notes', 'reviewed_by',
    ];

    protected $casts = [
        'submission_date' => 'date',
        'approval_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function researchProject(): BelongsTo
    {
        return $this->belongsTo(ResearchProject::class);
    }

    public function reviewer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }
}
