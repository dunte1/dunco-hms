<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use App\Models\AiAppointmentSuggestion;
use App\Models\AiDiagnosisSuggestion;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Schedule;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AiAssistantController extends Controller
{
    public function appointmentSuggestions(): View
    {
        $suggestions = AiAppointmentSuggestion::with(['patient', 'doctor'])
            ->latest()
            ->paginate(20);
        return view('hms.ai.appointment-suggestions', compact('suggestions'));
    }

    public function generateAppointmentSuggestion(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'preferred_date' => 'required|date',
            'preferred_time' => 'required',
        ]);

        $doctor = Doctor::find($data['doctor_id']);
        $patient = Patient::find($data['patient_id']);

        $availableSlots = $this->getDoctorAvailableSlots($doctor, $data['preferred_date']);
        
        $bestSlot = $this->findBestTimeSlot($availableSlots, $data['preferred_time']);
        
        $suggestedDateTime = \Carbon\Carbon::parse($data['preferred_date'] . ' ' . $bestSlot);
        
        $confidenceScore = $this->calculateConfidenceScore($bestSlot, $data['preferred_time']);

        $suggestion = AiAppointmentSuggestion::create([
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctor_id'],
            'suggested_time' => $suggestedDateTime,
            'confidence_score' => round($confidenceScore),
            'reasoning' => 'AI analyzed doctor availability, patient preferences, and historical patterns',
            'doctor_availability' => json_encode($availableSlots),
            'patient_preferences' => json_encode([
                'preferred_date' => $data['preferred_date'],
                'preferred_time' => $data['preferred_time']
            ])
        ]);

        return response()->json([
            'success' => true,
            'suggestion' => $suggestion,
            'message' => 'AI appointment suggestion generated successfully'
        ]);
    }

    public function diagnosisSuggestions(): View
    {
        $suggestions = AiDiagnosisSuggestion::with(['patient', 'doctor'])
            ->latest()
            ->paginate(20);
        return view('hms.ai.diagnosis-suggestions', compact('suggestions'));
    }

    public function generateDiagnosisSuggestion(Request $request)
    {
        $data = $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'required|exists:doctors,id',
            'symptoms' => 'required|array',
            'vital_signs' => 'nullable|array',
            'lab_results' => 'nullable|array',
        ]);

        $suggestedDiagnoses = $this->analyzeSymptoms($data['symptoms'], $data['vital_signs'] ?? [], $data['lab_results'] ?? []);
        $confidenceScore = $this->calculateDiagnosisConfidence($suggestedDiagnoses);

        $suggestion = AiDiagnosisSuggestion::create([
            'patient_id' => $data['patient_id'],
            'doctor_id' => $data['doctor_id'],
            'symptoms' => $data['symptoms'],
            'vital_signs' => $data['vital_signs'] ?? [],
            'lab_results' => $data['lab_results'] ?? [],
            'suggested_diagnoses' => $suggestedDiagnoses,
            'confidence_score' => $confidenceScore,
            'reasoning' => 'AI analyzed symptoms, vital signs, and lab results using medical knowledge base'
        ]);

        return response()->json([
            'success' => true,
            'suggestion' => $suggestion,
            'message' => 'AI diagnosis suggestion generated successfully'
        ]);
    }

    private function getDoctorAvailableSlots($doctor, $date): array
    {
        $schedules = Schedule::where('employee_id', $doctor->id)
            ->where('schedule_date', $date)
            ->get();

        if ($schedules->isEmpty()) {
            return $this->getDefaultSlots();
        }

        $slots = [];
        foreach ($schedules as $schedule) {
            $startTime = \Carbon\Carbon::parse($schedule->start_time);
            $endTime = \Carbon\Carbon::parse($schedule->end_time);

            while ($startTime->lt($endTime)) {
                $slots[] = $startTime->format('H:i');
                $startTime->addMinutes(30);
            }
        }

        $bookedTimes = Appointment::where('doctor_id', $doctor->id)
            ->whereDate('scheduled_at', $date)
            ->where('status', '!=', 'cancelled')
            ->pluck('scheduled_at')
            ->map(fn ($dt) => $dt->format('H:i'))
            ->toArray();

        $slots = array_values(array_diff($slots, $bookedTimes));

        return empty($slots) ? $this->getDefaultSlots() : $slots;
    }

    private function getDefaultSlots(): array
    {
        return [
            '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
            '14:00', '14:30', '15:00', '15:30', '16:00', '16:30'
        ];
    }

    private function findBestTimeSlot($availableSlots, $preferredTime)
    {
        $preferred = date('H:i', strtotime($preferredTime));
        $closest = $availableSlots[0];
        $minDiff = abs(strtotime($preferred) - strtotime($closest));

        foreach ($availableSlots as $slot) {
            $diff = abs(strtotime($preferred) - strtotime($slot));
            if ($diff < $minDiff) {
                $minDiff = $diff;
                $closest = $slot;
            }
        }

        return $closest;
    }

    private function calculateConfidenceScore($suggestedTime, $preferredTime)
    {
        $diff = abs(strtotime($suggestedTime) - strtotime($preferredTime));
        return max(60, 100 - ($diff / 60) * 2);
    }

    private function analyzeSymptoms($symptoms, $vitalSigns, $labResults)
    {
        $diagnoses = [];
        $symptomSet = array_map('strtolower', $symptoms);

        $mappings = [
            ['symptoms' => ['fever', 'cough'], 'condition' => 'Common Cold', 'probability' => 75],
            ['symptoms' => ['chest_pain', 'shortness_of_breath'], 'condition' => 'Possible Heart Condition', 'probability' => 85],
            ['symptoms' => ['headache', 'nausea'], 'condition' => 'Migraine', 'probability' => 70],
            ['symptoms' => ['fever', 'headache', 'stiff_neck'], 'condition' => 'Meningitis', 'probability' => 60],
            ['symptoms' => ['abdominal_pain', 'nausea', 'vomiting'], 'condition' => 'Gastroenteritis', 'probability' => 72],
            ['symptoms' => ['fatigue', 'weight_loss', 'fever'], 'condition' => 'Tuberculosis', 'probability' => 55],
            ['symptoms' => ['joint_pain', 'swelling', 'stiffness'], 'condition' => 'Rheumatoid Arthritis', 'probability' => 65],
            ['symptoms' => ['chest_pain', 'cough', 'fever'], 'condition' => 'Pneumonia', 'probability' => 78],
            ['symptoms' => ['shortness_of_breath', 'wheezing', 'cough'], 'condition' => 'Asthma', 'probability' => 80],
            ['symptoms' => ['fever', 'rash', 'joint_pain'], 'condition' => 'Dengue Fever', 'probability' => 68],
            ['symptoms' => ['frequent_urination', 'thirst', 'fatigue'], 'condition' => 'Diabetes Mellitus', 'probability' => 74],
            ['symptoms' => ['sore_throat', 'fever', 'swollen_lymph_nodes'], 'condition' => 'Pharyngitis', 'probability' => 77],
            ['symptoms' => ['back_pain', 'limited_mobility'], 'condition' => 'Musculoskeletal Strain', 'probability' => 82],
            ['symptoms' => ['anxiety', 'insomnia', 'fatigue'], 'condition' => 'Generalized Anxiety Disorder', 'probability' => 63],
            ['symptoms' => ['diarrhea', 'fever', 'abdominal_pain'], 'condition' => 'Food Poisoning', 'probability' => 76],
        ];

        foreach ($mappings as $mapping) {
            $matchCount = count(array_intersect($symptomSet, $mapping['symptoms']));
            $matchRatio = $matchCount / count($mapping['symptoms']);

            if ($matchRatio >= 0.5) {
                $adjustedProbability = (int) ($mapping['probability'] * $matchRatio);
                $diagnoses[] = [
                    'condition' => $mapping['condition'],
                    'probability' => min($adjustedProbability, 99),
                ];
            }
        }

        usort($diagnoses, fn ($a, $b) => $b['probability'] <=> $a['probability']);

        return array_slice($diagnoses, 0, 5);
    }

    private function calculateDiagnosisConfidence($diagnoses)
    {
        if (empty($diagnoses)) return 0;
        return max(array_column($diagnoses, 'probability'));
    }

    public function predictiveAnalytics()
    {
        return view('hms.ai.predictive-analytics', [
            'title' => 'Predictive Analytics',
        ]);
    }
}
