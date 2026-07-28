<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use App\Models\EmployeeDepartment;
use App\Models\Attendance;
use App\Models\LeaveRequest;
use App\Models\Payroll;
use App\Models\PerformanceAppraisal;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\Response;
use Barryvdh\DomPDF\Facade\Pdf;

class HrReportsController extends Controller
{
    /**
     * Display HR reports index
     */
    public function index(): View
    {
        return view('hms.hr.reports.index');
    }

    /**
     * Employee list by department
     */
    public function employeeList(Request $request)
    {
        $query = Employee::with('department');
        
        if ($request->department_id) {
            $query->where('department_id', $request->department_id);
        }
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        $employees = $query->orderBy('first_name')->get();
        $departments = EmployeeDepartment::all();
        
        if ($request->format === 'pdf') {
            $pdf = Pdf::loadView('hms.hr.reports.employee-list-pdf', compact('employees'));
            return $pdf->download('employee_list_' . date('Y-m-d') . '.pdf');
        }
        
        if ($request->format === 'excel') {
            // Export to CSV
            $filename = 'employee_list_' . date('Y-m-d') . '.csv';
            $headers = [
                'Content-Type' => 'text/csv',
                'Content-Disposition' => 'attachment; filename="' . $filename . '"',
            ];

            $callback = function() use ($employees) {
                $file = fopen('php://output', 'w');
                fputcsv($file, ['Employee ID', 'Name', 'Email', 'Department', 'Position', 'Status']);
                foreach ($employees as $employee) {
                    fputcsv($file, [
                        $employee->employee_id,
                        $employee->full_name,
                        $employee->email,
                        $employee->department->name ?? '',
                        $employee->position,
                        $employee->status,
                    ]);
                }
                fclose($file);
            };

            return response()->stream($callback, 200, $headers);
        }
        
        return view('hms.hr.reports.employee-list', compact('employees', 'departments'));
    }

    /**
     * Leave report
     */
    public function leaveReport(Request $request)
    {
        $query = LeaveRequest::with(['employee', 'leaveType']);
        
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('start_date', [$request->start_date, $request->end_date]);
        }
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        if ($request->employee_id) {
            $query->where('employee_id', $request->employee_id);
        }
        
        $leaveRequests = $query->orderBy('start_date', 'desc')->get();
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get();
        
        if ($request->format === 'pdf') {
            $pdf = Pdf::loadView('hms.hr.reports.leave-report-pdf', compact('leaveRequests'));
            return $pdf->download('leave_report_' . date('Y-m-d') . '.pdf');
        }
        
