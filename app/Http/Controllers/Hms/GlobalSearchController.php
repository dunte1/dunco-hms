<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\LabRequest;
use App\Models\Prescription;

class GlobalSearchController extends Controller
{
    /**
     * Global search page across patients, appointments, staff, billing, lab, and prescriptions.
     */
    public function search(Request $request): View
    {
        $query = trim((string) $request->input('q', ''));
        $results = [];
        $hasQuery = mb_strlen($query) >= 2;

        if ($hasQuery) {
            $patients = Patient::query()
                ->where(function ($q) use ($query) {
                    $q->where('first_name', 'like', "%{$query}%")
                        ->orWhere('last_name', 'like', "%{$query}%")
                        ->orWhere('patient_no', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%")
                        ->orWhere('phone', 'like', "%{$query}%");
                })
                ->limit(10)
                ->get();

            foreach ($patients as $patient) {
                $results[] = [
                    'type' => 'Patient',
                    'icon' => 'fa-user-injured',
                    'title' => $patient->full_name,
                    'description' => 'Patient ID: ' . $patient->patient_no,
                    'url' => route('hms.patients.show', $patient),
                ];
            }

            $appointments = Appointment::with(['patient', 'doctor'])
                ->where(function ($q) use ($query) {
                    $q->whereHas('patient', function ($qp) use ($query) {
                        $qp->where('first_name', 'like', "%{$query}%")
                            ->orWhere('last_name', 'like', "%{$query}%");
                    })->orWhereHas('doctor', function ($qd) use ($query) {
                        $qd->where('first_name', 'like', "%{$query}%")
                            ->orWhere('last_name', 'like', "%{$query}%");
                    });
                })
                ->limit(10)
                ->get();

            foreach ($appointments as $appointment) {
                $patientName = $appointment->patient->full_name ?? 'Unknown patient';
                $doctorName = $appointment->doctor->full_name ?? 'Unassigned';
                $scheduledAt = $appointment->scheduled_at?->format('M d, Y h:i A') ?? '—';

                $results[] = [
                    'type' => 'Appointment',
                    'icon' => 'fa-calendar-check',
                    'title' => $patientName . ' — ' . $doctorName,
                    'description' => $scheduledAt,
                    'url' => route('hms.appointments.show', $appointment),
                ];
            }

            $employees = Employee::with('department')
                ->where(function ($q) use ($query) {
                    $q->where('first_name', 'like', "%{$query}%")
                        ->orWhere('last_name', 'like', "%{$query}%")
                        ->orWhere('employee_id', 'like', "%{$query}%")
                        ->orWhere('email', 'like', "%{$query}%");
                })
                ->limit(10)
                ->get();

            foreach ($employees as $employee) {
                $employeeName = trim(($employee->first_name ?? '') . ' ' . ($employee->last_name ?? '')) ?: $employee->employee_id;
                $results[] = [
                    'type' => 'Employee',
                    'icon' => 'fa-user-tie',
                    'title' => $employeeName,
                    'description' => trim(($employee->position ?? '') . ' — ' . ($employee->department->name ?? 'N/A'), ' —'),
                    'url' => route('hms.hr.employees.show', $employee),
                ];
            }

            $invoices = Invoice::with('patient')
                ->where(function ($q) use ($query) {
                    $q->where('invoice_number', 'like', "%{$query}%")
                        ->orWhereHas('patient', function ($qp) use ($query) {
                            $qp->where('first_name', 'like', "%{$query}%")
                                ->orWhere('last_name', 'like', "%{$query}%");
                        });
                })
                ->limit(10)
                ->get();

            foreach ($invoices as $invoice) {
                $results[] = [
                    'type' => 'Invoice',
                    'icon' => 'fa-file-invoice-dollar',
                    'title' => $invoice->invoice_number,
                    'description' => 'Amount: KES ' . number_format((float) $invoice->total_amount, 2),
                    'url' => route('hms.billing.invoices.show', $invoice),
                ];
            }

            $labRequests = LabRequest::with(['patient', 'doctor'])
                ->where(function ($q) use ($query) {
                    $q->where('request_number', 'like', "%{$query}%")
                        ->orWhere('status', 'like', "%{$query}%")
                        ->orWhereHas('patient', function ($qp) use ($query) {
                            $qp->where('first_name', 'like', "%{$query}%")
                                ->orWhere('last_name', 'like', "%{$query}%");
                        });
                })
                ->limit(10)
                ->get();

            foreach ($labRequests as $labRequest) {
                $results[] = [
                    'type' => 'Lab Request',
                    'icon' => 'fa-vials',
                    'title' => $labRequest->request_number,
                    'description' => ($labRequest->patient->full_name ?? 'Unknown patient') . ' — ' . ($labRequest->status ?: 'pending'),
                    'url' => route('hms.laboratory.requests.show', $labRequest),
                ];
            }

            $prescriptions = Prescription::with(['patient', 'doctor'])
                ->where(function ($q) use ($query) {
                    $q->where('diagnosis', 'like', "%{$query}%")
                        ->orWhere('symptoms', 'like', "%{$query}%")
                        ->orWhere('status', 'like', "%{$query}%")
                        ->orWhereHas('patient', function ($qp) use ($query) {
                            $qp->where('first_name', 'like', "%{$query}%")
                                ->orWhere('last_name', 'like', "%{$query}%");
                        });
                })
                ->limit(10)
                ->get();

            foreach ($prescriptions as $prescription) {
                $results[] = [
                    'type' => 'Prescription',
                    'icon' => 'fa-prescription-bottle-medical',
                    'title' => $prescription->patient->full_name ?? 'Unknown patient',
                    'description' => trim(($prescription->diagnosis ?? 'No diagnosis') . ' — ' . ($prescription->status ?: 'pending')),
                    'url' => route('hms.pharmacy.prescriptions.show', $prescription),
                ];
            }
        }

        return view('hms.global-search.index', [
            'query' => $query,
            'results' => $results,
            'hasQuery' => $hasQuery,
        ]);
    }
}
