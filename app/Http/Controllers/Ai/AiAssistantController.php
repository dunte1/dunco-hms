<?php

namespace App\Http\Controllers\Ai;

use App\Http\Controllers\Controller;
use App\Models\AiAppointmentSuggestion;
use App\Models\AiDiagnosisSuggestion;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
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

        // Simulate AI logic for appointment suggestions
        $doctor = Doctor::find($data['doctor_id']);
        $patient = Patient::find($data['patient_id']);

        // Get doctor's available slots (simplified)
        $availableSlots = $this->getDoctorAvailableSlots($doctor, $data['preferred_date']);
        
        // AI suggestion logic (simplified)
        $bestSlot = $this->findBestTimeSlot($availableSlots, $data['preferred_time']);
        
        // Combine preferred date with the suggested time slot
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

        // Simulate AI diagnosis logic
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

    private function getDoctorAvailableSlots($doctor, $date)
    {
        // Simplified: return mock available slots
        return [
            '09:00', '09:30', '10:00', '10:30', '11:00', '11:30',
            '14:00', '14:30', '15:00', '15:30', '16:00', '16:30'
        ];
    }

    private function findBestTimeSlot($availableSlots, $preferredTime)
    {
        // Find closest available slot to preferred time
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

        return $closest; // Return time string instead of Carbon instance
    }

    private function calculateConfidenceScore($suggestedTime, $preferredTime)
    {
        $diff = abs(strtotime($suggestedTime) - strtotime($preferredTime));
        return max(60, 100 - ($diff / 60) * 2); // Higher score for closer times
    }

    private function analyzeSymptoms($symptoms, $vitalSigns, $labResults)
    {
        // Simplified AI diagnosis logic
        $diagnoses = [];
        
        if (in_array('fever', $symptoms) && in_array('cough', $symptoms)) {
            $diagnoses[] = ['condition' => 'Common Cold', 'probability' => 75];
        }
        
        if (in_array('chest_pain', $symptoms) && in_array('shortness_of_breath', $symptoms)) {
            $diagnoses[] = ['condition' => 'Possible Heart Condition', 'probability' => 85];
        }

        return $diagnoses;
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
