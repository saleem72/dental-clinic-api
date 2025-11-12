<?php

namespace App\Http\Requests\V1;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class CreateUserRequest extends FormRequest
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
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users', 'username'),
            ],
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'nullable',
                'email',
                'max:255',
                Rule::unique('users', 'email'),
            ],
            'phone' => [
                'nullable',
                'string',
                'digits:10',
            ],
            'roles' => [
                'required',     // Roles array must be present in the request
                'array',        // Must be an array (even if it's an array with one element)
                'min:1',        // Must contain at least one role ID
            ],
            'roles.*' => [
                'required',     // Each individual item in the array is required
                'integer',      // Each item must be an integer ID
                'exists:roles,id', // <-- CRUCIAL: Ensures the ID exists in the 'roles' table 'id' column
            ],
        ];
    }
}
