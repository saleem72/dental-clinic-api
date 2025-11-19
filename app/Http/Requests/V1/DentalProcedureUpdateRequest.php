<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class DentalProcedureUpdateRequest extends FormRequest
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
        $procedureId = $this->route('procedure')->id;
        return [
            "name" => 'required|string',
            'dental_code' => [
                'required',
                'string',
                // Use Rule::unique() and ignore the current ID
                Rule::unique('dental_procedures', 'dental_code')->ignore($procedureId),
            ],
            "fee" => 'required|numeric',
            "description" => 'nullable|string',
            "is_active" => 'required|boolean',
        ];
    }

    /**
     * Prepare the data for validation.
     *
     * @return void
     */
    protected function prepareForValidation()
    {
        if ($this->has('cost')) {
            $this->merge([
                'fee' => $this->cost,
            ]);
        }
    }

    protected function passedValidation()
    {
        if ($this->has('cost')) {
            $this->offsetUnset('cost');
        }
    }
}
