<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CorrectiveAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'source_type', 'source_id', 'action_description', 'responsible_person',
        'due_date', 'completion_date', 'status', 'evidence_path',
    ];

    protected $casts = [
        'due_date' => 'date',
        'completion_date' => 'date',
    ];

    public function responsible(): BelongsTo
    {
        return $this->belongsTo(User::class, 'responsible_person');
    }

    public function complete(?string $evidencePath = null): void
    {
        $this->update([
            'status' => 'completed',
            'completion_date' => now()->toDateString(),
            'evidence_path' => $evidencePath,
        ]);
    }
}
