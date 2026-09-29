<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\PostnatalVisit;
use App\Models\Pregnancy;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class PostnatalVisitController extends Controller
{
    public function store(Request $request, Pregnancy $pregnancy): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'visit_date' => 'required|date',
            'visit_day_postpartum' => 'required|integer|min:0',
            'blood_pressure_sys' => 'nullable|integer|min:50|max:300',
            'blood_pressure_dia' => 'nullable|integer|min:30|max:200',
            'uterine_involution' => 'nullable|in:good,poor',
            'lochia' => 'nullable|in:normal,abnormal',
            'breast_feeding' => 'nullable|in:yes,no,difficulty',
            'family_planning_counselled' => 'boolean',
            'family_planning_method' => 'nullable|string|max:50',
            'wound_check' => 'nullable|string|max:100',
            'mental_health_screening' => 'nullable|string|max:100',
            'complications' => 'nullable|string',
        ]);

        $data['pregnancy_id'] = $pregnancy->id;
        $data['visited_by'] = auth()->id();
        $data['family_planning_counselled'] = $data['family_planning_counselled'] ?? false;

        PostnatalVisit::create($data);

        return back()->with('success', 'Postnatal visit recorded successfully!');
    }
}
