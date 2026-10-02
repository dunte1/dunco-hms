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
                \Mail::send('emails.lab-results-ready', [
                    'patient' => $labRequest->patient,
                    'labRequest' => $labRequest,
                ], function ($message) use ($labRequest) {
                    $message->to($labRequest->doctor->email)
                        ->subject('Lab Results Ready - ' . $labRequest->request_number)
                        ->from(\App\Models\SystemSetting::get('hospital_email', config('mail.from.address')), \App\Models\SystemSetting::get('hospital_name', config('app.name')));
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
