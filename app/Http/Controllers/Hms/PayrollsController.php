<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Payroll;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PayrollsController extends Controller
{
    public function index(): View
    {
        $payrolls = Payroll::with('employee')->latest('pay_date')->paginate(10);
        return view('hms.hr.payrolls.index', compact('payrolls'));
    }

    public function create(): View
    {
        $employees = Employee::where('status', 'active')->orderBy('first_name')->get(['id', 'first_name', 'last_name', 'salary']);
        return view('hms.hr.payrolls.create', compact('employees'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'payroll_period' => 'required|string',
            'pay_date' => 'required|date',
            'basic_salary' => 'required|numeric|min:0',
            'overtime_pay' => 'nullable|numeric|min:0',
            'bonus' => 'nullable|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        // Calculate totals
        $data['overtime_pay'] = $data['overtime_pay'] ?? 0;
        $data['bonus'] = $data['bonus'] ?? 0;
        $data['allowances'] = $data['allowances'] ?? 0;
        $data['deductions'] = $data['deductions'] ?? 0;
        
        $data['gross_salary'] = $data['basic_salary'] + $data['overtime_pay'] + $data['bonus'] + $data['allowances'];
        $data['net_salary'] = $data['gross_salary'] - $data['deductions'];

        Payroll::create($data);
        return redirect()->route('hms.hr.payrolls.index')->with('status', 'Payroll created');
    }

    public function show(Payroll $payroll): View
    {
        $payroll->load('employee');
        return view('hms.hr.payrolls.show', compact('payroll'));
    }

    public function edit(Payroll $payroll): View
    {
        $employees = Employee::orderBy('first_name')->get();
        return view('hms.hr.payrolls.edit', compact('payroll', 'employees'));
    }

    public function update(Request $request, Payroll $payroll): RedirectResponse
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'period_start' => 'required|date',
            'period_end' => 'required|date|after_or_equal:period_start',
            'basic_salary' => 'required|numeric|min:0',
            'allowances' => 'nullable|numeric|min:0',
            'deductions' => 'nullable|numeric|min:0',
            'net_salary' => 'required|numeric|min:0',
            'status' => 'required|in:pending,paid,cancelled',
        ]);
        $payroll->update($data);
        return redirect()->route('hms.hr.payrolls.index')->with('status', 'Payroll updated');
    }

    public function destroy(Payroll $payroll): RedirectResponse
    {
        $payroll->delete();
        return redirect()->route('hms.hr.payrolls.index')->with('status', 'Payroll deleted');
    }
}
