<?php

namespace App\Providers;

use App\Models\Patient;
use App\Models\Appointment;
use App\Models\LabRequest;
use App\Models\IpdAdmission;
use App\Observers\PatientObserver;
use App\Observers\AppointmentObserver;
use App\Observers\LabRequestObserver;
use App\Observers\IpdAdmissionObserver;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register Model Observers
        Patient::observe(PatientObserver::class);
        Appointment::observe(AppointmentObserver::class);
        LabRequest::observe(LabRequestObserver::class);
        IpdAdmission::observe(IpdAdmissionObserver::class);

        // Register Event Listeners
        Event::listen(
            \App\Events\PatientRegistered::class,
            \App\Listeners\SendPatientWelcomeNotification::class
        );

        Event::listen(
            \App\Events\AppointmentBooked::class,
            \App\Listeners\NotifyDoctorOfAppointment::class
        );

        Event::listen(
            \App\Events\LabResultReady::class,
            \App\Listeners\NotifyLabResultsReady::class
        );

        Event::listen(
            \App\Events\DischargeCompleted::class,
            \App\Listeners\HandleDischargeCompletion::class
        );
    }
}
