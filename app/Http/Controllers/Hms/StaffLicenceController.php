<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\StaffLicence;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class StaffLicenceController extends Controller
{
    public function index(): View
    {
        $licences = StaffLicence::with('employee')->latest()->paginate(10);
        return view('hms.hr.licences.index', compact('licences'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'employee_id' => 'required|exists:employees,id',
            'licence_type' => 'required|in:medical,nursing,pharmacy,lab,other',
            'licence_number' => 'required|string|max:255',
            'issuing_body' => 'required|string|max:255',
            'issue_date' => 'required|date',
            'expiry_date' => 'required|date|after:issue_date',
        ]);

        $data['status'] = 'active';

        StaffLicence::create($data);

        return redirect()->route('hms.hr.licences.index')
            ->with('success', 'Staff licence recorded.');
    }

    public function expiring(): View
    {
        $licences = StaffLicence::with('employee')
            ->where('expiry_date', '<=', now()->addDays(30))
            ->where('expiry_date', '>=', now())
            ->where('status', 'active')
            ->orderBy('expiry_date')
            ->get();

        return view('hms.hr.licences.expiring', compact('licences'));
    }
}
