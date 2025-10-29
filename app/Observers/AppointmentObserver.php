<?php

namespace App\Observers;

use App\Models\Appointment;
use App\Events\AppointmentBooked;

class AppointmentObserver
{
    /**
     * Handle the Appointment "created" event.
     */
    public function created(Appointment $appointment): void
    {
        // Fire AppointmentBooked event when status is confirmed or scheduled
        if (in_array($appointment->status, ['confirmed', 'scheduled'])) {
            event(new AppointmentBooked($appointment));
        }
    }

    /**
     * Handle the Appointment "updated" event.
     */
    public function updated(Appointment $appointment): void
    {
        // If status changed to confirmed, fire event
        if ($appointment->wasChanged('status') && $appointment->status === 'confirmed') {
            event(new AppointmentBooked($appointment));
        }
    }
}

