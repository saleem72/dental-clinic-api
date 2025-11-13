<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;

class CreateDentistRequest extends FormRequest
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
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'nullable|email|max:255|unique:users,email',
            'phone' => 'nullable|string|digits:10',
            'license_number' => 'nullable|string',
            'specialization' => 'nullable|string',
            'bio' => 'nullable|string',
            'commission_rate' => 'nullable|numeric',
            // $table->boolean('is_available')->default(true);
        ];
    }
}

