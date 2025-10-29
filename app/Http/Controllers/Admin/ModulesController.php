<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;

class ModulesController extends Controller
{
    public static function registry(): array
    {
        return [
            'Dashboard',
            'Accountants', 'Accounts', 'Advance Payments',
            'Ambulance', 'Ambulance Calls',
            'Appointments',
            'Beds Management', 'Bed Assigns', 'Beds Visulization', 'Bed Status', 'Bed Types',
            'Billing', 'Invoices', 'Payments', 'Payment Reports', 'Manual Billing Payments',
            'Birth Reports', 'Death Reports', 'Operation Reports', 'Investigation Reports',
            'Blood Bank', 'Blood Donors',
            'Case Handlers', 'Cases Management',
            'Doctors Management', 'Doctors Departments', 'Doctor OPD Charge', 'Schedules',
            'Documents', 'Document Types',
            'IPD (In Patient Department)', 'OPD (Out Patient Department)', 'Patient Admissions',
            'Payrolls',
            'Inquiry',
            'Expenses Management', 'Income Management',
            'Hospital Charges', 'Hospital Charges Categories',
            'Insurance Management', 'Packages Management',
            'Lab Technician', 'Nurses Management', 'Pharmacists', 'Receptionists',
            'Medicines (+ Inventory)', 'Medicines Brands', 'Medicines Categories', 'Full Inventory Management',
            'Notice Board',
            'Pathology Categories', 'Pathology Tests',
            'Patient Diagnosis Categories', 'Patient Diagnosis Reports',
            'Patients Management', 'Prescriptions Management',
            'Radiology Categories', 'Radiology Tests',
            'Send Mails', 'SMS Reminders',
            'Settings', 'Frontend CMS', 'Multi-Lingual', 'Multi-Currency', 'Export of Everything',
            'Roles + ALC for 8 Different Departments',
        ];
    }

    public function index(): View
    {
        $modules = self::registry();
        return view('admin.modules.index', compact('modules'));
    }

    public function show(string $slug): View|RedirectResponse
    {
        $modules = collect(self::registry());
        $name = $modules->first(function ($m) use ($slug) {
            return str($m)->slug('-') == $slug;
        });
        abort_unless($name, 404);
        
        // Handle specific modules with custom logic
        if ($slug === 'multi-currency') {
            return redirect()->route('admin.modules.multi-currency.index');
        }
        
        // Handle Dashboard module with real data
        if ($slug === 'dashboard' || $name === 'Dashboard') {
            return $this->showDashboard();
        }
        
        // Get route mapping for the module
        $route = $this->getModuleRoute($name);
        
        if ($route) {
            // Redirect to existing route if available
            return redirect()->route($route);
        }
        
        // Otherwise show module-specific view if it exists
        $viewPath = 'admin.modules.' . str($slug)->kebab();
        if (view()->exists($viewPath)) {
            return view($viewPath, ['module' => $name]);
        }
        
        // Fallback to placeholder
        return view('admin.modules.placeholder', ['module' => $name]);
    }
    
