<?php

namespace App\Listeners;

use App\Events\PatientRegistered;
use App\Services\SmsService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Mail;

class SendPatientWelcomeNotification implements ShouldQueue
{
    use InteractsWithQueue;

    protected $smsService;

    /**
     * Create the event listener.
     */
    public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Handle the event.
     */
    public function handle(PatientRegistered $event): void
    {
        $patient = $event->patient;

        // Send welcome email
        if ($patient->email) {
            try {
                Mail::send('emails.patient-welcome', [
                    'patient' => $patient,
                    'patientNo' => $patient->patient_no ?? 'P-' . str_pad($patient->id, 6, '0', STR_PAD_LEFT)
                ], function ($message) use ($patient) {
                    $message->to($patient->email)
                        ->subject('Welcome to ' . config('app.name'));
                });
            } catch (\Exception $e) {
                \Log::error('Failed to send welcome email: ' . $e->getMessage());
            }
        }

        // Send welcome SMS
        if ($patient->phone && $this->smsService->isConfigured()) {
            $message = "Hello {$patient->first_name}, welcome to " . config('app.name') . 
                      ". Your Patient ID is: " . ($patient->patient_no ?? 'P-' . str_pad($patient->id, 6, '0', STR_PAD_LEFT));
            $this->smsService->send($patient->phone, $message);
        }
    }
}

