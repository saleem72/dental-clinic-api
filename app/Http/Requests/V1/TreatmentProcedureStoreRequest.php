<?php

namespace App\Http\Requests\V1;

use App\Enums\Tooth;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class TreatmentProcedureStoreRequest extends FormRequest
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
            "treatment_course_id" => ['required', 'integer'],
            "treatment_session_id" => ['required', 'integer'],
            "dentist_id" => ['required', 'integer'],
            "dental_procedure_id" => ['required', 'integer'],
            "tooth_code" => [
                'nullable',
                Rule::in(array_column(Tooth::cases(), 'value')),
            ],
            "performed_at" => ['required', 'date', 'date_format:Y-m-d\TH:i:s.u\Z'],
            "cost" => ['required', 'numeric'],
            "notes" => ['nullable', 'string']
        ];
    }

    protected function prepareForValidation()
    {
        if ($this->has('treatment_id')) {
            $this->merge([
                'treatment_course_id' => $this->treatment_id,
            ]);
            $this->offsetUnset('treatment_id');
        }
        if ($this->has('session_id')) {
            $this->merge([
                'treatment_session_id' => $this->session_id,
            ]);
            $this->offsetUnset('session_id');
        }
    }
}