    /**
     * Map module names to their corresponding routes
     */
    private function getModuleRoute(string $moduleName): ?string
    {
        $routeMap = [
            'Accountants' => 'hms.staff.accountants',
            'Accounts' => 'hms.finance.accounts.index',
            'Advance Payments' => 'hms.advance-payments.index',
            'Ambulance' => 'hms.ambulance.index',
            'Ambulance Calls' => 'hms.ambulance.calls',
            'Appointments' => 'hms.appointments.index',
            'Beds Management' => 'hms.beds.index',
            'Bed Assigns' => 'hms.beds.index',
            'Beds Visulization' => 'hms.beds.index',
            'Bed Status' => 'hms.beds.index',
            'Bed Types' => 'hms.bed-types.index',
            'Billing' => 'hms.billing.index',
            'Invoices' => 'hms.billing.invoices.index',
            'Payments' => 'hms.payments.index',
            'Payment Reports' => 'hms.billing.payment-reports',
            'Manual Billing Payments' => 'hms.billing.index',
            'Birth Reports' => 'hms.reports.birth',
            'Death Reports' => 'hms.reports.death',
            'Operation Reports' => 'hms.reports.operation',
            'Investigation Reports' => 'hms.investigation-reports.index',
            'Blood Bank' => 'hms.bloodbank.index',
            'Blood Donors' => 'hms.bloodbank.donors',
            'Case Handlers' => 'hms.case-handlers.index',
            'Cases Management' => 'hms.case-handlers.cases',
            'Doctors Management' => 'hms.doctors.index',
            'Doctors Departments' => 'hms.doctors.departments.index',
            'Doctor OPD Charge' => 'hms.doctor-charges.index',
            'Schedules' => 'hms.schedules.index',
            'Documents' => 'hms.hr.documents.index',
            'Document Types' => 'hms.hr.document-types',
            'IPD (In Patient Department)' => 'hms.ipd.index',
            'OPD (Out Patient Department)' => 'hms.opd.index',
            'Patient Admissions' => 'hms.ipd.index',
            'Payrolls' => 'hms.payrolls.index',
            'Inquiry' => 'admin.enquiries.index',
            'Expenses Management' => 'hms.finance.expenses.index',
            'Income Management' => 'hms.finance.income.index',
            'Hospital Charges' => 'hms.billing.index',
            'Hospital Charges Categories' => 'hms.billing.index',
            'Insurance Management' => 'hms.insurance.index',
            'Packages Management' => 'hms.packages.index',
            'Lab Technician' => 'hms.staff.lab-technicians',
            'Nurses Management' => 'hms.nurses.index',
            'Pharmacists' => 'hms.staff.pharmacists',
            'Receptionists' => 'hms.staff.receptionists',
            'Medicines (+ Inventory)' => 'hms.medicines.index',
            'Medicines Brands' => 'hms.pharmacy.medicine-brands.index',
            'Medicines Categories' => 'hms.pharmacy.medicine-categories.index',
            'Full Inventory Management' => 'hms.inventory.index',
            'Notice Board' => 'admin.notices.index',
            'Pathology Categories' => 'hms.test-categories.index',
            'Pathology Tests' => 'hms.lab-tests.index',
            'Patient Diagnosis Categories' => 'hms.test-categories.index',
            'Patient Diagnosis Reports' => 'hms.diagnosis.index',
            'Patients Management' => 'hms.patients.index',
            'Prescriptions Management' => 'hms.prescriptions.index',
            'Radiology Categories' => 'hms.test-categories.index',
            'Radiology Tests' => 'hms.radiology-tests.index',
            'Send Mails' => 'hms.messaging.index',
            'SMS Reminders' => 'hms.reminders.index',
            'Settings' => 'hms.settings.index',
            'Frontend CMS' => 'cms.index',
            'Multi-Lingual' => 'hms.system.localization',
            'Export of Everything' => 'hms.reports.index',
            'Roles + ALC for 8 Different Departments' => 'admin.roles.index',
        ];
        
        return $routeMap[$moduleName] ?? null;
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
    
    private function showDashboard(): View
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
            'totalAppointments' => \App\Models\Appointment::count(),
            'todayAppointments' => \App\Models\Appointment::whereDate('appointment_date', today())->count(),
            'pendingAppointments' => \App\Models\Appointment::where('status', 'pending')->count(),
            'totalBeds' => \App\Models\Bed::count(),
            'occupiedBeds' => \App\Models\Bed::where('is_available', false)->count(),
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
        
        // Recent data
        $notices = \App\Models\Notice::latest()->take(5)->get();
        $enquiries = \App\Models\Enquiry::latest()->take(5)->get();
        $appointments = \App\Models\AppointmentRequest::latest()->take(5)->get();
        $recentPatients = \App\Models\Patient::latest()->take(5)->get();
        $recentAppointments = \App\Models\Appointment::with(['patient', 'doctor'])->latest()->take(5)->get();
        
        return view('admin.modules.dashboard', compact('metrics', 'chart', 'notices', 'enquiries', 'appointments', 'recentPatients', 'recentAppointments'));
    }
}


