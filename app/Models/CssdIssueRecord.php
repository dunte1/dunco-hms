<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CssdIssueRecord extends Model
{
    use HasFactory;

    protected $table = 'cssd_issue_records';

    protected $fillable = [
        'instrument_set_id', 'issued_to_user_id', 'theatre_schedule_id',
        'issued_at', 'expected_return_at', 'status', 'notes',
    ];

    protected $casts = [
        'issued_at' => 'datetime',
        'expected_return_at' => 'datetime',
    ];

    public function instrumentSet()
    {
        return $this->belongsTo(InstrumentSet::class);
    }

    public function issuedTo()
    {
        return $this->belongsTo(User::class, 'issued_to_user_id');
    }

    public function theatreSchedule()
    {
        return $this->belongsTo(OtSchedule::class, 'theatre_schedule_id');
    }

    public function returnRecord()
    {
        return $this->hasOne(CssdReturnRecord::class, 'issue_record_id');
    }
}
