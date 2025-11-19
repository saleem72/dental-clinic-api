<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class DentalProcedureStoreRequest extends FormRequest
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
            "name" => 'required|string',
            "dental_code" => 'required|string|unique:dental_procedures,dental_code',
            "fee" => 'required|numeric',
            "description" => 'nullable|string',
            // "is_active" => '',
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
