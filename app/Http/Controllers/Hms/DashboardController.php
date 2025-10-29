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
use App\Models\Attendance;
use Carbon\Carbon;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        // Fetch all stats in one go for better performance
        $stats = [
            // Primary Stats
            'total_patients' => Patient::count(),
            'total_doctors' => \App\Models\Doctor::count(),
            'available_beds' => \App\Models\Bed::where('is_available', true)->count(),
            'total_beds' => \App\Models\Bed::count(),
            'todays_appointments' => Appointment::whereDate('appointment_date', today())->count(),
            
            // Staff Stats
            'total_nurses' => \App\Models\Nurse::count(),
            'total_pharmacists' => \App\Models\Pharmacist::count(),
            'total_lab_technicians' => \App\Models\LabTechnician::count(),
            'total_receptionists' => \App\Models\Receptionist::count(),
            
            // Financial Stats
            'total_invoices' => Invoice::sum('total_amount') ?? 0,
            'total_payments' => Payment::sum('amount') ?? 0,
            'outstanding_balance' => Invoice::where('status', '!=', 'paid')->sum('balance_amount') ?? 0,
        ];
        
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
        
        return view('dashboard', compact('stats', 'recentAppointments', 'recentLabRequests'));
    }
    
    public function todaySummary()
    {
        $today = Carbon::today();
        
        // Today's statistics
        $stats = [
            'appointments' => [
                'total' => Appointment::whereDate('appointment_date', $today)->count(),
                'completed' => Appointment::whereDate('appointment_date', $today)->where('status', 'completed')->count(),
                'pending' => Appointment::whereDate('appointment_date', $today)->where('status', 'pending')->count(),
                'cancelled' => Appointment::whereDate('appointment_date', $today)->where('status', 'cancelled')->count(),
            ],
            'patients' => [
                'new_registrations' => Patient::whereDate('created_at', $today)->count(),
                'opd_visits' => OpdVisit::whereDate('created_at', $today)->count(),
                'ipd_admissions' => IpdAdmission::whereDate('created_at', $today)->count(),
                'total_active' => Patient::whereDate('created_at', $today)->count() + 
                                 OpdVisit::whereDate('created_at', $today)->count(),
            ],
            'financial' => [
                'total_revenue' => Payment::whereDate('created_at', $today)->sum('amount') ?? 0,
                'invoices_generated' => Invoice::whereDate('created_at', $today)->count(),
                'payments_received' => Payment::whereDate('created_at', $today)->count(),
                'pending_amount' => Invoice::whereDate('created_at', $today)
                    ->where('status', 'pending')
                    ->sum('total_amount') ?? 0,
            ],
            'diagnostics' => [
                'lab_tests' => LabRequest::whereDate('created_at', $today)->count(),
                'radiology_tests' => RadiologyRequest::whereDate('created_at', $today)->count(),
                'completed_tests' => LabRequest::whereDate('created_at', $today)->where('status', 'completed')->count() +
                                    RadiologyRequest::whereDate('created_at', $today)->where('status', 'completed')->count(),
            ],
        ];
        
        // Recent appointments
        $recentAppointments = Appointment::with(['patient', 'doctor'])
            ->whereDate('appointment_date', $today)
            ->orderBy('appointment_time', 'desc')
            ->limit(10)
            ->get();
        
        // Recent OPD visits
        $recentOpdVisits = OpdVisit::with(['patient'])
            ->whereDate('created_at', $today)
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();
        
        return view('hms.dashboard.today-summary', compact('stats', 'recentAppointments', 'recentOpdVisits'));
    }
    
    public function activeStaff()
    {
        $today = Carbon::today();
        
        // Staff attendance stats
        $attendanceStats = [
            'total_staff' => User::count(),
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
        
        // Get staff by role with attendance
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
        
        // Recent check-ins
        $recentCheckIns = Attendance::with('user')
            ->whereDate('date', $today)
            ->whereNotNull('check_in')
            ->orderBy('check_in', 'desc')
            ->limit(15)
            ->get();
        
        return view('hms.dashboard.active-staff', compact('attendanceStats', 'staffByRole', 'recentCheckIns'));
    }
}
