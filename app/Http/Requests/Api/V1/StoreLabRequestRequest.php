<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class StoreLabRequestRequest extends FormRequest
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
            'opd_visit_id' => 'nullable|exists:opd_visits,id',
            'request_date' => 'required|date',
            'clinical_notes' => 'nullable|string',
            'status' => 'sometimes|in:pending,in_progress,completed,cancelled',
        ];
    }

    public function messages(): array
    {
        return [
            'patient_id.required' => 'Patient is required.',
            'patient_id.exists' => 'Selected patient does not exist.',
            'request_date.required' => 'Request date is required.',
            'request_date.date' => 'Please provide a valid date.',
            'status.in' => 'Status must be pending, in_progress, completed, or cancelled.',
        ];
    }
}
