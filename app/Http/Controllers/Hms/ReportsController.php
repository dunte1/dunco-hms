<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\Appointment;
use App\Models\OpdVisit;
use App\Models\IpdAdmission;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PatientsExport;

class ReportsController extends Controller
{
    public function index(): View
    {
        return view('hms.reports.index');
    }

    public function patientReports(Request $request): View
    {
        $query = Patient::query();
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $patients = $query->with(['appointments', 'opdVisits', 'ipdAdmissions'])
            ->latest()
            ->paginate(20);
            
        $totalPatients = Patient::count();
        $newPatients = Patient::whereDate('created_at', '>=', now()->subDays(30))->count();
        $activePatients = Patient::whereHas('appointments', function($q) {
            $q->whereDate('appointment_date', '>=', now()->subDays(30));
        })->count();
        
        return view('hms.reports.patients', compact('patients', 'totalPatients', 'newPatients', 'activePatients'));
    }

    public function revenueReports(Request $request): View
    {
        $query = Invoice::query();
        
        if ($request->filled('date_from')) {
            $query->whereDate('invoice_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('invoice_date', '<=', $request->date_to);
        }
        
        $invoices = $query->with('patient')->latest('invoice_date')->paginate(20);
        
        // Revenue statistics
        $totalRevenue = $query->sum('total_amount');
        $paidRevenue = $query->where('status', 'paid')->sum('total_amount');
        $pendingRevenue = $query->where('status', 'pending')->sum('total_amount');
        $partialRevenue = $query->where('status', 'partial')->sum('total_amount');
        
        // Daily revenue for chart
        $dailyRevenue = Invoice::selectRaw('DATE(invoice_date) as date, SUM(total_amount) as revenue')
            ->whereDate('invoice_date', '>=', now()->subDays(30))
            ->groupBy('date')
            ->orderBy('date')
            ->get();
        
        return view('hms.reports.revenue', compact('invoices', 'totalRevenue', 'paidRevenue', 'pendingRevenue', 'partialRevenue', 'dailyRevenue'));
    }

    public function appointmentReports(Request $request): View
    {
        $query = Appointment::with(['patient', 'doctor']);
        
        if ($request->filled('date_from')) {
            $query->whereDate('appointment_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('appointment_date', '<=', $request->date_to);
        }
        
        if ($request->filled('doctor_id')) {
            $query->where('doctor_id', $request->doctor_id);
        }
        
        $appointments = $query->latest('appointment_date')->paginate(20);
        
        // Appointment statistics
        $totalAppointments = Appointment::count();
        $todayAppointments = Appointment::whereDate('appointment_date', today())->count();
        $completedAppointments = Appointment::where('status', 'completed')->count();
        $cancelledAppointments = Appointment::where('status', 'cancelled')->count();
        
        // Doctor performance
        $doctorPerformance = Appointment::selectRaw('doctor_id, COUNT(*) as total_appointments, COUNT(CASE WHEN status = "completed" THEN 1 END) as completed_appointments')
            ->with('doctor')
            ->groupBy('doctor_id')
            ->orderByDesc('total_appointments')
            ->limit(10)
            ->get();
        
        $doctors = \App\Models\Doctor::orderBy('first_name')->get(['id', 'first_name', 'last_name']);
        
        return view('hms.reports.appointments', compact('appointments', 'totalAppointments', 'todayAppointments', 'completedAppointments', 'cancelledAppointments', 'doctorPerformance', 'doctors'));
    }

    public function financialReports(Request $request): View
    {
        $query = Payment::with(['patient', 'invoice']);
        
        if ($request->filled('date_from')) {
            $query->whereDate('payment_date', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('payment_date', '<=', $request->date_to);
        }
        
        $payments = $query->latest('payment_date')->paginate(20);
        
        // Financial statistics
        $totalPayments = Payment::sum('amount');
        $cashPayments = Payment::where('payment_method', 'cash')->sum('amount');
        $cardPayments = Payment::where('payment_method', 'card')->sum('amount');
        $bankTransferPayments = Payment::where('payment_method', 'bank_transfer')->sum('amount');
        
        // Monthly revenue trend
        $monthlyRevenue = Payment::selectRaw('YEAR(payment_date) as year, MONTH(payment_date) as month, SUM(amount) as revenue')
            ->whereDate('payment_date', '>=', now()->subMonths(12))
            ->groupBy('year', 'month')
            ->orderBy('year')
            ->orderBy('month')
            ->get();
        
        return view('hms.reports.financial', compact('payments', 'totalPayments', 'cashPayments', 'cardPayments', 'bankTransferPayments', 'monthlyRevenue'));
    }

    public function exportPatients(Request $request)
    {
        $query = Patient::query();
        
        if ($request->filled('date_from')) {
            $query->whereDate('created_at', '>=', $request->date_from);
        }
        
        if ($request->filled('date_to')) {
            $query->whereDate('created_at', '<=', $request->date_to);
        }
        
        $patients = $query->get();
        
        $filename = 'patients_' . now()->format('Y-m-d_H-i-s') . '.csv';
        
        $headers = [
            'Content-Type' => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $filename . '"',
        ];
        
        $callback = function() use ($patients) {
            $file = fopen('php://output', 'w');
            
            // CSV headers
            fputcsv($file, ['ID', 'Name', 'Email', 'Phone', 'Date of Birth', 'Gender', 'Address', 'Created At']);
            
            // CSV data
            foreach ($patients as $patient) {
                fputcsv($file, [
                    $patient->id,
                    $patient->first_name . ' ' . $patient->last_name,
                    $patient->email,
                    $patient->phone,
                    $patient->date_of_birth?->format('Y-m-d'),
                    $patient->gender,
                    $patient->address,
                    $patient->created_at->format('Y-m-d H:i:s'),
                ]);
            }
            
            fclose($file);
        };
        
        return response()->stream($callback, 200, $headers);
    }

    public function exportPatientsExcel(Request $request)
    {
        try {
            return Excel::download(new PatientsExport($request), 'patients_' . now()->format('Y-m-d_H-i-s') . '.xlsx');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Excel export failed: ' . $e->getMessage()]);
        }
    }
}