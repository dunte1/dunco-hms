<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Privilege;
use App\Models\PractitionerPrivilege;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PrivilegeController extends Controller
{
    public function index(): View
    {
        $privileges = Privilege::where('is_active', true)->orderBy('category')->orderBy('name')->get();
        $granted = PractitionerPrivilege::with('doctor', 'privilege', 'grantor')->latest()->paginate(15);

        return view('hms.credentialing.privileges', compact('privileges', 'granted'));
    }

    public function grant(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'privilege_id' => 'required|exists:privileges,id',
            'granted_date' => 'required|date',
            'expiry_date' => 'nullable|date|after:granted_date',
            'notes' => 'nullable|string',
        ]);

        $data['status'] = 'active';
        $data['granted_by'] = auth()->id();

        PractitionerPrivilege::create($data);

        return redirect()->route('hms.credentialing.privileges.index')
            ->with('success', 'Privilege granted successfully!');
    }

    public function revoke(Request $request, Privilege $privilege): RedirectResponse
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
        ]);

        $pp = PractitionerPrivilege::where('doctor_id', $request->doctor_id)
            ->where('privilege_id', $privilege->id)
            ->where('status', 'active')
            ->firstOrFail();

        $pp->update([
            'status' => 'revoked',
            'revoked_by' => auth()->id(),
            'revoked_at' => now(),
        ]);

        return redirect()->route('hms.credentialing.privileges.index')
            ->with('success', 'Privilege revoked successfully!');
    }
}
