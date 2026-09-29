<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Publication extends Model
{
    use HasFactory;

    protected $fillable = [
        'research_project_id', 'title', 'authors', 'journal_name',
        'publication_date', 'doi', 'pubmed_id', 'publication_type', 'status',
    ];

    protected $casts = [
        'publication_date' => 'date',
    ];

    public function researchProject(): BelongsTo
    {
        return $this->belongsTo(ResearchProject::class);
    }
}
