<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class NursingProcedure extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $fillable = [
        'patient_id', 'ipd_admission_id', 'procedure_name', 'description',
        'body_site', 'outcome', 'complications', 'performed_by', 'performed_at',
    ];

    protected $casts = [
        'performed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function (NursingProcedure $model) {
            $model->created_at = $model->created_at ?? now();
            $model->updated_at = $model->updated_at ?? now();
        });

        static::updating(function (NursingProcedure $model) {
            $model->updated_at = now();
        });
    }

    public function patient(): BelongsTo
    {
        return $this->belongsTo(Patient::class);
    }

    public function ipdAdmission(): BelongsTo
    {
        return $this->belongsTo(IpdAdmission::class, 'ipd_admission_id');
    }

    public function performer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
