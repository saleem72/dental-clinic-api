<?php

namespace App\Http\Requests\V1;

use App\Enums\TreatmentSessionStatus;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TreatmentSessionStoreRequest extends FormRequest
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
            'treatment_course_id' => 'required|integer',
            'dentist_id' => 'required|integer',
            // 'start_at' => 'nullable|date|date_format:Y-m-d\TH:i:s\Z',
            'start_at' => 'nullable|date_format:Y-m-d\TH:i:s.u\Z',
            'estimated_time' => 'required|integer',
            'notes' => 'nullable|string',
            'status' => ['required', Rule::in(array_column(TreatmentSessionStatus::cases(), 'value'))]
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->has('treatment_id')) {
            $this->merge([
                'treatment_course_id' => $this->treatment_id,
            ]);
            $this->offsetUnset('treatment_id');
        }
    }
}
