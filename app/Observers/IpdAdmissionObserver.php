<?php

namespace App\Observers;

use App\Models\IpdAdmission;
use App\Events\DischargeCompleted;
use App\Models\Bed;

class IpdAdmissionObserver
{
    /**
     * Handle the IpdAdmission "updated" event.
     */
    public function updated(IpdAdmission $admission): void
    {
        // If status changed to discharged, fire event
        if ($admission->wasChanged('status') && $admission->status === 'discharged') {
            event(new DischargeCompleted($admission));
        }
    }

    /**
     * Handle the IpdAdmission "created" event.
     */
    public function created(IpdAdmission $admission): void
    {
        // Mark bed as occupied
        if ($admission->bed_id) {
            Bed::where('id', $admission->bed_id)->update([
                'status' => 'occupied',
                'patient_id' => $admission->patient_id
            ]);
        }
    }
}

