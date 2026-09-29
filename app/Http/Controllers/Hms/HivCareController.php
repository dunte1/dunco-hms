<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\HivCareEnrollment;
use App\Models\HtsEncounter;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HivCareController extends Controller
{
    public function index(Request $request): View
    {
        $query = HivCareEnrollment::with(['patient', 'htsEncounter', 'artRegimens', 'activeRegimen']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereHas('patient', function ($pq) use ($search) {
                    $pq->where('first_name', 'like', "%{$search}%")
                       ->orWhere('last_name', 'like', "%{$search}%")
                       ->orWhere('patient_no', 'like', "%{$search}%");
                })->orWhere('art_number', 'like', "%{$search}%");
            });
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $enrollments = $query->latest()->paginate(15)->withQueryString();

        $stats = [
            'total' => HivCareEnrollment::count(),
            'active' => HivCareEnrollment::where('status', 'active')->count(),
            'transferred_out' => HivCareEnrollment::where('status', 'transferred_out')->count(),
        ];

        return view('hms.hiv.care-index', compact('enrollments', 'stats'));
    }

    public function enroll(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'hts_encounter_id' => 'nullable|exists:hts_encounters,id',
            'enrollment_date' => 'required|date',
            'art_number' => 'nullable|string|unique:hiv_care_enrollments,art_number',
            'who_stage' => 'nullable|integer|min:1|max:4',
            'baseline_cd4' => 'nullable|integer|min:0',
            'baseline_viral_load' => 'nullable|integer|min:0',
        ]);

        $data['enrolled_by'] = auth()->id();

        $enrollment = HivCareEnrollment::create($data);

        return redirect()->route('hms.hiv.care.index')
            ->with('success', "Patient enrolled in HIV care with ART# {$enrollment->art_number}");
    }
}