        return view('hms.hr.reports.leave-report', compact('leaveRequests', 'employees'));
    }

    /**
     * Attendance report
     */
    public function attendanceReport(Request $request)
    {
        $query = Attendance::with('user');
        
        if ($request->start_date && $request->end_date) {
            $query->whereBetween('date', [$request->start_date, $request->end_date]);
        }
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        $attendances = $query->orderBy('date', 'desc')->paginate(50);
        
        // Statistics
        $stats = [
            'total' => Attendance::whereBetween('date', [$request->start_date ?? now()->startOfMonth(), $request->end_date ?? now()])->count(),
            'present' => Attendance::whereBetween('date', [$request->start_date ?? now()->startOfMonth(), $request->end_date ?? now()])->where('status', 'present')->count(),
            'absent' => Attendance::whereBetween('date', [$request->start_date ?? now()->startOfMonth(), $request->end_date ?? now()])->where('status', 'absent')->count(),
            'late' => Attendance::whereBetween('date', [$request->start_date ?? now()->startOfMonth(), $request->end_date ?? now()])->where('status', 'late')->count(),
        ];
        
        if ($request->format === 'pdf') {
            $pdf = Pdf::loadView('hms.hr.reports.attendance-report-pdf', compact('attendances', 'stats'));
            return $pdf->download('attendance_report_' . date('Y-m-d') . '.pdf');
        }
        
        return view('hms.hr.reports.attendance-report', compact('attendances', 'stats'));
    }

    /**
     * Payroll summary
     */
    public function payrollSummary(Request $request)
    {
        $query = Payroll::with('employee');
        
        if ($request->month && $request->year) {
            $query->whereYear('pay_date', $request->year)
                  ->whereMonth('pay_date', $request->month);
        }
        
        $payrolls = $query->orderBy('pay_date', 'desc')->get();
        
        $summary = [
            'total_gross' => $payrolls->sum('gross_salary'),
            'total_net' => $payrolls->sum('net_salary'),
            'total_deductions' => $payrolls->sum('deductions'),
            'count' => $payrolls->count(),
        ];
        
        if ($request->format === 'pdf') {
            $pdf = Pdf::loadView('hms.hr.reports.payroll-summary-pdf', compact('payrolls', 'summary'));
            return $pdf->download('payroll_summary_' . date('Y-m-d') . '.pdf');
        }
        
        return view('hms.hr.reports.payroll-summary', compact('payrolls', 'summary'));
    }

    /**
     * Headcount trends
     */
    public function headcountTrends(Request $request): View
    {
        $year = $request->year ?? now()->year;
        
        $trends = [];
        for ($month = 1; $month <= 12; $month++) {
            $trends[$month] = Employee::whereYear('hire_date', '<=', $year)
                ->where(function($q) use ($year, $month) {
                    $q->whereYear('hire_date', '<', $year)
                      ->orWhere(function($q2) use ($year, $month) {
                          $q2->whereYear('hire_date', $year)
                             ->whereMonth('hire_date', '<=', $month);
                      });
                })
                ->where(function($q) use ($year, $month) {
                    $q->whereNull('termination_date')
                      ->orWhere(function($q2) use ($year, $month) {
                          $q2->whereYear('termination_date', '>', $year)
                             ->orWhere(function($q3) use ($year, $month) {
                                 $q3->whereYear('termination_date', $year)
                                    ->whereMonth('termination_date', '>', $month);
                             });
                      });
                })
                ->where('status', 'active')
                ->count();
        }
        
        return view('hms.hr.reports.headcount-trends', compact('trends', 'year'));
    }

    /**
     * Attrition report
     */
    public function attritionReport(Request $request): View
    {
        $year = $request->year ?? now()->year;
        
        $attrition = Employee::whereYear('termination_date', $year)
            ->where('status', 'terminated')
            ->with('department')
            ->orderBy('termination_date')
            ->get();
        
        $departmentAttrition = Employee::selectRaw('employee_departments.name as department, COUNT(*) as count')
            ->join('employee_departments', 'employees.department_id', '=', 'employee_departments.id')
            ->whereYear('termination_date', $year)
            ->where('status', 'terminated')
            ->groupBy('employee_departments.name')
            ->get();
        
        return view('hms.hr.reports.attrition', compact('attrition', 'departmentAttrition', 'year'));
    }

    /**
     * Salary expense analysis
     */
    public function salaryExpenseAnalysis(Request $request): View
    {
        $year = $request->year ?? now()->year;
        
        $monthlyExpenses = [];
        for ($month = 1; $month <= 12; $month++) {
            $monthlyExpenses[$month] = Payroll::whereYear('pay_date', $year)
                ->whereMonth('pay_date', $month)
                ->sum('net_salary') ?? 0;
        }
        
        $departmentExpenses = Payroll::selectRaw('employee_departments.name as department, SUM(payrolls.net_salary) as total')
            ->join('employees', 'payrolls.employee_id', '=', 'employees.id')
            ->join('employee_departments', 'employees.department_id', '=', 'employee_departments.id')
            ->whereYear('payrolls.pay_date', $year)
            ->groupBy('employee_departments.name')
            ->orderBy('total', 'desc')
            ->get();
        
        return view('hms.hr.reports.salary-expense', compact('monthlyExpenses', 'departmentExpenses', 'year'));
    }

    /**
     * Training participation report
     */
    public function trainingParticipation(Request $request)
    {
        $query = \App\Models\TrainingEnrollment::with(['trainingProgram', 'employee']);
        
        if ($request->training_program_id) {
            $query->where('training_program_id', $request->training_program_id);
        }
        
        if ($request->status) {
            $query->where('status', $request->status);
        }
        
        $enrollments = $query->latest()->get();
        $trainingPrograms = \App\Models\TrainingProgram::all();
        
        return view('hms.hr.reports.training-participation', compact('enrollments', 'trainingPrograms'));
    }
}

