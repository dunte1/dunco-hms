<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use Carbon\Carbon;

class EllianaDAssistantService
{
    private $apiKey;
    private $baseUrl = 'https://openrouter.ai/api/v1';
    
    // Free models from OpenRouter for reasoning and knowledge
    private $reasoningModel = 'google/gemini-flash-1.5'; // Free reasoning model
    private $knowledgeModel = 'mistralai/mistral-7b-instruct:free'; // Free knowledge model
    
    public function __construct()
    {
        $this->apiKey = config('services.openrouter.api_key');
        
        // Warn if API key is missing but continue with fallback
        if (empty($this->apiKey)) {
            Log::warning('OpenRouter API key not configured. Elliana D will use fallback responses.');
        }
    }
    
    /**
     * Process user message and determine intent
     */
    public function processMessage(string $message, ?int $userId = null): array
    {
        try {
            // Determine intent using reasoning model
            $intent = $this->determineIntent($message);
            
            switch ($intent['type']) {
                case 'book_appointment':
                    return $this->handleAppointmentBooking($message, $userId, $intent);
                    
                case 'medical_query':
                    return $this->handleMedicalQuery($message, $userId);
                    
                case 'general_inquiry':
                    return $this->handleGeneralInquiry($message);
                    
                default:
                    return [
                        'response' => "Hello! I'm Elliana D, your virtual nurse assistant. I can help you with:\n\n- Booking appointments\n- Answering medical questions\n- General inquiries\n\nHow can I assist you today?",
                        'type' => 'general',
                        'actions' => []
                    ];
            }
        } catch (\Exception $e) {
            Log::error('Elliana D Error: ' . $e->getMessage());
            return [
                'response' => "I apologize, but I'm experiencing a technical issue. Please try again or contact our reception desk.",
                'type' => 'error',
                'actions' => []
            ];
        }
    }
    
    /**
     * Determine user intent using AI reasoning
     */
    private function determineIntent(string $message): array
    {
        $prompt = "You are Elliana D, a nurse assistant. Analyze this user message and determine the intent. 
        
User message: {$message}

Respond with ONLY a JSON object with this structure:
{
    \"type\": \"book_appointment|medical_query|general_inquiry\",
    \"confidence\": 0.0-1.0,
    \"extracted_data\": {}
}

For book_appointment, extract: patient_name, preferred_date, preferred_time, reason, doctor_preference
For medical_query, extract: symptom, urgency
For general_inquiry, extract: topic";

        $response = $this->callOpenRouter($this->reasoningModel, $prompt);
        
        // Parse JSON response
        $intent = json_decode($response, true);
        
        if (!$intent || !isset($intent['type'])) {
            // Fallback to simple keyword matching
            return $this->fallbackIntentDetection($message);
        }
        
        return $intent;
    }
    
    /**
     * Fallback intent detection using keywords
     */
    private function fallbackIntentDetection(string $message): array
    {
        $lowerMessage = strtolower($message);
        
        $appointmentKeywords = ['appointment', 'book', 'schedule', 'visit', 'see doctor', 'consultation'];
        $medicalKeywords = ['symptom', 'pain', 'feeling', 'ill', 'sick', 'ache', 'problem', 'condition'];
        
        $appointmentScore = 0;
        $medicalScore = 0;
        
        foreach ($appointmentKeywords as $keyword) {
            if (strpos($lowerMessage, $keyword) !== false) {
                $appointmentScore++;
            }
        }
        
        foreach ($medicalKeywords as $keyword) {
            if (strpos($lowerMessage, $keyword) !== false) {
                $medicalScore++;
            }
        }
        
        if ($appointmentScore > $medicalScore && $appointmentScore > 0) {
            return [
                'type' => 'book_appointment',
                'confidence' => min($appointmentScore / 3, 1.0),
                'extracted_data' => []
            ];
        } elseif ($medicalScore > 0) {
            return [
                'type' => 'medical_query',
                'confidence' => min($medicalScore / 3, 1.0),
                'extracted_data' => []
            ];
        }
        
        return [
            'type' => 'general_inquiry',
            'confidence' => 0.5,
            'extracted_data' => []
        ];
    }
    
