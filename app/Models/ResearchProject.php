<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class ResearchProject extends Model
{
    use HasFactory;

    protected $fillable = [
        'title', 'principal_investigator_id', 'status', 'start_date',
        'end_date', 'budget', 'objectives', 'methodology', 'findings',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'budget' => 'decimal:2',
    ];

    public function principalInvestigator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'principal_investigator_id');
    }

    public function ethicsApprovals(): HasMany
    {
        return $this->hasMany(EthicsApproval::class);
    }

    public function publications(): HasMany
    {
        return $this->hasMany(Publication::class);
    }
}
