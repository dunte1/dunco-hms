<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Newborn;
use App\Models\NeonatalAssessment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class NeonatalAssessmentController extends Controller
{
    public function store(Request $request, Newborn $newborn): RedirectResponse
    {
        $data = $request->validate([
            'assessment_date' => 'required|date',
            'weight_grams' => 'required|integer|min:1',
            'length_cm' => 'required|numeric|min:1',
            'head_circumference_cm' => 'required|numeric|min:1',
            'temperature' => 'required|numeric|min:30|max:42',
            'heart_rate' => 'required|integer|min:50|max:200',
            'respiratory_rate' => 'required|integer|min:15|max:80',
            'feeding_type' => 'required|in:breast,formula,mixed',
            'stool_passed' => 'required|boolean',
            'jaundice' => 'required|in:none,mild,moderate,severe',
            'reflexes' => 'required|in:present,absent',
            'cried_at_birth' => 'required|boolean',
            'notes' => 'nullable|string',
        ]);

        $data['newborn_id'] = $newborn->id;
        $data['assessed_by'] = auth()->id();

        NeonatalAssessment::create($data);

        return redirect()->route('hms.neonatal.newborns.index')
            ->with('status', 'Neonatal assessment recorded.');
    }
}
