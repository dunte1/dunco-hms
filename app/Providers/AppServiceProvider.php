<?php

namespace App\Providers;

use App\Models\Patient;
use App\Models\Appointment;
use App\Models\LabRequest;
use App\Models\IpdAdmission;
use App\Models\Invoice;
use App\Models\RadiologyRequest;
use App\Observers\PatientObserver;
use App\Observers\AppointmentObserver;
use App\Observers\LabRequestObserver;
use App\Observers\IpdAdmissionObserver;
use App\Policies\PatientPolicy;
use App\Policies\LabRequestPolicy;
use App\Policies\InvoicePolicy;
use App\Policies\RadiologyRequestPolicy;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Gate;

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
        Gate::policy(Patient::class, PatientPolicy::class);
        Gate::policy(LabRequest::class, LabRequestPolicy::class);
        Gate::policy(Invoice::class, InvoicePolicy::class);
        Gate::policy(RadiologyRequest::class, RadiologyRequestPolicy::class);

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
