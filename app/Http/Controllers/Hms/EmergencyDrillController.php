<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\EmergencyDrillRecord;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class EmergencyDrillController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'drill_type' => 'required|in:fire,earthquake,evacuation,chemical_spill,active_shooter',
            'drill_date' => 'required|date',
            'participants_count' => 'required|integer|min:0',
            'assembly_point' => 'nullable|string',
            'duration_minutes' => 'nullable|integer|min:1',
            'performance_rating' => 'required|in:poor,fair,good,excellent',
            'lessons_learned' => 'nullable|string',
        ]);

        $data['conducted_by'] = auth()->id();

        EmergencyDrillRecord::create($data);

        return back()->with('status', 'Emergency drill record created.');
    }
}
