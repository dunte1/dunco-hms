<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\OpdVisit;
use App\Models\IpdAdmission;
use App\Models\Invoice;
use App\Models\Payment;
use App\Models\LabRequest;
use App\Models\RadiologyRequest;
use App\Models\User;
use App\Models\Employee;
use App\Models\Attendance;
use App\Models\Bed;
use App\Models\Ward;
use App\Models\Doctor;
use App\Models\Nurse;
use App\Models\Pharmacist;
use App\Models\LabTechnician;
use App\Models\Receptionist;
use App\Models\Medicine;
use App\Models\MedicineBatch;
use App\Models\InsuranceClaim;
use Carbon\Carbon;
use App\Services\Dashboard\MyWorkService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index(MyWorkService $myWork)
    {
        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();
        $yearStart = Carbon::now()->startOfYear();

        // Primary Stats
        $stats = [
            'total_patients' => Patient::count(),
            'new_patients_today' => Patient::whereDate('created_at', $today)->count(),
            'new_patients_month' => Patient::whereDate('created_at', '>=', $monthStart)->count(),
            'total_doctors' => Doctor::count(),
            'active_inpatients' => IpdAdmission::whereNull('discharge_date')->count(),
            'todays_appointments' => Appointment::whereDate('scheduled_at', $today)->count(),
            'todays_opd_visits' => OpdVisit::whereDate('visit_date', $today)->count(),
            'todays_admissions' => IpdAdmission::whereDate('admission_date', $today)->count(),
            'todays_discharges' => IpdAdmission::whereDate('discharge_date', $today)->count(),
            
            // Staff Stats
            'total_nurses' => Nurse::count(),
            'total_pharmacists' => Pharmacist::count(),
            'total_lab_technicians' => LabTechnician::count(),
            'total_receptionists' => Receptionist::count(),
            
            // Bed Stats
            'available_beds' => Bed::where('is_available', true)->count(),
            'total_beds' => Bed::count(),
            'occupied_beds' => Bed::where('is_available', false)->count(),
            
            // Financial Stats
            'total_invoices' => Invoice::sum('total_amount') ?? 0,
            'total_payments' => Payment::whereDate('payment_date', $today)->sum('amount') ?? 0,
            'monthly_revenue' => Payment::whereDate('payment_date', '>=', $monthStart)->sum('amount') ?? 0,
            'outstanding_balance' => Invoice::where('status', '!=', 'paid')->sum('balance_amount') ?? 0,
            
            // Diagnostic Stats
            'pending_lab_requests' => LabRequest::where('status', 'pending')->count(),
            'pending_radiology' => RadiologyRequest::where('status', 'pending')->count(),
            
            // Pharmacy Stats
            'low_stock_items' => Medicine::where('stock_quantity', '<=', 10)->count(),
            'expiring_items' => MedicineBatch::where('expiry_date', '<=', now()->addDays(30))
                ->where('expiry_date', '>=', now())->count(),
            
            // Insurance
            'pending_claims' => InsuranceClaim::where('status', 'pending')->count(),
        ];

        // Revenue data for the last 12 months (chart)
        $revenueChart = [];
        for ($i = 11; $i >= 0; $i--) {
            $month = Carbon::now()->subMonths($i);
            $revenueChart[] = [
                'month' => $month->format('M'),
                'revenue' => Payment::whereMonth('payment_date', $month->month)
                    ->whereYear('payment_date', $month->year)
                    ->sum('amount') ?? 0,
                'invoices' => Invoice::whereMonth('invoice_date', $month->month)
                    ->whereYear('invoice_date', $month->year)
                    ->sum('total_amount') ?? 0,
            ];
        }

        // Patient registrations over time (last 30 days)
        $patientChart = [];
        for ($i = 29; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $patientChart[] = [
                'date' => $date->format('M d'),
                'count' => Patient::whereDate('created_at', $date)->count(),
            ];
        }

        // Appointments over time (last 7 days)
        $appointmentChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $appointmentChart[] = [
                'day' => $date->format('D'),
                'total' => Appointment::whereDate('scheduled_at', $date)->count(),
                'completed' => Appointment::whereDate('scheduled_at', $date)->where('status', 'completed')->count(),
                'pending' => Appointment::whereDate('scheduled_at', $date)->where('status', 'pending')->count(),
            ];
        }

        // Department activity (OPD visits by department)
        $departmentActivity = OpdVisit::select('doctor_id', DB::raw('COUNT(*) as count'))
            ->whereDate('visit_date', '>=', $monthStart)
            ->groupBy('doctor_id')
            ->with('doctor.department')
            ->get()
            ->map(function ($item) {
                return [
                    'department' => $item->doctor->department->name ?? 'General',
                    'count' => $item->count,
                ];
            })
            ->groupBy('department')
            ->map(fn($group) => $group->sum('count'))
            ->sortDesc()
            ->take(8);

        // OPD visits vs IPD admissions (last 7 days)
        $visitChart = [];
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $visitChart[] = [
                'day' => $date->format('D'),
                'opd' => OpdVisit::whereDate('visit_date', $date)->count(),
                'ipd' => IpdAdmission::whereDate('admission_date', $date)->count(),
            ];
        }

        // Recent appointments
        $recentAppointments = Appointment::with(['patient', 'doctor'])
            ->latest()
            ->limit(5)
            ->get();
        
        // Recent lab requests
        $recentLabRequests = LabRequest::with(['patient'])
            ->latest()
            ->limit(5)
            ->get();

        // Ward occupancy data
        $wardOccupancy = Ward::withCount(['beds as total_beds'])
            ->withCount(['beds as occupied_beds' => function ($q) {
                $q->where('is_available', false);
            }])
            ->where('is_active', true)
            ->get()
            ->map(function ($ward) {
                $ward->occupancy_rate = $ward->total_beds > 0 
                    ? round(($ward->occupied_beds / $ward->total_beds) * 100, 1) 
                    : 0;
                return $ward;
            });
        
        return view('dashboard', compact(
            'stats', 'recentAppointments', 'recentLabRequests',
            'revenueChart', 'patientChart', 'appointmentChart',
            'departmentActivity', 'visitChart', 'wardOccupancy'
        ) + ['myWork' => $myWork->forUser()]);
    }
    
    public function todaySummary()
    {
        $today = Carbon::today();
        
        $stats = [
            'appointments' => [
                'total' => Appointment::whereDate('scheduled_at', $today)->count(),
                'completed' => Appointment::whereDate('scheduled_at', $today)->where('status', 'completed')->count(),
                'pending' => Appointment::whereDate('scheduled_at', $today)->where('status', 'pending')->count(),
                'cancelled' => Appointment::whereDate('scheduled_at', $today)->where('status', 'cancelled')->count(),
            ],
            'patients' => [
                'new_registrations' => Patient::whereDate('created_at', $today)->count(),
                'opd_visits' => OpdVisit::whereDate('visit_date', $today)->count(),
                'ipd_admissions' => IpdAdmission::whereDate('admission_date', $today)->count(),
                'total_active' => Patient::whereDate('created_at', $today)->count() + 
                                 OpdVisit::whereDate('visit_date', $today)->count(),
            ],
            'financial' => [
                'total_revenue' => Payment::whereDate('payment_date', $today)->sum('amount') ?? 0,
                'invoices_generated' => Invoice::whereDate('invoice_date', $today)->count(),
                'payments_received' => Payment::whereDate('payment_date', $today)->count(),
                'pending_amount' => Invoice::whereDate('invoice_date', $today)
                    ->where('status', 'pending')
                    ->sum('total_amount') ?? 0,
            ],
            'diagnostics' => [
                'lab_tests' => LabRequest::whereDate('request_date', $today)->count(),
                'radiology_tests' => RadiologyRequest::whereDate('request_date', $today)->count(),
                'completed_tests' => LabRequest::whereDate('request_date', $today)->where('status', 'completed')->count() +
                                    RadiologyRequest::whereDate('request_date', $today)->where('status', 'completed')->count(),
            ],
        ];
        
        $recentAppointments = Appointment::with(['patient', 'doctor'])
            ->whereDate('scheduled_at', $today)
            ->orderBy('scheduled_at', 'desc')
            ->limit(10)
            ->get();
        
        $recentOpdVisits = OpdVisit::with(['patient'])
            ->whereDate('visit_date', $today)
            ->orderBy('visit_date', 'desc')
            ->limit(10)
            ->get();
        
        return view('hms.dashboard.today-summary', compact('stats', 'recentAppointments', 'recentOpdVisits'));
    }
    
    public function activeStaff()
    {
        $today = Carbon::today();
        
        $totalUsers = User::count();
        $totalEmployees = Employee::where('status', 'active')->count();
        
        $usersWithEmployees = User::whereHas('employee')->count();
        $employeesWithoutUsers = Employee::where('status', 'active')->whereNull('user_id')->count();
        $standaloneUsers = User::whereDoesntHave('employee')->count();
        $totalUniqueStaff = $usersWithEmployees + $employeesWithoutUsers + $standaloneUsers;
        
        $attendanceStats = [
            'total_staff' => $totalUniqueStaff > 0 ? $totalUniqueStaff : max($totalUsers, $totalEmployees),
            'total_users' => $totalUsers,
            'total_employees' => $totalEmployees,
            'present_today' => Attendance::whereDate('date', $today)
                ->where('status', 'present')
                ->distinct('user_id')
                ->count('user_id'),
            'on_leave' => Attendance::whereDate('date', $today)
                ->where('status', 'leave')
                ->distinct('user_id')
                ->count('user_id'),
            'absent' => Attendance::whereDate('date', $today)
                ->where('status', 'absent')
                ->distinct('user_id')
                ->count('user_id'),
        ];
        
        $staffByRole = [
            'doctors' => User::role('Doctor')->with(['attendance' => function($q) use ($today) {
                $q->whereDate('date', $today);
            }])->get(),
            'nurses' => User::role('Nurse')->with(['attendance' => function($q) use ($today) {
                $q->whereDate('date', $today);
            }])->get(),
            'receptionists' => User::role('Receptionist')->with(['attendance' => function($q) use ($today) {
                $q->whereDate('date', $today);
            }])->get(),
            'lab_technicians' => User::role('Lab Technician')->with(['attendance' => function($q) use ($today) {
                $q->whereDate('date', $today);
            }])->get(),
            'pharmacists' => User::role('Pharmacist')->with(['attendance' => function($q) use ($today) {
                $q->whereDate('date', $today);
            }])->get(),
        ];
        
        $activeEmployees = Employee::where('status', 'active')
            ->with(['department', 'user'])
            ->get();
        
        $recentCheckIns = Attendance::with('user')
            ->whereDate('date', $today)
            ->whereNotNull('check_in')
            ->orderBy('check_in', 'desc')
            ->limit(15)
            ->get();
        
        return view('hms.dashboard.active-staff', compact('attendanceStats', 'staffByRole', 'activeEmployees', 'recentCheckIns'));
    }
}
