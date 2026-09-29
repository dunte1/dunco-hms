<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdateLabRequestRequest extends FormRequest
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
            'opd_visit_id' => 'nullable|exists:opd_visits,id',
            'request_date' => 'sometimes|date',
            'clinical_notes' => 'nullable|string',
            'status' => 'sometimes|in:pending,in_progress,completed,cancelled',
            'results_notes' => 'nullable|string',
        ];
    }
}
