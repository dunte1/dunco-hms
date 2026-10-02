<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\AuditLog;
use App\Models\BreakGlassEvent;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

/**
 * Break-glass emergency access.
 *
 * Workflow: restricted record -> emergency access request (reason required)
 * -> temporary authorization recorded -> full audit -> security review.
 *
 * Break-glass is NOT a normal access shortcut. Every grant is audited.
 */
class BreakGlassController extends Controller
{
    public function index(Request $request): View
    {
        $events = BreakGlassEvent::with(['user', 'patient', 'reviewer'])
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->string('status')))
            ->latest()
            ->paginate(25);

        return view('hms.security.break-glass', compact('events'));
    }

    public function create(): View
    {
        return view('hms.security.break-glass-create', [
            'patients' => Patient::orderBy('last_name')->limit(200)->get(['id', 'patient_no', 'first_name', 'last_name']),
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'nullable|exists:patients,id',
            'reason' => 'required|string|min:20|max:1000',
            'auditable_type' => 'nullable|string|max:255',
            'auditable_id' => 'nullable|integer',
        ]);

        $event = BreakGlassEvent::create([
            'user_id' => auth()->id(),
            'patient_id' => $data['patient_id'] ?? null,
            'reason' => $data['reason'],
            'auditable_type' => $data['auditable_type'] ?? (!empty($data['patient_id']) ? \App\Models\Patient::class : 'BreakGlassEvent'),
            'auditable_id' => $data['auditable_id'] ?? $data['patient_id'] ?? null,
            'status' => 'approved', // Emergency access is granted immediately but fully audited
        ]);

        AuditLog::log(
            'user',
            auth()->id(),
            'break_glass.access',
            'BreakGlassEvent',
            $event->id,
            null,
            [
                'patient_id' => $data['patient_id'] ?? null,
                'reason' => $data['reason'],
                'status' => 'approved',
            ],
            'Break-glass emergency access granted: ' . mb_substr($data['reason'], 0, 200)
        );

        $redirect = auth()->user()?->can('manage break glass events') || auth()->user()?->can('view audit logs')
            ? route('break-glass.index')
            : url('/dashboard');

        return redirect($redirect)
            ->with('status', 'Emergency access recorded and audited. Security review required.');
    }

    public function approve(BreakGlassEvent $event): RedirectResponse
    {
        if (!$event->isPending()) {
            return redirect()->route('break-glass.index')->with('error', 'Event already reviewed.');
        }

        $event->approve(auth()->id());

        AuditLog::log(
            'user',
            auth()->id(),
            'break_glass.review.approve',
            'BreakGlassEvent',
            $event->id,
            ['status' => 'pending'],
            ['status' => 'approved', 'reviewed_by' => auth()->id()],
            'Break-glass event approved after review'
        );

        return redirect()->route('break-glass.index')->with('status', 'Break-glass event approved.');
    }

    public function reject(BreakGlassEvent $event): RedirectResponse
    {
        if (!$event->isPending()) {
            return redirect()->route('break-glass.index')->with('error', 'Event already reviewed.');
        }

        $event->reject(auth()->id());

        AuditLog::log(
            'user',
            auth()->id(),
            'break_glass.review.reject',
            'BreakGlassEvent',
            $event->id,
            ['status' => 'pending'],
            ['status' => 'rejected', 'reviewed_by' => auth()->id()],
            'Break-glass event rejected after review'
        );

        return redirect()->route('break-glass.index')->with('status', 'Break-glass event rejected.');
    }
}

