<?php

namespace App\Observers;

use App\Models\LabRequest;
use App\Events\LabResultReady;

class LabRequestObserver
{
    /**
     * Handle the LabRequest "updated" event.
     */
    public function updated(LabRequest $labRequest): void
    {
        // If status changed to completed, fire event
        if ($labRequest->wasChanged('status') && $labRequest->status === 'completed') {
            event(new LabResultReady($labRequest));
        }
    }
}

