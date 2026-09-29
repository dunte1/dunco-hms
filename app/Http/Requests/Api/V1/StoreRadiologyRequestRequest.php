<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreRadiologyRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'radiology_test_id' => 'required|exists:radiology_tests,id',
            'request_date' => 'required|date',
            'appointment_date' => 'nullable|date|after_or_equal:today',
            'clinical_notes' => 'nullable|string',
            'status' => 'sometimes|in:pending,scheduled,completed,cancelled',
        ];
    }

    public function messages(): array
    {
        return [
            'patient_id.required' => 'Patient is required.',
            'patient_id.exists' => 'Selected patient does not exist.',
            'radiology_test_id.required' => 'Radiology test is required.',
            'radiology_test_id.exists' => 'Selected radiology test does not exist.',
            'request_date.required' => 'Request date is required.',
            'request_date.date' => 'Please provide a valid date.',
            'appointment_date.after_or_equal' => 'Appointment date must be today or in the future.',
            'status.in' => 'Status must be pending, scheduled, completed, or cancelled.',
        ];
    }
}
