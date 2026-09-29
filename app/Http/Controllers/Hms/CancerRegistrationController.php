<?php

namespace App\Http\Controllers\Hms;

use App\Http\Controllers\Controller;
use App\Models\CancerRegistration;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class CancerRegistrationController extends Controller
{
    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'cancer_site' => 'required|string|max:255',
            'histology_type' => 'required|string|max:255',
            'laterality' => 'nullable|string|max:50',
            'grade' => 'nullable|string|max:50',
            'diagnosis_date' => 'required|date',
            'tnm_staging_t' => 'nullable|string|max:10',
            'tnm_staging_n' => 'nullable|string|max:10',
            'tnm_staging_m' => 'nullable|string|max:10',
            'overall_stage' => 'required|in:I,II,III,IV',
            'status' => 'nullable|in:active,in_remission,completed,deceased',
        ]);

        $data['registered_by'] = auth()->id();
        $data['status'] = $data['status'] ?? 'active';

        CancerRegistration::create($data);

        return redirect()->route('hms.oncology.registrations.index')
            ->with('status', 'Cancer registration recorded.');
    }

    public function index(Request $request): JsonResponse
    {
        $registrations = CancerRegistration::with('patient')
            ->when($request->patient_id, fn ($q) => $q->where('patient_id', $request->patient_id))
            ->orderByDesc('created_at')
            ->paginate(20);

        return response()->json($registrations);
    }
}
