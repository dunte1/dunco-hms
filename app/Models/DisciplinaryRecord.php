<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DisciplinaryRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'employee_id', 'incident_date', 'description', 'category',
        'severity', 'action_taken', 'action_by', 'status',
        'resolution_date', 'resolution_notes',
    ];

    protected $casts = [
        'incident_date' => 'date',
        'resolution_date' => 'date',
    ];

    public function employee(): BelongsTo
    {
        return $this->belongsTo(Employee::class);
    }

    public function actionByUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'action_by');
    }
}