    /**
     * Handle appointment booking
     */
    private function handleAppointmentBooking(string $message, ?int $userId, array $intent): array
    {
        // Extract appointment details using AI
        $details = $this->extractAppointmentDetails($message);
        
        // Validate and create appointment
        $validation = $this->validateAppointmentData($details, $userId);
        
        if (!$validation['valid']) {
            return [
                'response' => $validation['message'],
                'type' => 'appointment_booking',
                'actions' => [
                    'needs_clarification' => true,
                    'missing_fields' => $validation['missing_fields'] ?? []
                ]
            ];
        }
        
        // Create appointment
        $appointment = $this->createAppointment($details, $userId);
        
        if ($appointment) {
            return [
                'response' => "✅ Appointment booked successfully!\n\n📅 Date: " . $appointment->appointment_date->format('M d, Y') . "\n⏰ Time: " . $appointment->appointment_time . "\n👨‍⚕️ Doctor: " . ($appointment->doctor->first_name ?? 'TBD') . "\n\nYou'll receive a confirmation shortly. Please arrive 15 minutes early.",
                'type' => 'appointment_booking',
                'actions' => [
                    'appointment_id' => $appointment->id,
                    'confirm' => true
                ]
            ];
        }
        
        return [
            'response' => "I encountered an issue booking your appointment. Please contact our reception at [phone number] or try again.",
            'type' => 'appointment_booking',
            'actions' => []
        ];
    }
    
    /**
     * Extract appointment details from message
     */
    private function extractAppointmentDetails(string $message): array
    {
        $prompt = "Extract appointment booking details from this message:
        
Message: {$message}

Extract and return ONLY JSON:
{
    \"patient_name\": \"name or null\",
    \"preferred_date\": \"YYYY-MM-DD or null\",
    \"preferred_time\": \"HH:MM or null\",
    \"reason\": \"consultation reason\",
    \"doctor_preference\": \"doctor name or specialization or null\",
    \"urgency\": \"routine|urgent|emergency\"
}";

        $response = $this->callOpenRouter($this->reasoningModel, $prompt);
        $details = json_decode($response, true);
        
        return $details ?? [];
    }
    
    /**
     * Validate appointment data
     */
    private function validateAppointmentData(array $details, ?int $userId): array
    {
        $missing = [];
        
        // Check if user is logged in or has patient name
        if (!$userId && empty($details['patient_name'])) {
            $missing[] = 'patient identification';
        }
        
        // Check date
        if (empty($details['preferred_date'])) {
            $missing[] = 'preferred date';
        } else {
            // Validate date format and future date
            try {
                $date = Carbon::parse($details['preferred_date']);
                if ($date->isPast()) {
                    return [
                        'valid' => false,
                        'message' => "I can't book appointments in the past. Please provide a future date.",
                        'missing_fields' => []
                    ];
                }
            } catch (\Exception $e) {
                $missing[] = 'valid date';
            }
        }
        
        // Check reason
        if (empty($details['reason'])) {
            $missing[] = 'appointment reason';
        }
        
        if (!empty($missing)) {
            return [
                'valid' => false,
                'message' => "I need a bit more information to book your appointment. Please provide: " . implode(', ', $missing) . ".",
                'missing_fields' => $missing
            ];
        }
        
        return ['valid' => true];
    }
    
    /**
     * Create appointment
     */
    private function createAppointment(array $details, ?int $userId): ?Appointment
    {
        try {
            // Find patient
            $patient = null;
            if ($userId) {
                $patient = Patient::where('user_id', $userId)->first();
            } elseif (!empty($details['patient_name'])) {
                // Try to find by name
                $patient = Patient::where('first_name', 'LIKE', '%' . $details['patient_name'] . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $details['patient_name'] . '%')
                    ->first();
            }
            
            if (!$patient) {
                return null;
            }
            
            // Find doctor if preference specified
            $doctor = null;
            if (!empty($details['doctor_preference'])) {
                $doctor = Doctor::where('first_name', 'LIKE', '%' . $details['doctor_preference'] . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $details['doctor_preference'] . '%')
                    ->orWhere('specialization', 'LIKE', '%' . $details['doctor_preference'] . '%')
                    ->first();
            }
            
            // Default to first available doctor if none specified
            if (!$doctor) {
                $doctor = Doctor::where('is_active', true)->first();
            }
            
            // Set default time if not provided
            $time = $details['preferred_time'] ?? '09:00';
            $date = Carbon::parse($details['preferred_date']);
            
            $appointment = Appointment::create([
                'patient_id' => $patient->id,
                'doctor_id' => $doctor->id ?? null,
                'appointment_date' => $date,
                'appointment_time' => $time,
                'reason' => $details['reason'] ?? 'General consultation',
                'status' => 'scheduled',
                'source' => 'elliana_d_assistant',
            ]);
            
            return $appointment;
        } catch (\Exception $e) {
            Log::error('Appointment creation error: ' . $e->getMessage());
            return null;
        }
    }
    
