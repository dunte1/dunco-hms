<?php

namespace App\Listeners;

use App\Events\LabResultReady;
use App\Notifications\LabResultReadyNotification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyLabResultsReady implements ShouldQueue
{
    use InteractsWithQueue;

    /**
     * Handle the event.
     */
    public function handle(LabResultReady $event): void
    {
        $labRequest = $event->labRequest;

        // Notify doctor via email if available
        if ($labRequest->doctor && $labRequest->doctor->email) {
            try {
                \Mail::send([], [], function ($message) use ($labRequest) {
                    $message->to($labRequest->doctor->email)
                        ->subject('Lab Results Ready - ' . $labRequest->request_number)
                        ->html('Lab results are ready for request ' . $labRequest->request_number . ' (Patient: ' . $labRequest->patient->full_name . ')');
                });
            } catch (\Exception $e) {
                \Log::error('Failed to send lab results notification to doctor: ' . $e->getMessage());
            }
        }

        // Notify patient via email if available
        if ($labRequest->patient && $labRequest->patient->email) {
            try {
                $labRequest->patient->notify(new LabResultReadyNotification($labRequest));
            } catch (\Exception $e) {
                \Log::error('Failed to send lab results notification to patient: ' . $e->getMessage());
            }
        }
    }
}

