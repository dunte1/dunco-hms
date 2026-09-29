<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\IpcAudit;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class IpcAuditController extends Controller
{
    public function index(): View
    {
        $audits = IpcAudit::with(['ward', 'auditor'])
            ->orderByDesc('audit_date')
            ->paginate(20);

        return view('hms.ipc.audits', compact('audits'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'audit_type' => 'required|in:hand_hygiene,isolation,waste,sharps,environment',
            'ward_id' => 'nullable|exists:wards,id',
            'audit_date' => 'required|date',
            'score' => 'required|numeric|min:0|max:100',
            'findings' => 'required|string',
            'corrective_actions' => 'nullable|string',
            'next_audit_date' => 'nullable|date|after_or_equal:audit_date',
        ]);

        $data['auditor_id'] = auth()->id();
        IpcAudit::create($data);

        return back()->with('status', 'IPC audit recorded');
    }
}
