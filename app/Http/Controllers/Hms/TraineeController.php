<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\TraineeRecord;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class TraineeController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:200',
            'trainee_type' => 'required|in:student,intern,resident,fellow',
            'institution' => 'nullable|string|max:200',
            'department_id' => 'required|exists:employee_departments,id',
            'program_name' => 'nullable|string|max:150',
            'start_date' => 'required|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
            'supervisor_id' => 'nullable|exists:users,id',
            'user_id' => 'nullable|exists:users,id',
        ]);

        $validated['status'] = 'active';

        TraineeRecord::create($validated);

        return redirect()->route('hms.training.trainees.index')
            ->with('success', 'Trainee record created successfully.');
    }

    public function index(Request $request)
    {
        $query = TraineeRecord::with(['department', 'supervisor']);

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $trainees = $query->latest()->paginate(15);

        return view('hms.training.trainees.index', compact('trainees'));
    }
}
