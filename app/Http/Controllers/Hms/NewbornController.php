<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\Newborn;
use App\Models\Patient;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class NewbornController extends Controller
{
    public function index(Request $request): View
    {
        $newborns = Newborn::with(['patient', 'mother'])
            ->latest('date_of_birth')
            ->paginate(15);

        return view('hms.neonatal.newborns.index', compact('newborns'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'birth_report_id' => 'nullable|exists:birth_reports,id',
            'mother_patient_id' => 'nullable|exists:patients,id',
            'baby_name' => 'nullable|string|max:255',
            'sex' => 'required|in:male,female',
            'date_of_birth' => 'required|date',
            'time_of_birth' => 'required',
            'birth_weight_grams' => 'required|integer|min:1',
            'gestational_age_weeks' => 'required|numeric|min:20|max:44',
            'apgar_1_min' => 'required|integer|min:0|max:10',
            'apgar_5_min' => 'required|integer|min:0|max:10',
            'apgar_10_min' => 'nullable|integer|min:0|max:10',
        ]);

        Newborn::create($data);

        return redirect()->route('hms.neonatal.newborns.index')
            ->with('status', 'Newborn registered successfully.');
    }
}
