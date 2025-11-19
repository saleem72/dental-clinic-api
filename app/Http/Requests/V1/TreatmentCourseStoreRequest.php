<?php

namespace App\Http\Requests\V1;

use App\Enums\TreatmentStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TreatmentCourseStoreRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'patient_id' => 'required|exists:patients,id',
            'dentist_id' => 'required|exists:dentists,id',
            'started_at' => 'required|date|date_format:Y-m-d\TH:i:s\Z',
            'completed_at' => 'nullable|date|date_format:Y-m-d\TH:i:s\Z',
            'notes' => 'nullable|string',
            'status' => ['required', Rule::in(array_column(TreatmentStatus::cases(), 'value'))],
        ];
    }
}

