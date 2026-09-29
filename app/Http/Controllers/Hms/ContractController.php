<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\EmployeeContract;
use App\Models\Employee;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ContractController extends Controller
{
    public function index(): View
    {
        $contracts = EmployeeContract::with('employee')->latest()->paginate(10);
        return view('hms.hr.contracts.index', compact('contracts'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'contract_type' => 'required|in:permanent,temporary,locum,intern',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'salary' => 'nullable|numeric|min:0',
            'salary_type' => 'required|in:monthly,daily,hourly',
            'notes' => 'nullable|string',
        ]);

        $data['status'] = 'active';

        EmployeeContract::create($data);

        return redirect()->route('hms.hr.contracts.index')
            ->with('success', 'Contract created successfully.');
    }
}
