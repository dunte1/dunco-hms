<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CssdCycleRecord extends Model
{
    use HasFactory;

    protected $table = 'cssd_cycle_records';

    protected $fillable = [
        'batch_id', 'instrument_set_id', 'cycle_type', 'start_time',
        'end_time', 'status', 'notes', 'performed_by',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function batch()
    {
        return $this->belongsTo(CssdBatch::class);
    }

    public function instrumentSet()
    {
        return $this->belongsTo(InstrumentSet::class);
    }

    public function performedBy()
    {
        return $this->belongsTo(User::class, 'performed_by');
    }
}
