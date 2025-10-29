<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\LabRequest;
use App\Models\Prescription;
use App\Models\BloodDonor;
use App\Models\Bed;
use App\Models\BedAssignment;
use App\Models\PatientDiagnosis;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Expense;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnalyticsReportsController extends Controller
{
    /**
     * Billing Report
     */
    public function billingReport(Request $request): View
    {
        $dateFrom = $request->get('date_from', now()->subMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $invoices = Invoice::with('patient')
            ->whereBetween('invoice_date', [$dateFrom, $dateTo])
            ->get();

        $totalRevenue = $invoices->sum('total_amount');
        $paidRevenue = $invoices->where('status', 'paid')->sum('total_amount');
        $pendingRevenue = $invoices->where('status', 'pending')->sum('total_amount');
        $partialRevenue = $invoices->where('status', 'partial')->sum('total_amount');

        return view('hms.reports.billing', compact(
            'invoices', 
            'totalRevenue', 
            'paidRevenue', 
            'pendingRevenue', 
            'partialRevenue',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Lab Report
     */
    public function labReport(Request $request): View
    {
        $dateFrom = $request->get('date_from', now()->subMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $labRequests = LabRequest::with(['patient', 'doctor', 'items.labTest'])
            ->whereBetween('request_date', [$dateFrom, $dateTo])
            ->get();

        $totalRequests = $labRequests->count();
        $pendingRequests = $labRequests->where('status', 'pending')->count();
        $completedRequests = $labRequests->where('status', 'completed')->count();
        $cancelledRequests = $labRequests->where('status', 'cancelled')->count();

        $totalRevenue = $labRequests->sum('total_amount');

        return view('hms.reports.lab', compact(
            'labRequests',
            'totalRequests',
            'pendingRequests',
            'completedRequests',
            'cancelledRequests',
            'totalRevenue',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Pharmacy Report
     */
    public function pharmacyReport(Request $request): View
    {
        $dateFrom = $request->get('date_from', now()->subMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $prescriptions = Prescription::with(['patient', 'doctor', 'items.medicine'])
            ->whereBetween('prescription_date', [$dateFrom, $dateTo])
            ->get();

        $totalPrescriptions = $prescriptions->count();
        $totalRevenue = $prescriptions->sum('total_amount');

        return view('hms.reports.pharmacy', compact(
            'prescriptions',
            'totalPrescriptions',
            'totalRevenue',
            'dateFrom',
            'dateTo'
        ));
    }

        /**
     * Blood Bank Report
     */
    public function bloodBankReport(Request $request): View
    {
        $dateFrom = $request->get('date_from', now()->subMonth()->format('Y-m-d'));                                                                             
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $donors = BloodDonor::with('donations')
            ->whereBetween('created_at', [$dateFrom, $dateTo])
            ->get();

        $totalDonations = $donors->sum(function($donor) {
            return $donor->donations->count();
        });

        $totalDonors = $donors->count();

        return view('hms.reports.blood-bank', compact(
            'donors',
            'totalDonations',
            'totalDonors',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Bed Occupancy Report
     */
    public function bedOccupancyReport(Request $request): View
    {
        $beds = Bed::with(['bedType', 'currentAssignment.patient'])->get();
        
        $totalBeds = $beds->count();
        $occupiedBeds = $beds->where('status', 'occupied')->count();
        $availableBeds = $beds->where('status', 'available')->count();
        $maintenanceBeds = $beds->where('status', 'maintenance')->count();

        $occupancyRate = $totalBeds > 0 ? ($occupiedBeds / $totalBeds) * 100 : 0;

        return view('hms.reports.bed-occupancy', compact(
            'beds',
            'totalBeds',
            'occupiedBeds',
            'availableBeds',
            'maintenanceBeds',
            'occupancyRate'
        ));
    }

    /**
     * Diagnosis Report
     */
    public function diagnosisReport(Request $request): View
    {
        $dateFrom = $request->get('date_from', now()->subMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $diagnoses = PatientDiagnosis::with(['patient', 'doctor', 'diagnosisCategory'])
            ->whereBetween('diagnosis_date', [$dateFrom, $dateTo])
            ->get();

        $diagnosesByCategory = $diagnoses->groupBy('diagnosis_category_id');

        return view('hms.reports.diagnosis', compact(
            'diagnoses',
            'diagnosesByCategory',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Doctor Performance Report
     */
    public function doctorPerformanceReport(Request $request): View
    {
        $dateFrom = $request->get('date_from', now()->subMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $doctors = Doctor::with(['appointments' => function($query) use ($dateFrom, $dateTo) {
            $query->whereBetween('appointment_date', [$dateFrom, $dateTo]);
        }])->get();

        $doctorPerformance = $doctors->map(function($doctor) {
            return [
                'doctor' => $doctor,
                'total_appointments' => $doctor->appointments->count(),
                'completed_appointments' => $doctor->appointments->where('status', 'completed')->count(),
                'cancelled_appointments' => $doctor->appointments->where('status', 'cancelled')->count(),
            ];
        })->sortByDesc('total_appointments');

        return view('hms.reports.doctor-performance', compact(
            'doctorPerformance',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Expense Report
     */
    public function expenseReport(Request $request): View
    {
        $dateFrom = $request->get('date_from', now()->subMonth()->format('Y-m-d'));
        $dateTo = $request->get('date_to', now()->format('Y-m-d'));

        $expenses = Expense::with('category')
            ->whereBetween('expense_date', [$dateFrom, $dateTo])
            ->get();

        $totalExpenses = $expenses->sum('amount');
        $expensesByCategory = $expenses->groupBy('expense_category_id');

        return view('hms.reports.expense', compact(
            'expenses',
            'totalExpenses',
            'expensesByCategory',
            'dateFrom',
            'dateTo'
        ));
    }

    /**
     * Summary Reports
     */
    public function summaryReports(): View
    {
        // Get all summary statistics
        $revenue = Payment::sum('amount');
        $expenses = Expense::sum('amount');
        $netProfit = $revenue - $expenses;

        $patientCount = \App\Models\Patient::count();
        $doctorCount = Doctor::count();
        $employeeCount = \App\Models\Employee::count();

        $todayAppointments = Appointment::whereDate('appointment_date', today())->count();
        $todayRevenue = Payment::whereDate('payment_date', today())->sum('amount');

        return view('hms.reports.summary', compact(
            'revenue',
            'expenses',
            'netProfit',
            'patientCount',
            'doctorCount',
            'employeeCount',
            'todayAppointments',
            'todayRevenue'
        ));
    }
}
