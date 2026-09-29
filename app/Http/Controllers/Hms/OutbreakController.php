<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\OutbreakEvent;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OutbreakController extends Controller
{
    public function index(): View
    {
        $outbreaks = OutbreakEvent::with('declaredBy')
            ->orderByDesc('declared_at')
            ->paginate(20);

        return view('hms.public-health.outbreaks', compact('outbreaks'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'disease_name' => 'required|string|max:255',
            'start_date' => 'required|date',
            'facility_ids' => 'nullable|array',
            'facility_ids.*' => 'exists:hospital_branches,id',
            'investigation_notes' => 'nullable|string',
        ]);

        $data['declared_by'] = auth()->id();
        $data['declared_at'] = now();
        $data['status'] = 'suspected';
        OutbreakEvent::create($data);

        return back()->with('status', 'Outbreak event declared');
    }

    public function update(Request $request, OutbreakEvent $outbreak): RedirectResponse
    {
        $data = $request->validate([
            'status' => 'required|in:suspected,confirmed,contained,ended',
            'total_cases' => 'nullable|integer|min:0',
            'total_deaths' => 'nullable|integer|min:0',
            'investigation_notes' => 'nullable|string',
        ]);

        $updates = $data;

        if ($data['status'] === 'contained' && $outbreak->contained_at === null) {
            $updates['contained_at'] = now();
        }

        if ($data['status'] === 'ended' && $outbreak->ended_at === null) {
            $updates['ended_at'] = now();
        }

        $outbreak->update($updates);

        return back()->with('status', 'Outbreak status updated');
    }
}
