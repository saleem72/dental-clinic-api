<?php

namespace App\Http\Requests\V1;

use App\Enums\Gender;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PotentialPatientRequest extends FormRequest
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
            'created_by_id'  => 'required|integer|exists:users,id',
            'assigned_to_id' => 'required|integer|exists:dentists,id',
            "name"            => 'required|string',
            "email"           => 'nullable|email|unique:users,email',
            "phone"           => 'required|digits:10',
            "gender"          => ['required', Rule::in(array_column(Gender::cases(), 'value'))],
            "date_of_birth"   => 'nullable|date',
            "medical_notes"   => 'nullable|string',
            "medical_history" => 'nullable|string',
        ];
    }
}