    /**
     * Handle medical queries with knowledge base
     */
    private function handleMedicalQuery(string $message, ?int $userId): array
    {
        $knowledgeBase = "You are Elliana D, a friendly and knowledgeable virtual nurse assistant. Provide helpful, professional medical guidance based on general medical knowledge. Be empathetic, clear, and supportive.

User query: {$message}

Provide a comprehensive, helpful response that:
1. Acknowledges the user's concern with empathy
2. Provides relevant general medical information when appropriate
3. Offers practical suggestions or next steps
4. Naturally suggests booking an appointment if professional consultation would be beneficial";

        $response = $this->callOpenRouter($this->knowledgeModel, $knowledgeBase);
        
        return [
            'response' => $response,
            'type' => 'medical_query',
            'actions' => [
                'suggest_appointment' => true,
                'medical_disclaimer' => true
            ]
        ];
    }
    
    /**
     * Handle general inquiries
     */
    private function handleGeneralInquiry(string $message): array
    {
        $prompt = "You are Elliana D, a friendly virtual nurse assistant at a hospital. 
        
User message: {$message}

Provide a helpful, professional response. Include relevant information about hospital services, hours, location, or direct them appropriately.";

        $response = $this->callOpenRouter($this->reasoningModel, $prompt);
        
        return [
            'response' => $response,
            'type' => 'general_inquiry',
            'actions' => []
        ];
    }
    
    /**
     * Call OpenRouter API
     */
    private function callOpenRouter(string $model, string $prompt): string
    {
        // If API key is not configured, use fallback responses
        if (empty($this->apiKey)) {
            return $this->getFallbackResponse($prompt);
        }
        
        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(30)->post("{$this->baseUrl}/chat/completions", [
                'model' => $model,
                'messages' => [
                    [
                        'role' => 'user',
                        'content' => $prompt
                    ]
                ],
                'temperature' => 0.7,
                'max_tokens' => 500,
            ]);
            
            if ($response->successful()) {
                $data = $response->json();
                return $data['choices'][0]['message']['content'] ?? '';
            }
            
            Log::error('OpenRouter API Error: ' . $response->body());
            return $this->getFallbackResponse($prompt);
        } catch (\Exception $e) {
            Log::error('OpenRouter Call Error: ' . $e->getMessage());
            return $this->getFallbackResponse($prompt);
        }
    }
    
    /**
     * Get fallback response when AI is unavailable
     */
    private function getFallbackResponse(string $prompt): string
    {
        $lowerPrompt = strtolower($prompt);
        
        // Simple keyword-based fallback
        if (strpos($lowerPrompt, 'appointment') !== false || strpos($lowerPrompt, 'book') !== false) {
            return "I can help you book an appointment! Please provide:\n- Your name\n- Preferred date and time\n- Reason for visit\n\nOr you can say 'I want to book an appointment' and I'll guide you through the process.";
        }
        
        if (strpos($lowerPrompt, 'medical') !== false || strpos($lowerPrompt, 'symptom') !== false || strpos($lowerPrompt, 'pain') !== false) {
            return "I understand you have a medical concern. Let me help you with some general information.\n\nBased on what you've described, I'd recommend:\n1. Monitoring your symptoms\n2. Resting if needed\n3. Staying hydrated\n4. Booking an appointment with one of our doctors for a proper evaluation\n\nWould you like me to help you book an appointment? I can find available slots and schedule you right away.";
        }
        
        return "Hello! I'm Elliana D, your virtual nurse assistant. I'm here to help you with:\n\n**✨ Services I offer:**\n• 📅 Booking and scheduling appointments\n• 🏥 Answering medical questions and providing guidance\n• 📋 Hospital information and services\n• 💬 General inquiries\n\n**How can I assist you today?** Feel free to ask me anything or tell me what you need help with!";
    }
}

