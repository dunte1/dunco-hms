<?php

namespace App\Observers;

use App\Models\Patient;
use App\Events\PatientRegistered;
use Illuminate\Support\Str;

class PatientObserver
{
    /**
     * Handle the Patient "created" event.
     */
    public function created(Patient $patient): void
    {
        // Generate patient number if not set
        if (!$patient->patient_no) {
            $patient->update([
                'patient_no' => 'P-' . date('Y') . '-' . str_pad($patient->id, 6, '0', STR_PAD_LEFT)
            ]);
        }

        // Fire PatientRegistered event
        event(new PatientRegistered($patient));
    }

    /**
     * Handle the Patient "updated" event.
     */
    public function updated(Patient $patient): void
    {
        // Log patient updates
        \Log::info('Patient updated', [
            'patient_id' => $patient->id,
            'patient_no' => $patient->patient_no,
            'changes' => $patient->getChanges()
        ]);
    }

    /**
     * Handle the Patient "deleted" event.
     */
    public function deleted(Patient $patient): void
    {
        \Log::info('Patient deleted', [
            'patient_id' => $patient->id,
            'patient_no' => $patient->patient_no
        ]);
    }
}

