<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Triage;
use App\Models\OpdVisit;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class TriageController extends ApiController
{
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'opd_visit_id' => 'nullable|exists:opd_visits,id',
            'ipd_admission_id' => 'nullable|exists:ipd_admissions,id',
            'priority_level' => 'required|in:emergency,urgent,semi_urgent,non_urgent',
            'category_id' => 'nullable|exists:triage_categories,id',
            'pain_score' => 'nullable|integer|min:0|max:10',
            'gcs_score' => 'nullable|integer|min:3|max:15',
            'pregnancy_status' => 'nullable|in:yes,no,unknown',
            'temperature' => 'nullable|numeric|min:30|max:45',
            'pulse_rate' => 'nullable|integer|min:30|max:250',
            'systolic_bp' => 'nullable|integer|min:50|max:300',
            'diastolic_bp' => 'nullable|integer|min:20|max:200',
            'respiratory_rate' => 'nullable|integer|min:5|max:60',
            'oxygen_saturation' => 'nullable|numeric|min:50|max:100',
            'blood_glucose' => 'nullable|numeric|min:0|max:50',
            'weight_kg' => 'nullable|numeric|min:0|max:300',
            'height_cm' => 'nullable|numeric|min:0|max:250',
            'chief_complaint' => 'nullable|string',
            'triage_notes' => 'nullable|string',
        ]);

        $data['triage_number'] = 'TRI-' . date('Y') . '-' . str_pad(Triage::count() + 1, 6, '0', STR_PAD_LEFT);
        $data['triaged_by'] = auth()->id();
        $data['triaged_at'] = now();
        $data['pregnancy_status'] = $data['pregnancy_status'] ?? 'unknown';

        $triage = Triage::create($data);

        if (!empty($data['opd_visit_id'])) {
            OpdVisit::where('id', $data['opd_visit_id'])->update([
                'status' => 'triaged',
                'triage_notes' => $data['triage_notes'] ?? null,
            ]);
        }

        return $this->created($triage->load(['patient', 'category']), 'Triage record created');
    }

    public function queue(Request $request): JsonResponse
    {
        $query = Triage::with(['patient', 'category', 'triager']);

        if ($request->filled('priority_level')) {
            $query->where('priority_level', $request->priority_level);
        }

        if ($request->filled('date')) {
            $query->whereDate('triaged_at', $request->date);
        }

        $perPage = min((int) ($request->input('per_page', 15)), 100);
        $paginator = $query->orderBy('triaged_at', 'desc')->paginate($perPage);

        return $this->paginated($paginator);
    }
}
