<?php

namespace App\Http\Controllers\Security;

use App\Http\Controllers\Controller;
use App\Models\AccessEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AccessEventController extends Controller
{
    public function index(Request $request): View
    {
        $query = AccessEvent::with('user', 'employee');

        if ($request->filled('event_type')) {
            $query->where('event_type', $request->event_type);
        }
        if ($request->filled('location')) {
            $query->where('location', $request->location);
        }

        $events = $query->latest('event_time')->paginate(20);

        return view('hms.security.access-events.index', compact('events'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'user_id' => 'nullable|exists:users,id',
            'employee_id' => 'nullable|exists:employees,id',
            'event_type' => 'required|in:entry,exit,attempt_denied',
            'location' => 'required|string|max:255',
            'access_method' => 'required|in:badge,biometric,key,manual',
            'device_id' => 'nullable|string|max:100',
            'ip_address' => 'nullable|ip',
        ]);

        $data['event_time'] = now();

        AccessEvent::create($data);

        return redirect()->route('access-events.index')
            ->with('success', 'Access event recorded successfully.');
    }
}
