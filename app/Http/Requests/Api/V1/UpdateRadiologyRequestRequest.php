<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateRadiologyRequestRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'patient_id' => 'sometimes|exists:patients,id',
            'doctor_id' => 'nullable|exists:doctors,id',
            'radiology_test_id' => 'sometimes|exists:radiology_tests,id',
            'request_date' => 'sometimes|date',
            'appointment_date' => 'nullable|date|after_or_equal:today',
            'clinical_notes' => 'nullable|string',
            'status' => 'sometimes|in:pending,scheduled,completed,cancelled',
            'findings' => 'nullable|string',
            'impression' => 'nullable|string',
            'image_path' => 'nullable|string|max:500',
        ];
    }
}
