<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\PayrollExport;
use App\Models\Payroll;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PayrollExportController extends Controller
{
    public function index(): View
    {
        $exports = PayrollExport::with('exportedByUser')
            ->latest('exported_at')
            ->paginate(10);

        return view('hms.hr.payroll-exports.index', compact('exports'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'export_month' => 'required|integer|min:1|max:12',
            'export_year' => 'required|integer|min:2020|max:2100',
        ]);

        $month = $data['export_month'];
        $year = $data['export_year'];

        $payrolls = Payroll::whereMonth('pay_date', $month)
            ->whereYear('pay_date', $year)
            ->get();

        $totalGross = $payrolls->sum('gross_salary');
        $totalDeductions = $payrolls->sum('deductions');
        $totalNet = $payrolls->sum('net_salary');
        $employeeCount = $payrolls->count();

        PayrollExport::create([
            'export_month' => $month,
            'export_year' => $year,
            'total_gross' => $totalGross,
            'total_deductions' => $totalDeductions,
            'total_net' => $totalNet,
            'employee_count' => $employeeCount,
            'status' => 'draft',
            'exported_by' => auth()->id(),
            'exported_at' => now(),
        ]);

        return redirect()->route('hms.hr.payroll-export.index')
            ->with('success', 'Payroll export created.');
    }
}
