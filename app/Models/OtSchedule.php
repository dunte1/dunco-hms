<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class OtSchedule extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'schedule_number', 'patient_id', 'ot_room_id', 'surgeon_id',
        'anesthetist_id', 'assistant_doctor_id', 'nurse_id',
        'procedure_name', 'procedure_description', 'procedure_type',
        'anesthesia_type', 'scheduled_date', 'scheduled_start', 'scheduled_end',
        'actual_start', 'actual_end', 'status', 'pre_op_notes', 'intra_op_notes',
        'post_op_notes', 'complications', 'risk_level', 'consent_signed',
        'consent_form_path', 'estimated_cost', 'actual_cost', 'created_by',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'scheduled_start' => 'datetime:H:i',
        'scheduled_end' => 'datetime:H:i',
        'actual_start' => 'datetime',
        'actual_end' => 'datetime',
        'estimated_cost' => 'decimal:2',
        'actual_cost' => 'decimal:2',
        'consent_signed' => 'boolean',
    ];

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function otRoom(): BelongsTo
    {
        return $this->belongsTo(OtRoom::class);
    }

    public function surgeon(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'surgeon_id');
    }

    public function anesthetist(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'anesthetist_id');
    }

    public function assistantDoctor(): BelongsTo
    {
        return $this->belongsTo(Doctor::class, 'assistant_doctor_id');
    }

    public function nurse(): BelongsTo
    {
        return $this->belongsTo(Nurse::class);
    }

    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function timeLogs(): HasMany
    {
        return $this->hasMany(OtTimeLog::class);
    }

    public function instrumentTrays(): HasMany
    {
        return $this->hasMany(OtInstrumentTray::class, 'last_used_schedule_id');
    }

    public function getStatusBadgeAttribute(): string
    {
        return match($this->status) {
            'scheduled' => 'bg-blue-100 text-blue-800',
            'in_preparation' => 'bg-yellow-100 text-yellow-800',
            'in_progress' => 'bg-green-100 text-green-800',
            'completed' => 'bg-gray-100 text-gray-800',
            'cancelled' => 'bg-red-100 text-red-800',
            'postponed' => 'bg-orange-100 text-orange-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getRiskBadgeAttribute(): string
    {
        return match($this->risk_level) {
            'low' => 'bg-green-100 text-green-800',
            'medium' => 'bg-yellow-100 text-yellow-800',
            'high' => 'bg-orange-100 text-orange-800',
            'critical' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public static function generateScheduleNumber(): string
    {
        $prefix = 'OT-' . now()->format('Ym');
        $last = self::where('schedule_number', 'like', $prefix . '%')->latest('id')->value('schedule_number');
        if ($last) {
            $sequence = intval(substr($last, -4)) + 1;
        } else {
            $sequence = 1;
        }
        return $prefix . str_pad($sequence, 4, '0', STR_PAD_LEFT);
    }
}
