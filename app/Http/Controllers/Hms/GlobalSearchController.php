<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Employee;
use App\Models\Invoice;
use App\Models\LabRequest;
use App\Models\Prescription;

class GlobalSearchController extends Controller
{
    /**
     * Perform global search across all modules
     */
    public function search(Request $request)
    {
        $query = $request->input('q', '');
        $results = [];
        
        if (strlen($query) < 2) {
            return response()->json(['results' => []]);
        }
        
        // Search patients
        $patients = Patient::where('first_name', 'like', "%{$query}%")
            ->orWhere('last_name', 'like', "%{$query}%")
            ->orWhere('patient_no', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->orWhere('phone', 'like', "%{$query}%")
            ->limit(5)
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
        
        // Search appointments
        $appointments = Appointment::with(['patient', 'doctor'])
            ->whereHas('patient', function($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%");
            })
            ->orWhereHas('doctor', function($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%");
            })
            ->limit(5)
            ->get();
        
        foreach ($appointments as $appointment) {
            $results[] = [
                'type' => 'Appointment',
                'icon' => 'fa-calendar-check',
                'title' => $appointment->patient->full_name . ' - ' . $appointment->doctor->full_name,
                'description' => $appointment->scheduled_at->format('M d, Y h:i A'),
                'url' => route('hms.appointments.show', $appointment),
            ];
        }
        
        // Search employees
        $employees = Employee::where('first_name', 'like', "%{$query}%")
            ->orWhere('last_name', 'like', "%{$query}%")
            ->orWhere('employee_id', 'like', "%{$query}%")
            ->orWhere('email', 'like', "%{$query}%")
            ->limit(5)
            ->get();
        
        foreach ($employees as $employee) {
            $results[] = [
                'type' => 'Employee',
                'icon' => 'fa-user-tie',
                'title' => $employee->full_name,
                'description' => $employee->position . ' - ' . ($employee->department->name ?? 'N/A'),
                'url' => route('hms.hr.employees.show', $employee),
            ];
        }
        
        // Search invoices
        $invoices = Invoice::with('patient')
            ->where('invoice_number', 'like', "%{$query}%")
            ->orWhereHas('patient', function($q) use ($query) {
                $q->where('first_name', 'like', "%{$query}%")
                  ->orWhere('last_name', 'like', "%{$query}%");
            })
            ->limit(5)
            ->get();
        
        foreach ($invoices as $invoice) {
            $results[] = [
                'type' => 'Invoice',
                'icon' => 'fa-file-invoice-dollar',
                'title' => $invoice->invoice_number,
                'description' => 'Amount: ' . number_format($invoice->total_amount, 2),
                'url' => route('hms.billing.invoices.show', $invoice),
            ];
        }
        
        return response()->json(['results' => $results]);
    }
}

