<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Module;
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
        $modules = Module::orderBy('category')->orderBy('sort_order')->orderBy('name')->get();

        $categories = $modules->groupBy('category');
        $registry = self::registry();

        // Ensure modules from the legacy registry that are not yet in the DB get seeded.
        $this->syncRegistryToDb($registry, $modules);

        $enabledCount = Module::where('is_enabled', true)->count();
        $disabledCount = Module::where('is_enabled', false)->count();

        return view('admin.modules.index', compact('modules', 'categories', 'enabledCount', 'disabledCount'));
    }

    public function enable(Module $module): RedirectResponse
    {
        $module->update(['is_enabled' => true]);

        return back()->with('success', "Module '{$module->name}' enabled.");
    }

    public function disable(Request $request, Module $module): RedirectResponse
    {
        $request->validate(['confirm' => 'required|in:1']);

        if (in_array($module->slug, Module::alwaysEnabledSlugs(), true)) {
            return back()->withErrors(['confirm' => "Core module '{$module->name}' cannot be disabled."]);
        }

        $module->update(['is_enabled' => false]);

        return back()->with('success', "Module '{$module->name}' disabled.");
    }

    public function toggle(Module $module): RedirectResponse
    {
        if (in_array($module->slug, Module::alwaysEnabledSlugs(), true)) {
            return back()->withErrors(['module' => "Core module '{$module->name}' cannot be disabled."]);
        }

        $module->update(['is_enabled' => !$module->is_enabled]);

        return back()->with('success', "Module '{$module->name}' " . ($module->is_enabled ? 'enabled' : 'disabled') . '.');
    }

    public function enableAll(): RedirectResponse
    {
        Module::query()->whereNotIn('slug', Module::alwaysEnabledSlugs())
            ->update(['is_enabled' => true]);

        Module::resetCache();

        return back()->with('success', 'All modules enabled.');
    }

    public function show(string $slug): View|RedirectResponse
    {
        $categoryName = $slug;

        $modules = collect(self::registry());
        $name = $modules->first(function ($m) use ($slug) {
            return str($m)->slug('-') == $slug;
        });

        if (!$name) {
            $dbModule = Module::where('slug', $slug)->first();
            if ($dbModule) {
                $name = $dbModule->name;
            } else {
                abort(404);
            }
        }

        if ($slug === 'multi-currency') {
            return redirect()->route('admin.modules.multi-currency.index');
        }

        if ($slug === 'dashboard' || $name === 'Dashboard') {
            return $this->showDashboard();
        }

        $route = $this->getModuleRoute($name);

        if ($route && \Route::has($route)) {
            // Only redirect if the module is enabled, else show module info.
            $dbModule = Module::where('slug', str($name)->slug('-'))->first();
            if (!$dbModule || $dbModule->is_enabled) {
                return redirect()->route($route);
            }
            return view('admin.modules.module-info', [
                'module' => $name,
                'route' => $route,
                'disabled' => true,
            ]);
        }

        $viewPath = 'admin.modules.' . str($slug)->kebab();
        if (view()->exists($viewPath)) {
            return view($viewPath, ['module' => $name, 'category' => $categoryName]);
        }

        return view('admin.modules.module-info', [
            'module' => $name,
            'route' => $this->getModuleRoute($name),
        ]);
    }

    private function syncRegistryToDb(array $registry, $modules): void
    {
        $existingSlugs = $modules->pluck('slug')->map(fn ($s) => (string) $s)->all();

        $missing = array_filter($registry, function ($m) use ($existingSlugs) {
            return !in_array(str($m)->slug('-')->toString(), $existingSlugs, true);
        });

        if (!empty($missing)) {
            \App\Models\Module::unguarded(function () use ($missing) {
                foreach ($missing as $name) {
                    \App\Models\Module::firstOrCreate(
                        ['slug' => str($name)->slug('-')->toString()],
                        ['name' => $name, 'category' => 'Other', 'is_enabled' => true, 'sort_order' => 900]
                    );
                }
            });
            \App\Models\Module::resetCache();
        }
    }

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
            'Insurance Management' => 'hms.insurance.claims.index',
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
            'SHA / SHIF' => 'hms.sha.index',
            'Biometric Security' => 'biometric.index',
            'Telemedicine' => 'telemedicine.index',
            'Queue Management' => 'hms.queue.index',
            'Visitor Management' => 'hms.visitors.index',
            'RFID & IoT' => 'rfid.index',
            'Documents' => 'hms.hr.documents.index',
        ];
        
        return $routeMap[$moduleName] ?? null;
    }

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

        $notices = \App\Models\Notice::latest()->take(5)->get();
        $enquiries = \App\Models\Enquiry::latest()->take(5)->get();
        $appointments = \App\Models\AppointmentRequest::latest()->take(5)->get();
        $recentPatients = \App\Models\Patient::latest()->take(5)->get();
        $recentAppointments = \App\Models\Appointment::with(['patient', 'doctor'])->latest()->take(5)->get();

        return view('admin.modules.dashboard', compact('metrics', 'chart', 'notices', 'enquiries', 'appointments', 'recentPatients', 'recentAppointments'));
    }
}
