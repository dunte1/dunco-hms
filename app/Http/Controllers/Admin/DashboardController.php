<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Calculate real metrics from database
        $metrics = [
            'invoiceAmount' => \App\Models\Invoice::sum('total_amount') ?? 0,
            'billAmount' => \App\Models\Invoice::where('status', '!=', 'paid')->sum('balance_amount') ?? 0,
            'paymentAmount' => \App\Models\Payment::sum('amount') ?? 0,
            'advanceAmount' => \App\Models\AdvancePayment::sum('amount') ?? 0,
            'availableBeds' => \App\Models\Bed::where('is_available', true)->count(),
            'doctors' => \App\Models\Doctor::count(),
            'patients' => \App\Models\Patient::count(),
            'nurses' => \App\Models\Nurse::where('is_active', true)->count(),
            'admins' => $this->countUsersWithRole('admin'),
            'accountants' => \App\Models\Accountant::where('is_active', true)->count(),
            'labTechs' => \App\Models\LabTechnician::where('is_active', true)->count(),
            'pharmacists' => \App\Models\Pharmacist::where('is_active', true)->count(),
            'receptionists' => \App\Models\Receptionist::where('is_active', true)->count(),
        ];
        
        // Calculate monthly income and expenses for chart
        $currentYear = now()->year;
        $monthlyIncome = [];
        $monthlyExpenses = [];
        
        for ($i = 1; $i <= 12; $i++) {
            $monthlyIncome[] = \App\Models\Payment::whereYear('payment_date', $currentYear)
                ->whereMonth('payment_date', $i)
                ->sum('amount') ?? 0;
            
            $monthlyExpenses[] = \App\Models\Expense::whereYear('expense_date', $currentYear)
                ->whereMonth('expense_date', $i)
                ->sum('amount') ?? 0;
        }
        
        $chart = [
            'labels' => ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
            'income' => $monthlyIncome,
            'expenses' => $monthlyExpenses,
        ];
        $notices = \App\Models\Notice::latest()->take(5)->get(['title','published_at']);
        $enquiries = \App\Models\Enquiry::latest()->take(5)->get(['name','subject','created_at']);
        $appointments = \App\Models\AppointmentRequest::latest()->take(5)->get(['patient_name','preferred_date']);
        return view('admin.dashboard', compact('metrics','chart','notices','enquiries','appointments'));
    }
    
    /**
     * Safely count users with a specific role without throwing exceptions
     */
    private function countUsersWithRole(string $roleName): int
    {
        try {
            $role = \Spatie\Permission\Models\Role::where('name', $roleName)->where('guard_name', 'web')->first();
            if ($role) {
                return $role->users()->count();
            }
            return 0;
        } catch (\Exception $e) {
            return 0;
        }
    }
}


