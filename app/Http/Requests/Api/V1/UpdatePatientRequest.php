<?php

namespace App\Http\Requests\Api\V1;

use Illuminate\Foundation\Http\FormRequest;

class UpdatePatientRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $patientId = $this->route('patient')?->id ?? $this->route('patient');

        return [
            'first_name' => 'sometimes|string|max:255',
            'last_name' => 'sometimes|string|max:255',
            'email' => 'sometimes|email|unique:patients,email,' . $patientId,
            'phone' => 'sometimes|string|max:20',
            'dob' => 'sometimes|date|before:today',
            'gender' => 'sometimes|in:male,female,other',
            'address' => 'nullable|string|max:500',
            'national_id' => 'nullable|string|max:50',
        ];
    }

    public function messages(): array
    {
        return [
            'dob.before' => 'Date of birth must be in the past.',
            'gender.in' => 'Gender must be male, female, or other.',
            'email.unique' => 'A patient with this email already exists.',
            'email.email' => 'Please provide a valid email address.',
        ];
    }
}
