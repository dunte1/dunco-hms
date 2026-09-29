<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\ChildProtectionCase;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class ChildProtectionController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'concern_type' => 'required|in:neglect,physical,sexual,emotional,other',
            'description' => 'required|string',
            'risk_level' => 'required|in:low,moderate,high,critical',
            'reported_date' => 'required|date',
            'assigned_to' => 'nullable|exists:users,id',
            'referral_agency' => 'nullable|string',
        ]);

        $data['reported_by'] = auth()->id();
        $data['status'] = 'open';

        ChildProtectionCase::create($data);

        return back()->with('status', 'Child protection case opened');
    }

    public function update(Request $request, ChildProtectionCase $case): RedirectResponse
    {
        $data = $request->validate([
            'risk_level' => 'sometimes|in:low,moderate,high,critical',
            'status' => 'sometimes|in:open,investigation,confirmed,closed,referred',
            'assigned_to' => 'nullable|exists:users,id',
            'referral_agency' => 'nullable|string',
            'outcome' => 'nullable|string',
        ]);

        $case->update($data);

        return back()->with('status', 'Case updated');
    }

    public function close(Request $request, ChildProtectionCase $case): RedirectResponse
    {
        $data = $request->validate([
            'outcome' => 'required|string',
        ]);

        $case->update([
            'status' => 'closed',
            'outcome' => $data['outcome'],
        ]);

        return back()->with('status', 'Case closed');
    }
}
