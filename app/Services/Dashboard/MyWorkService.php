<?php

namespace App\Services\Dashboard;

use App\Models\Appointment;
use App\Models\LabRequest;
use App\Models\Prescription;
use App\Models\QueueManagement;
use Illuminate\Support\Facades\Auth;

/**
 * Role-aware "My Work" queues for the dashboard.
 * Only returns data the authenticated user is authorized to see.
 */
class MyWorkService
{
    /**
     * @return array<string, array{label: string, count: int, route: ?string, visible: bool}>
     */
    public function forUser(): array
    {
        $user = Auth::user();
        if (!$user) {
            return [];
        }

        $work = [];

        $work['appointments'] = [
            'label' => "Today's appointments",
            'count' => 0,
            'route' => null,
            'visible' => $user->can('view appointments') || $user->can('manage appointments') || $user->can('create appointments'),
        ];
        if ($work['appointments']['visible']) {
            $cols = \Schema::getColumnListing('appointments');
            $q = Appointment::query();
            if (in_array('scheduled_at', $cols, true)) {
                $q->whereDate('scheduled_at', today());
            } elseif (in_array('appointment_date', $cols, true)) {
                $q->whereDate('appointment_date', today());
            }
            $work['appointments']['count'] = $q->count();
            $work['appointments']['route'] = \Route::has('hms.appointments.index')
                ? route('hms.appointments.index')
                : null;
        }

        $work['queue'] = [
            'label' => 'Patients in queue',
            'count' => 0,
            'route' => null,
            'visible' => $user->can('manage queue') || $user->can('view appointments'),
        ];
        if ($work['queue']['visible'] && \Schema::hasTable('queue_managements')) {
            $work['queue']['count'] = QueueManagement::whereDate('created_at', today())->count();
            $work['queue']['route'] = \Route::has('hms.queue.index') ? route('hms.queue.index') : null;
        }

        $work['pending_results'] = [
            'label' => 'Pending lab results',
            'count' => 0,
            'route' => null,
            'visible' => $user->can('view test results') || $user->can('add test requests') || $user->can('verify lab results'),
        ];
        if ($work['pending_results']['visible']) {
            $work['pending_results']['count'] = LabRequest::whereIn('status', ['pending', 'in_progress', 'processing', 'received'])
                ->count();
            $work['pending_results']['route'] = \Route::has('hms.laboratory.requests.index')
                ? route('hms.laboratory.requests.index')
                : null;
        }

        $work['prescriptions'] = [
            'label' => 'Pending prescriptions',
            'count' => 0,
            'route' => null,
            'visible' => $user->can('view prescriptions') || $user->can('dispense medicines') || $user->can('create prescriptions'),
        ];
        if ($work['prescriptions']['visible']) {
            $work['prescriptions']['count'] = Prescription::whereIn('status', ['pending', 'active', 'created'])
                ->count();
            $work['prescriptions']['route'] = \Route::has('hms.pharmacy.prescriptions.index')
                ? route('hms.pharmacy.prescriptions.index')
                : null;
        }

        $work['triage_queue'] = [
            'label' => 'Triage waiting',
            'count' => 0,
            'route' => null,
            'visible' => $user->can('view triage queue') || $user->can('manage triage records'),
        ];
        if ($work['triage_queue']['visible'] && \Schema::hasTable('triages')) {
            $work['triage_queue']['count'] = \App\Models\Triage::whereDate('created_at', today())->count();
        }

        $work['my_clinical'] = [
            'label' => 'My clinical focus',
            'count' => 0,
            'route' => null,
            'visible' => false,
        ];

        return array_filter($work, fn ($w) => $w['visible']);
    }
}
